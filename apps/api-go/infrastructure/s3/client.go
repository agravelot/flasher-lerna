package s3

import (
	"github.com/minio/minio-go/v7"
	"github.com/minio/minio-go/v7/pkg/credentials"
)

func NewClient() (*minio.Client, error) {
	// TODO Extract env
	accessKey := "MROzIQ2CXArhP8AIdEKv"
	secretKey := "a8iFE0aJaZpcZFYfweJD6wfyeeFElNOjpJ3y1Hjm"
	endpoint := "localhost:9000"
	useSSL := false

	// Initialize minio client object.
	minioClient, err := minio.New(endpoint, &minio.Options{
		Creds:  credentials.NewStaticV4(accessKey, secretKey, ""),
		Secure: useSSL,
	})

	return minioClient, err
}
