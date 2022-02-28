package album

import (
	"api-go/api"
	"context"

	"github.com/go-kit/kit/endpoint"
)

// Endpoints collects all of the endpoints that compose a Album service. It's
// meant to be used as a helper struct, to collect all of the endpoints into a
// single parameter.
//
// In a server, it's useful for functions that need to operate on a per-endpoint
// basis. For example, you might pass an Endpoints to a function that produces
// an http.Handler, with each method (endpoint) wired up to a specific path. (It
// is probably a mistake in design to invoke the Service methods on the
// Endpoints struct in a server.)
//
// In a client, it's useful to collect individually constructed endpoints into a
// single type that implements the Service interface. For example, you might
// construct individual endpoints using transport/http.NewClient, combine them
// into an Endpoints, and return it to the caller as a Service.
type Endpoints struct {
	PostAlbumEndpoint    endpoint.Endpoint
	GetAlbumListEndpoint endpoint.Endpoint
	GetAlbumEndpoint     endpoint.Endpoint
	PutAlbumEndpoint     endpoint.Endpoint
	PatchAlbumEndpoint   endpoint.Endpoint
	DeleteAlbumEndpoint  endpoint.Endpoint
}

// MakeServerEndpoints returns an Endpoints struct where each endpoint invokes
// the corresponding method on the provided service. Useful in a Albumsvc
// server.
func MakeServerEndpoints(s Service) Endpoints {
	return Endpoints{
		PostAlbumEndpoint:    MakePostAlbumEndpoint(s),
		GetAlbumListEndpoint: MakeGetAlbumListEndpoint(s),
		GetAlbumEndpoint:     MakeGetAlbumEndpoint(s),
		PutAlbumEndpoint:     MakePutAlbumEndpoint(s),
		PatchAlbumEndpoint:   MakePatchAlbumEndpoint(s),
		DeleteAlbumEndpoint:  MakeDeleteAlbumEndpoint(s),
	}
}

// MakeGetAlbumListEndpoint returns an endpoint via the passed service.
func MakeGetAlbumListEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(getAlbumListRequest)
		pa, e := s.GetAlbumList(ctx, AlbumListParams{Joins: req.Joins, PaginationParams: PaginationParams{Next: req.Next, Limit: req.Limit}})
		return getAlbumListResponse{PaginatedAlbums: pa, Err: e}, nil
	}
}

// MakePostAlbumEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePostAlbumEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(postAlbumRequest)
		a, e := s.PostAlbum(ctx, req.AlbumRequest)
		return postAlbumResponse{AlbumResponse: a, Err: e}, nil
	}
}

// MakeGetAlbumEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakeGetAlbumEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(getAlbumRequest)
		p, e := s.GetAlbum(ctx, req.ID)
		return getAlbumResponse{AlbumResponse: p, Err: e}, nil
	}
}

// MakePutAlbumEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePutAlbumEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(putAlbumRequest)
		a, e := s.PutAlbum(ctx, req.ID, req.AlbumRequest)
		return putAlbumResponse{AlbumResponse: a, Err: e}, nil
	}
}

// MakePatchAlbumEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePatchAlbumEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(patchAlbumRequest)
		a, e := s.PatchAlbum(ctx, req.Slug, req.AlbumUpdateRequest)
		return patchAlbumResponse{AlbumResponse: a, Err: e}, nil
	}
}

// MakeDeleteAlbumEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakeDeleteAlbumEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(deleteAlbumRequest)
		e := s.DeleteAlbum(ctx, req.ID)
		return deleteAlbumResponse{Err: e}, nil
	}
}

// We have two options to return errors from the business logic.
//
// We could return the error via the endpoint itself. That makes certain things
// a little bit easier, like providing non-200 HTTP responses to the client. But
// Go kit assumes that endpoint errors are (or may be treated as)
// transport-domain errors. For example, an endpoint error will count against a
// circuit breaker error count.
//
// Therefore, it's often better to return service (business logic) errors in the
// response object. This means we have to do a bit more work in the HTTP
// response encoder to detect e.g. a not-found error and provide a proper HTTP
// status code. That work is done with the errorer interface, in transport.go.
// Response types that may contain business-logic errors implement that
// interface.

type PaginationParams struct {
	Next  uint
	Limit int32
}

type AlbumListJoinsParams struct {
	Categories bool
	Medias     bool
}

type AlbumListParams struct {
	PaginationParams
	Joins AlbumListJoinsParams
}

type PaginatedAlbums struct {
	Data []AlbumResponse `json:"data"`
	Meta api.Meta        `json:"meta"`
}

type postAlbumRequest struct {
	AlbumRequest
}

type postAlbumResponse struct {
	AlbumResponse
	Err error `json:"err,omitempty"`
}

func (r postAlbumResponse) error() error { return r.Err }

type getAlbumListRequest struct {
	PaginationParams
	Joins AlbumListJoinsParams
}

type getAlbumListResponse struct {
	PaginatedAlbums
	Err error `json:"err,omitempty"`
}

type getAlbumRequest struct {
	ID string
}

func (r getAlbumListResponse) error() error { return r.Err }

type getAlbumResponse struct {
	AlbumResponse
	Err error `json:"err,omitempty"`
}

func (r getAlbumResponse) error() error { return r.Err }

type putAlbumRequest struct {
	ID string
	AlbumRequest
}

type putAlbumResponse struct {
	AlbumResponse
	Err error `json:"err,omitempty"`
}

func (r putAlbumResponse) error() error { return nil }

type patchAlbumRequest struct {
	Slug string
	AlbumUpdateRequest
}

type patchAlbumResponse struct {
	AlbumResponse
	Err error `json:"err,omitempty"`
}

func (r patchAlbumResponse) error() error { return r.Err }

type deleteAlbumRequest struct {
	ID string
}

type deleteAlbumResponse struct {
	Err error `json:"err,omitempty"`
}

func (r deleteAlbumResponse) error() error { return r.Err }
