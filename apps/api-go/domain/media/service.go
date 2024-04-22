package media

import (
	mediaspb "api-go/gen/go/medias/v2"
	"api-go/infrastructure/auth"
	"api-go/infrastructure/s3"
	"api-go/model"
	"context"
	"fmt"
	"log"
	"strings"
	"time"

	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/status"
	"google.golang.org/protobuf/types/known/timestamppb"
)

var (
	ErrAlreadyExists = status.Error(codes.AlreadyExists, "already exists")
	ErrNotFound      = status.Error(codes.NotFound, "not found")
	ErrNoAuth        = status.Error(codes.Unauthenticated, "not authenticated")
	ErrNotAdmin      = status.Error(codes.PermissionDenied, "not admin")
)

type service struct {
	mediaspb.MediaServiceServer
	repository Repository
}

// NewService Create a new instance.
func NewService(r Repository) (mediaspb.MediaServiceServer, error) {
	return &service{
		repository: r,
	}, nil
}

func (s *service) Create(ctx context.Context, req *mediaspb.CreateRequest) (*mediaspb.CreateResponse, error) {
	authenticated, _ := auth.GetUser(ctx)

	if !authenticated {
		return nil, ErrNoAuth
	}

	// TODO Revert
	//isAdmin := user.IsAdmin()
	//if !isAdmin {
	//	return nil, ErrNotAdmin
	//}

	if err := req.ValidateAll(); err != nil {
		return nil, fmt.Errorf("request validation failed: %w", err)
	}

	// TODO CHeck if album exists

	bucketName := "temp"

	minioClient, err := s3.NewClient()
	if err != nil {
		log.Fatalln(err)
	}

	urls := make(map[string]string)

	for _, fileName := range req.FileNames {

		objectKey := fmt.Sprintf("%s/%d/%s", strings.ToLower(req.Type.String()), req.ResourceID, fileName)

		url, err := minioClient.Presign(ctx, "PUT", bucketName, objectKey, time.Duration(10*int64(time.Minute)), nil)
		if err != nil {
			return nil, fmt.Errorf("unable generate put signed url: %w", err)
		}

		urls[objectKey] = url.String()
	}

	return &mediaspb.CreateResponse{
		FileUploadUrls: urls,
	}, nil
}

func (s *service) Delete(ctx context.Context, req *mediaspb.DeleteRequest) (*mediaspb.DeleteResponse, error) {
	authenticated, user := auth.GetUser(ctx)

	if !authenticated {
		return nil, ErrNoAuth
	}

	if !user.IsAdmin() {
		return nil, ErrNotAdmin
	}

	err := s.repository.Delete(ctx, DeleteParams{ID: req.Id})
	if err != nil {
		return &mediaspb.DeleteResponse{}, fmt.Errorf("unable delete album: %w", err)
	}
	return &mediaspb.DeleteResponse{}, nil
}

func transformMediaFromDB(media model.Medium) *mediaspb.Media {
	return &mediaspb.Media{
		Id:       media.ID,
		Name:     media.Name,
		FileName: media.FileName,
		MimeType: media.MimeType,
		Size:     media.Size,
		CreatedAt: &timestamppb.Timestamp{
			Seconds: int64(media.CreatedAt.Second()),
			Nanos:   int32(media.CreatedAt.Nanosecond()),
		},
		UpdatedAt: &timestamppb.Timestamp{
			Seconds: int64(media.UpdatedAt.Second()),
			Nanos:   int32(media.UpdatedAt.Nanosecond()),
		},
		CustomProperties: &mediaspb.Media_CustomProperties{
			Height: media.CustomProperties.Height,
			Width:  media.CustomProperties.Width,
		},
	}
}
