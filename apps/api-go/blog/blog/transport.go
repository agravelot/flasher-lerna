package blog

// The blogsvc is just over HTTP, so we just have a single transport.go.

import (
	"bytes"
	"context"
	"encoding/json"
	"errors"
	"io/ioutil"
	"net/http"
	"net/url"

	"github.com/gorilla/mux"

	"github.com/go-kit/kit/log"
	"github.com/go-kit/kit/transport"
	httptransport "github.com/go-kit/kit/transport/http"
)

var (
	// ErrBadRouting is returned when an expected path variable is missing.
	// It always indicates programmer error.
	ErrBadRouting = errors.New("inconsistent mapping between route and handler (programmer error)")
)

// MakeHTTPHandler mounts all of the service endpoints into an http.Handler.
// Useful in a blogsvc server.
func MakeHTTPHandler(s Service, logger log.Logger) http.Handler {
	r := mux.NewRouter()
	e := MakeServerEndpoints(s)
	options := []httptransport.ServerOption{
		httptransport.ServerErrorHandler(transport.NewLogErrorHandler(logger)),
		httptransport.ServerErrorEncoder(encodeError),
	}

	// POST    /blogs/                          adds another blog
	// GET     /blogs/:id                       retrieves the given blog by id
	// PUT     /blogs/:id                       post updated blog information about the blog
	// PATCH   /blogs/:id                       partial updated blog information
	// DELETE  /blogs/:id                       remove the given blog
	// GET     /blogs/:id/addresses/            retrieve addresses associated with the blog
	// GET     /blogs/:id/addresses/:addressID  retrieve a particular blog address
	// POST    /blogs/:id/addresses/            add a new address
	// DELETE  /blogs/:id/addresses/:addressID  remove an address

	r.Methods("POST").Path("/blogs").Handler(httptransport.NewServer(
		e.PostBlogEndpoint,
		decodePostBlogRequest,
		encodeResponse,
		options...,
	))
	r.Methods("GET").Path("/blogs").Handler(httptransport.NewServer(
		e.GetBlogListEndpoint,
		decodeGetBlogListRequest,
		encodeResponse,
		options...,
	))
	r.Methods("GET").Path("/blogs/{id}").Handler(httptransport.NewServer(
		e.GetBlogEndpoint,
		decodeGetBlogRequest,
		encodeResponse,
		options...,
	))
	r.Methods("PUT").Path("/blogs/{id}").Handler(httptransport.NewServer(
		e.PutBlogEndpoint,
		decodePutBlogRequest,
		encodeResponse,
		options...,
	))
	r.Methods("PATCH").Path("/blogs/{id}").Handler(httptransport.NewServer(
		e.PatchBlogEndpoint,
		decodePatchBlogRequest,
		encodeResponse,
		options...,
	))
	r.Methods("DELETE").Path("/blogs/{id}").Handler(httptransport.NewServer(
		e.DeleteBlogEndpoint,
		decodeDeleteBlogRequest,
		encodeResponse,
		options...,
	))

	return r
}

func decodePostBlogRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	var req postBlogRequest
	if e := json.NewDecoder(r.Body).Decode(&req.Blog); e != nil {
		return nil, e
	}
	return req, nil
}

func decodeGetBlogListRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	return getBlogListRequest{}, nil
}

func decodeGetBlogRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	return getBlogRequest{ID: id}, nil
}

func decodePutBlogRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	var blog Blog
	if err := json.NewDecoder(r.Body).Decode(&blog); err != nil {
		return nil, err
	}
	return putBlogRequest{
		ID:   id,
		Blog: blog,
	}, nil
}

func decodePatchBlogRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	var blog Blog
	if err := json.NewDecoder(r.Body).Decode(&blog); err != nil {
		return nil, err
	}
	return patchBlogRequest{
		ID:   id,
		Blog: blog,
	}, nil
}

func decodeDeleteBlogRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	return deleteBlogRequest{ID: id}, nil
}

func encodePostBlogRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("POST").Path("/blogs/")
	req.URL.Path = "/blogs/"
	return encodeRequest(ctx, req, request)
}

func encodeGetBlogListRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("GET").Path("/blogs")
	req.URL.Path = "/blogs"
	return encodeRequest(ctx, req, request)
}

func encodeGetBlogRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("GET").Path("/blogs/{id}")
	r := request.(getBlogRequest)
	blogID := url.QueryEscape(r.ID)
	req.URL.Path = "/blogs/" + blogID
	return encodeRequest(ctx, req, request)
}

func encodePutBlogRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("PUT").Path("/blogs/{id}")
	r := request.(putBlogRequest)
	blogID := url.QueryEscape(r.ID)
	req.URL.Path = "/blogs/" + blogID
	return encodeRequest(ctx, req, request)
}

func encodePatchBlogRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("PATCH").Path("/blogs/{id}")
	r := request.(patchBlogRequest)
	blogID := url.QueryEscape(r.ID)
	req.URL.Path = "/blogs/" + blogID
	return encodeRequest(ctx, req, request)
}

func encodeDeleteBlogRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("DELETE").Path("/blogs/{id}")
	r := request.(deleteBlogRequest)
	blogID := url.QueryEscape(r.ID)
	req.URL.Path = "/blogs/" + blogID
	return encodeRequest(ctx, req, request)
}

func decodePostBlogResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response postBlogResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodeGetBlogResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response getBlogResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodePutBlogResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response putBlogResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodePatchBlogResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response patchBlogResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodeDeleteBlogResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response deleteBlogResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

// errorer is implemented by all concrete response types that may contain
// errors. It allows us to change the HTTP response code without needing to
// trigger an endpoint (transport-level) error. For more information, read the
// big comment in endpoints.go.
type errorer interface {
	error() error
}

// encodeResponse is the common method to encode all response types to the
// client. I chose to do it this way because, since we're using JSON, there's no
// reason to provide anything more specific. It's certainly possible to
// specialize on a per-response (per-method) basis.
func encodeResponse(ctx context.Context, w http.ResponseWriter, response interface{}) error {
	if e, ok := response.(errorer); ok && e.error() != nil {
		// Not a Go kit transport error, but a business-logic error.
		// Provide those as HTTP errors.
		encodeError(ctx, e.error(), w)
		return nil
	}
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	return json.NewEncoder(w).Encode(response)
}

// encodeRequest likewise JSON-encodes the request to the HTTP request body.
// Don't use it directly as a transport/http.Client EncodeRequestFunc:
// blogsvc endpoints require mutating the HTTP method and request path.
func encodeRequest(_ context.Context, req *http.Request, request interface{}) error {
	var buf bytes.Buffer
	err := json.NewEncoder(&buf).Encode(request)
	if err != nil {
		return err
	}
	req.Body = ioutil.NopCloser(&buf)
	return nil
}

func encodeError(_ context.Context, err error, w http.ResponseWriter) {
	if err == nil {
		panic("encodeError with nil error")
	}
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	w.WriteHeader(codeFrom(err))
	json.NewEncoder(w).Encode(map[string]interface{}{
		"error": err.Error(),
	})
}

func codeFrom(err error) int {
	switch err {
	case ErrNotFound:
		return http.StatusNotFound
	case ErrAlreadyExists, ErrInconsistentIDs:
		return http.StatusBadRequest
	default:
		return http.StatusInternalServerError
	}
}
