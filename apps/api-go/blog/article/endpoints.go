package article

import (
	"context"
	"net/url"
	"strings"

	"github.com/go-kit/kit/endpoint"
	httptransport "github.com/go-kit/kit/transport/http"
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

// MakeClientEndpoints returns an Endpoints struct where each endpoint invokes
// the corresponding method on the remote instance, via a transport/http.Client.
// Useful in a Articlesvc client.
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
		PostArticleEndpoint:    httptransport.NewClient("POST", tgt, encodePostArticleRequest, decodePostArticleResponse, options...).Endpoint(),
		GetArticleEndpoint:     httptransport.NewClient("GET", tgt, encodeGetArticleRequest, decodeGetArticleResponse, options...).Endpoint(),
		GetArticleListEndpoint: httptransport.NewClient("GET", tgt, encodeGetArticleListRequest, decodeGetArticleResponse, options...).Endpoint(),
		PutArticleEndpoint:     httptransport.NewClient("PUT", tgt, encodePutArticleRequest, decodePutArticleResponse, options...).Endpoint(),
		PatchArticleEndpoint:   httptransport.NewClient("PATCH", tgt, encodePatchArticleRequest, decodePatchArticleResponse, options...).Endpoint(),
		DeleteArticleEndpoint:  httptransport.NewClient("DELETE", tgt, encodeDeleteArticleRequest, decodeDeleteArticleResponse, options...).Endpoint(),
	}, nil
}

// PostArticle implements Service. Primarily useful in a client.
func (e Endpoints) PostArticle(ctx context.Context, a Article) error {
	request := postArticleRequest{Article: a}
	response, err := e.PostArticleEndpoint(ctx, request)
	if err != nil {
		return err
	}
	resp := response.(postArticleResponse)
	return resp.Err
}

// GetArticleList implements Service. Primarily useful in a client.
func (e Endpoints) GetArticleList(ctx context.Context, id string) (PaginatedArticles, error) {
	request := getArticleListRequest{}
	response, err := e.GetArticleListEndpoint(ctx, request)
	if err != nil {
		return PaginatedArticles{}, err
	}
	resp := response.(getArticleListResponse)
	return resp.Data, resp.Err
}

// GetArticle implements Service. Primarily useful in a client.
func (e Endpoints) GetArticle(ctx context.Context, id string) (Article, error) {
	request := getArticleRequest{ID: id}
	response, err := e.GetArticleEndpoint(ctx, request)
	if err != nil {
		return Article{}, err
	}
	resp := response.(getArticleResponse)
	return resp.Article, resp.Err
}

// PutArticle implements Service. Primarily useful in a client.
func (e Endpoints) PutArticle(ctx context.Context, id string, p Article) error {
	request := putArticleRequest{ID: id, Article: p}
	response, err := e.PutArticleEndpoint(ctx, request)
	if err != nil {
		return err
	}
	resp := response.(putArticleResponse)
	return resp.Err
}

// PatchArticle implements Service. Primarily useful in a client.
func (e Endpoints) PatchArticle(ctx context.Context, id string, p Article) error {
	request := patchArticleRequest{ID: id, Article: p}
	response, err := e.PatchArticleEndpoint(ctx, request)
	if err != nil {
		return err
	}
	resp := response.(patchArticleResponse)
	return resp.Err
}

// DeleteArticle implements Service. Primarily useful in a client.
func (e Endpoints) DeleteArticle(ctx context.Context, id string) error {
	request := deleteArticleRequest{ID: id}
	response, err := e.DeleteArticleEndpoint(ctx, request)
	if err != nil {
		return err
	}
	resp := response.(deleteArticleResponse)
	return resp.Err
}

// MakePostArticleEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePostArticleEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(postArticleRequest)
		_, e := s.PostArticle(ctx, req.Article)
		return postArticleResponse{Err: e}, nil
	}
}

func MakeGetArticleListEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		p, e := s.GetArticleList(ctx, &PaginationParams{1, 10})
		return getArticleListResponse{Data: p, Err: e}, nil
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
		e := s.PutArticle(ctx, req.ID, req.Article)
		return putArticleResponse{Err: e}, nil
	}
}

// MakePatchArticleEndpoint returns an endpoint via the passed service.
// Primarily useful in a server.
func MakePatchArticleEndpoint(s Service) endpoint.Endpoint {
	return func(ctx context.Context, request interface{}) (response interface{}, err error) {
		req := request.(patchArticleRequest)
		e := s.PatchArticle(ctx, req.ID, req.Article)
		return patchArticleResponse{Err: e}, nil
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
	Article Article
}

type postArticleResponse struct {
	Err error `json:"err,omitempty"`
}

func (r postArticleResponse) error() error { return r.Err }

type getArticleListRequest struct{}

type getArticleListResponse struct {
	Data PaginatedArticles `json:"data,omitempty"`
	Err  error             `json:"err,omitempty"`
}

type getArticleRequest struct {
	ID string
}

func (r getArticleListResponse) error() error { return r.Err }

type getArticleResponse struct {
	Article Article `json:"article,omitempty"`
	Err     error   `json:"err,omitempty"`
}

func (r getArticleResponse) error() error { return r.Err }

type putArticleRequest struct {
	ID      string
	Article Article
}

type putArticleResponse struct {
	Err error `json:"err,omitempty"`
}

func (r putArticleResponse) error() error { return nil }

type patchArticleRequest struct {
	ID      string
	Article Article
}

type patchArticleResponse struct {
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
