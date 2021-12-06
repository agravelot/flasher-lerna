package album

// The albumsvc is just over HTTP, so we just have a single transport.go.

import (
	"api-go/auth"
	"bytes"
	"context"
	"encoding/json"
	"errors"
	"io/ioutil"
	"net/http"
	"net/url"
	"strconv"
	"strings"

	"github.com/gorilla/mux"

	"github.com/go-kit/kit/auth/jwt"
	"github.com/go-kit/kit/log"
	"github.com/go-kit/kit/transport"
	httptransport "github.com/go-kit/kit/transport/http"
)

var (
	// ErrBadRouting is returned when an expected path variable is missing.
	// It always indicates programmer error.
	ErrBadRouting = errors.New("inconsistent mapping between route and handler (programmer error)")
	ErrBadRequest = errors.New("unable to handle request")
)

// MakeHTTPHandler mounts all of the service endpoints into an http.Handler.
// Useful in a albumsvc server.
func MakeHTTPHandler(s Service, logger log.Logger) http.Handler {
	r := mux.NewRouter()
	e := MakeServerEndpoints(s)
	options := []httptransport.ServerOption{
		httptransport.ServerErrorHandler(transport.NewLogErrorHandler(logger)),
		httptransport.ServerErrorEncoder(encodeError),
		httptransport.ServerBefore(jwt.HTTPToContext()),
		httptransport.ServerBefore(auth.ClaimsToContext()),
	}

	// GET     /albums                           list all albums
	// POST    /albums                           adds another album
	// GET     /albums/:id                       retrieves the given album by id
	// PUT     /albums/:id                       post updated album information about the album
	// PATCH   /albums/:id                       partial updated album information
	// DELETE  /albums/:id                       remove the given album

	r.Methods("GET").Path("/albums").Handler(httptransport.NewServer(
		e.GetAlbumListEndpoint,
		decodeGetAlbumListRequest,
		encodeResponse,
		options...,
	))
	r.Methods("GET").Path("/albums/{id}").Handler(httptransport.NewServer(
		e.GetAlbumEndpoint,
		decodeGetAlbumRequest,
		encodeResponse,
		options...,
	))
	r.Methods("POST").Path("/albums").Handler(httptransport.NewServer(
		e.PostAlbumEndpoint,
		decodePostAlbumRequest,
		encodeResponse,
		options...,
	))
	r.Methods("PUT").Path("/albums/{id}").Handler(httptransport.NewServer(
		e.PutAlbumEndpoint,
		decodePutAlbumRequest,
		encodeResponse,
		options...,
	))
	r.Methods("PATCH").Path("/albums/{id}").Handler(httptransport.NewServer(
		e.PatchAlbumEndpoint,
		decodePatchAlbumRequest,
		encodeResponse,
		options...,
	))
	r.Methods("DELETE").Path("/albums/{id}").Handler(httptransport.NewServer(
		e.DeleteAlbumEndpoint,
		decodeDeleteAlbumRequest,
		encodeResponse,
		options...,
	))

	return r
}

func decodePostAlbumRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	var req postAlbumRequest
	if e := json.NewDecoder(r.Body).Decode(&req.AlbumRequest); e != nil {
		return nil, e
	}
	return req, nil
}

func decodeGetAlbumListRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	var next uint
	limit := 10

	nextString := r.URL.Query().Get("next")
	if nextString != "" {
		v, err := strconv.ParseUint(nextString, 10, 32)
		if err != nil {
			return nil, ErrBadRequest
		}
		next = uint(v)
	}

	limitString := r.URL.Query().Get("limit")
	if limitString != "" {
		limit, err = strconv.Atoi(limitString)
		if err != nil {
			return nil, ErrBadRequest
		}
	}

	joins := AlbumListJoinsParams{}
	joinsString := r.URL.Query().Get("join")
	if joinsString != "" {
		for _, j := range strings.Split(joinsString, ",") {
			switch j {
			case "medias":
				joins.Medias = true
			case "categories":
				joins.Categories = true
			}
		}
	}

	return getAlbumListRequest{Joins: joins, PaginationParams: PaginationParams{Next: next, Limit: limit}}, nil
}

func decodeGetAlbumRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	return getAlbumRequest{ID: id}, nil
}

func decodePutAlbumRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	var album AlbumRequest
	if err := json.NewDecoder(r.Body).Decode(&album); err != nil {
		return nil, err
	}
	return putAlbumRequest{
		ID:           id,
		AlbumRequest: album,
	}, nil
}

func decodePatchAlbumRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	slug, ok := vars["slug"]
	if !ok {
		return nil, ErrBadRouting
	}
	var album AlbumRequest
	if err := json.NewDecoder(r.Body).Decode(&album); err != nil {
		return nil, err
	}
	return patchAlbumRequest{
		Slug:         slug,
		AlbumRequest: album,
	}, nil
}

func decodeDeleteAlbumRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	return deleteAlbumRequest{ID: id}, nil
}

func encodePostAlbumRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("POST").Path("/albums/")
	req.URL.Path = "/albums"
	return encodeRequest(ctx, req, request)
}

func encodeGetAlbumListRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("GET").Path("/albums")
	req.URL.Path = "/albums"
	return encodeRequest(ctx, req, request)
}

func encodeGetAlbumRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("GET").Path("/albums/{id}")
	r := request.(getAlbumRequest)
	albumID := url.QueryEscape(r.ID)
	req.URL.Path = "/albums/" + albumID
	return encodeRequest(ctx, req, request)
}

func encodePutAlbumRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("PUT").Path("/albums/{id}")
	r := request.(putAlbumRequest)
	albumID := url.QueryEscape(r.ID)
	req.URL.Path = "/albums/" + albumID
	return encodeRequest(ctx, req, request)
}

func encodePatchAlbumRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("PATCH").Path("/albums/{id}")
	r := request.(patchAlbumRequest)
	albumSlug := url.QueryEscape(r.Slug)
	req.URL.Path = "/albums/" + albumSlug
	return encodeRequest(ctx, req, request)
}

func encodeDeleteAlbumRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("DELETE").Path("/albums/{id}")
	r := request.(deleteAlbumRequest)
	albumID := url.QueryEscape(r.ID)
	req.URL.Path = "/albums/" + albumID
	return encodeRequest(ctx, req, request)
}

func decodePostAlbumResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response postAlbumResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodeGetAlbumResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response getAlbumResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodePutAlbumResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response putAlbumResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodePatchAlbumResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response patchAlbumResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodeDeleteAlbumResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response deleteAlbumResponse
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
// albumsvc endpoints require mutating the HTTP method and request path.
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
	case ErrAlreadyExists:
		return http.StatusForbidden
	case ErrNoAuth:
		return http.StatusUnauthorized
	case ErrNotAdmin:
		return http.StatusForbidden
	case ErrNotFound:
		return http.StatusNotFound
	case ErrAlreadyExists:
		return http.StatusBadRequest
	default:
		return http.StatusInternalServerError
	}
}
