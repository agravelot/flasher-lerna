package blog

import (
	"context"
	"net/url"
	"strings"

	"github.com/go-kit/kit/endpoint"
	httptransport "github.com/go-kit/kit/transport/http"
)

// Endpoints collects all of the endpoints that compose a Blog service. It's
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
	PostBlogEndpoint    endpoint.Endpoint
	GetBlogListEndpoint endpoint.Endpoint
	GetBlogEndpoint     endpoint.Endpoint
	PutBlogEndpoint     endpoint.Endpoint
	PatchBlogEndpoint   endpoint.Endpoint
	DeleteBlogEndpoint  endpoint.Endpoint
}

// MakeServerEndpoints returns an Endpoints struct where each endpoint invokes
// the corresponding method on the provided service. Useful in a Blogsvc
// server.
func MakeServerEndpoints(s Service) Endpoints {
	return Endpoints{
		PostBlogEndpoint:    MakePostBlogEndpoint(s),
		GetBlogListEndpoint: MakeGetBlogListEndpoint(s),
		GetBlogEndpoint:     MakeGetBlogEndpoint(s),
		PutBlogEndpoint:     MakePutBlogEndpoint(s),
		PatchBlogEndpoint:   MakePatchBlogEndpoint(s),
		DeleteBlogEndpoint:  MakeDeleteBlogEndpoint(s),
	}
}

// MakeClientEndpoints returns an Endpoints struct where each endpoint invokes
// the corresponding method on the remote instance, via a transport/http.Client.
// Useful in a Blogsvc client.
func MakeClientEndpoints(instance string) (Endpoints, error) {
	if !strings.HasPrefix(instance, "http") {
		instance = "http://" + instance
	}
	tgt, err := url.Parse(instance)
	if err != nil {
		return Endpoints{}, err
	}
	tgt.Path = ""

	options := []httptransport.ClientOption{}

	// Note that the request encoders need to modify the request URL, changing
	// the path. That's fine: we simply need to provide specific encoders for
	// each endpoint.

	return Endpoints{
		PostBlogEndpoint:    httptransport.NewClient("POST", tgt, encodePostBlogRequest, decodePostBlogResponse, options...).Endpoint(),
		GetBlogEndpoint:     httptransport.NewClient("GET", tgt, encodeGetBlogRequest, decodeGetBlogResponse, options...).Endpoint(),
		GetBlogListEndpoint: httptransport.NewClient("GET", tgt, encodeGetBlogListRequest, decodeGetBlogResponse, options...).Endpoint(),
		PutBlogEndpoint:     httptransport.NewClient("PUT", tgt, encodePutBlogRequest, decodePutBlogResponse, options...).Endpoint(),
		PatchBlogEndpoint:   httptransport.NewClient("PATCH", tgt, encodePatchBlogRequest, decodePatchBlogResponse, options...).Endpoint(),
		DeleteBlogEndpoint:  httptransport.NewClient("DELETE", tgt, encodeDeleteBlogRequest, decodeDeleteBlogResponse, options...).Endpoint(),
	}, nil
}

// PostBlog implements Service. Primarily useful in a client.
func (e Endpoints) PostBlog(ctx context.Context, p Blog) error {
	request := postBlogRequest{Blog: p}
	response, err := e.PostBlogEndpoint(ctx, request)
	if err != nil {
		return err
	}
	resp := response.(postBlogResponse)
	return resp.Err
}

// GetBlogList implements Service. Primarily useful in a client.
func (e Endpoints) GetBlogList(ctx context.Context, id string) (PaginatedBlogs, error) {
	request := getBlogListRequest{}
	response, err := e.GetBlogListEndpoint(ctx, request)
	if err != nil {
		return PaginatedBlogs{}, err
	}
	resp := response.(getBlogListResponse)
	return resp.Data, resp.Err
}

