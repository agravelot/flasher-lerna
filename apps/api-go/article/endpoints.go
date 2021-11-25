package article

import (
	"context"

	"github.com/go-kit/kit/endpoint"
)

// Endpoints collects all of the endpoints that compose a Article service. It's
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
	PostArticleEndpoint    endpoint.Endpoint
	GetArticleListEndpoint endpoint.Endpoint
	GetArticleEndpoint     endpoint.Endpoint
	PutArticleEndpoint     endpoint.Endpoint
	PatchArticleEndpoint   endpoint.Endpoint
	DeleteArticleEndpoint  endpoint.Endpoint
}

// MakeServerEndpoints returns an Endpoints struct where each endpoint invokes
// the corresponding method on the provided service. Useful in a Articlesvc
// server.
func MakeServerEndpoints(s Service) Endpoints {
	return Endpoints{
		PostArticleEndpoint:    MakePostArticleEndpoint(s),
		GetArticleListEndpoint: MakeGetArticleListEndpoint(s),
		GetArticleEndpoint:     MakeGetArticleEndpoint(s),
		PutArticleEndpoint:     MakePutArticleEndpoint(s),
		PatchArticleEndpoint:   MakePatchArticleEndpoint(s),
		DeleteArticleEndpoint:  MakeDeleteArticleEndpoint(s),
	}
}

// MakeGetArticleListEndpoint returns an endpoint via the passed service.
func MakeGetArticleListEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(getArticleListRequest)

		// if req.Page == 0 {
		// 	req.Page = 1
		// }

		if req.Limit == 0 {
			req.Limit = 10
		}

		pa, e := s.GetArticleList(ctx, PaginationParams{Next: req.Next, Limit: req.Limit})
		return getArticleListResponse{PaginatedArticles: pa, Err: e}, nil
	}
}

// MakePostArticleEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePostArticleEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(postArticleRequest)
		a, e := s.PostArticle(ctx, req.Article)
		return postArticleResponse{Article: a, Err: e}, nil
	}
}

// MakeGetArticleEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakeGetArticleEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(getArticleRequest)
		p, e := s.GetArticle(ctx, req.ID)
		return getArticleResponse{Article: p, Err: e}, nil
	}
}

// MakePutArticleEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePutArticleEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(putArticleRequest)
		a, e := s.PutArticle(ctx, req.ID, req.Article)
		return putArticleResponse{Article: a, Err: e}, nil
	}
}

// MakePatchArticleEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePatchArticleEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(patchArticleRequest)
		a, e := s.PatchArticle(ctx, req.Slug, req.Article)
		return patchArticleResponse{Article: a, Err: e}, nil
	}
}

// MakeDeleteArticleEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakeDeleteArticleEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(deleteArticleRequest)
		e := s.DeleteArticle(ctx, req.ID)
		return deleteArticleResponse{Err: e}, nil
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

type postArticleRequest struct {
	Article
}

type postArticleResponse struct {
	Article
	Err error `json:"err,omitempty"`
}

func (r postArticleResponse) error() error { return r.Err }

type getArticleListRequest struct {
	Next  string
	Limit int
}

type getArticleListResponse struct {
	PaginatedArticles
	Err error `json:"err,omitempty"`
}

type getArticleRequest struct {
	ID string
}

func (r getArticleListResponse) error() error { return r.Err }

type getArticleResponse struct {
	Article
	Err error `json:"err,omitempty"`
}

func (r getArticleResponse) error() error { return r.Err }

type putArticleRequest struct {
	ID string
	Article
}

type putArticleResponse struct {
	Article
	Err error `json:"err,omitempty"`
}

func (r putArticleResponse) error() error { return nil }

type patchArticleRequest struct {
	Slug string
	Article
}

type patchArticleResponse struct {
	Article
	Err error `json:"err,omitempty"`
}

func (r patchArticleResponse) error() error { return r.Err }

type deleteArticleRequest struct {
	ID string
}

type deleteArticleResponse struct {
	Err error `json:"err,omitempty"`
}

func (r deleteArticleResponse) error() error { return r.Err }