// GetBlog implements Service. Primarily useful in a client.
func (e Endpoints) GetBlog(ctx context.Context, id string) (Blog, error) {
	request := getBlogRequest{ID: id}
	response, err := e.GetBlogEndpoint(ctx, request)
	if err != nil {
		return Blog{}, err
	}
	resp := response.(getBlogResponse)
	return resp.Blog, resp.Err
}

// PutBlog implements Service. Primarily useful in a client.
func (e Endpoints) PutBlog(ctx context.Context, id string, p Blog) error {
	request := putBlogRequest{ID: id, Blog: p}
	response, err := e.PutBlogEndpoint(ctx, request)
	if err != nil {
		return err
	}
	resp := response.(putBlogResponse)
	return resp.Err
}

// PatchBlog implements Service. Primarily useful in a client.
func (e Endpoints) PatchBlog(ctx context.Context, id string, p Blog) error {
	request := patchBlogRequest{ID: id, Blog: p}
	response, err := e.PatchBlogEndpoint(ctx, request)
	if err != nil {
		return err
	}
	resp := response.(patchBlogResponse)
	return resp.Err
}

// DeleteBlog implements Service. Primarily useful in a client.
func (e Endpoints) DeleteBlog(ctx context.Context, id string) error {
	request := deleteBlogRequest{ID: id}
	response, err := e.DeleteBlogEndpoint(ctx, request)
	if err != nil {
		return err
	}
	resp := response.(deleteBlogResponse)
	return resp.Err
}

// MakePostBlogEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePostBlogEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(postBlogRequest)
		e := s.PostBlog(ctx, req.Blog)
		return postBlogResponse{Err: e}, nil
	}
}

func MakeGetBlogListEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		p, e := s.GetBlogList(ctx)
		return getBlogListResponse{Data: p, Err: e}, nil
	}
}

// MakeGetBlogEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakeGetBlogEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(getBlogRequest)
		p, e := s.GetBlog(ctx, req.ID)
		return getBlogResponse{Blog: p, Err: e}, nil
	}
}

// MakePutBlogEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePutBlogEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(putBlogRequest)
		e := s.PutBlog(ctx, req.ID, req.Blog)
		return putBlogResponse{Err: e}, nil
	}
}

// MakePatchBlogEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePatchBlogEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(patchBlogRequest)
		e := s.PatchBlog(ctx, req.ID, req.Blog)
		return patchBlogResponse{Err: e}, nil
	}
}

// MakeDeleteBlogEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakeDeleteBlogEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(deleteBlogRequest)
		e := s.DeleteBlog(ctx, req.ID)
		return deleteBlogResponse{Err: e}, nil
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

type postBlogRequest struct {
	Blog Blog
}

type postBlogResponse struct {
	Err error `json:"err,omitempty"`
}

func (r postBlogResponse) error() error { return r.Err }

type getBlogListRequest struct{}

type getBlogListResponse struct {
	Data PaginatedBlogs `json:"data,omitempty"`
	Err  error          `json:"err,omitempty"`
}

type getBlogRequest struct {
	ID string
}

func (r getBlogListResponse) error() error { return r.Err }

type getBlogResponse struct {
	Blog Blog  `json:"Blog,omitempty"`
	Err  error `json:"err,omitempty"`
}

func (r getBlogResponse) error() error { return r.Err }

type putBlogRequest struct {
	ID   string
	Blog Blog
}

type putBlogResponse struct {
	Err error `json:"err,omitempty"`
}

func (r putBlogResponse) error() error { return nil }

type patchBlogRequest struct {
	ID   string
	Blog Blog
}

type patchBlogResponse struct {
	Err error `json:"err,omitempty"`
}

func (r patchBlogResponse) error() error { return r.Err }

type deleteBlogRequest struct {
	ID string
}

type deleteBlogResponse struct {
	Err error `json:"err,omitempty"`
}

func (r deleteBlogResponse) error() error { return r.Err }
