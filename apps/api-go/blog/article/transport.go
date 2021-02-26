package article

// The articlesvc is just over HTTP, so we just have a single transport.go.

import (
	"bytes"
	"context"
	"encoding/json"
	"errors"
	"fmt"
	"io/ioutil"
	"net/http"
	"net/url"

	"github.com/gorilla/mux"

	jwtgo "github.com/dgrijalva/jwt-go"
	"github.com/go-kit/kit/auth/jwt"
	"github.com/go-kit/kit/log"
	"github.com/go-kit/kit/transport"
	httptransport "github.com/go-kit/kit/transport/http"
)

var (
	// ErrBadRouting is returned when an expected path variable is missing.
	// It always indicates programmer error.
	ErrBadRouting = errors.New("inconsistent mapping between route and handler (programmer error)")
)

type Claims struct {
	Exp               int            `json:"exp"`
	Iat               int            `json:"iat"`
	AuthTime          int            `json:"auth_time"`
	Jti               string         `json:"jti"`
	Iss               string         `json:"iss"`
	Aud               string         `json:"aud"`
	Sub               string         `json:"sub"`
	Typ               string         `json:"typ"`
	Azp               string         `json:"azp"`
	Nonce             string         `json:"nonce"`
	SessionState      string         `json:"session_state"`
	Acr               string         `json:"acr"`
	AllowedOrigins    []string       `json:"allowed-origins"`
	RealmAccess       RealmAccess    `json:"realm_access"`
	ResourceAccess    ResourceAccess `json:"resource_access"`
	Scope             string         `json:"scope"`
	EmailVerified     bool           `json:"email_verified"`
	PreferredUsername string         `json:"preferred_username"`
	Email             string         `json:"email"`
}
type RealmAccess struct {
	Roles []string `json:"roles"`
}
type Account struct {
	Roles []string `json:"roles"`
}
type ResourceAccess struct {
	Account Account `json:"account"`
}

func (m Claims) Valid() error {
	return nil
}

type UserClaimsKeyType string

const UserClaimsKey UserClaimsKeyType = "user"

func GetUserClaims(ctx context.Context) *Claims {
	// claims, ok := ctx.Value("user").(*Claims)
	fmt.Printf("get user claims  : %+v\n", ctx.Value("user"))

	// TODO Failed to cast to Claims in test, but working in live
	claims, ok := ctx.Value("user").(*Claims)

	if !ok {
		println("unable to get claims")
		return nil
	}

	return claims
}

// ClaimsToContext Inject user claims into context
func ClaimsToContext() httptransport.RequestFunc {
	return func(ctx context.Context, r *http.Request) context.Context {

		tokenString, ok := ctx.Value(jwt.JWTTokenContextKey).(string)

		// Unable to find token in current context, do not inject user
		if ok == false {
			println("token not included, skipping")
			return ctx
		}

		parser := new(jwtgo.Parser)
		// token, p, err := parser.ParseUnverified(tokenString, jwtgo.MapClaims{})
		token, _, err := parser.ParseUnverified(tokenString, &Claims{})

		if err != nil {
			panic("Unable to parse token")
		}

		claims := token.Claims.(*Claims)
		// fmt.Printf("%+v\n", claims)

		println(UserClaimsKey)

		return context.WithValue(ctx, UserClaimsKey, claims)
	}
}

// MakeHTTPHandler mounts all of the service endpoints into an http.Handler.
// Useful in a articlesvc server.
func MakeHTTPHandler(s Service, logger log.Logger) http.Handler {
	r := mux.NewRouter()
	e := MakeServerEndpoints(s)
	options := []httptransport.ServerOption{
		httptransport.ServerErrorHandler(transport.NewLogErrorHandler(logger)),
		httptransport.ServerErrorEncoder(encodeError),
		httptransport.ServerBefore(jwt.HTTPToContext()), // Inject jwt into context
		httptransport.ServerBefore(ClaimsToContext()),
	}

	// GET     /articles                           list all articles
	// POST    /articles                           adds another article
	// GET     /articles/:id                       retrieves the given article by id
	// PUT     /articles/:id                       post updated article information about the article
	// PATCH   /articles/:id                       partial updated article information
	// DELETE  /articles/:id                       remove the given article

	r.Methods("GET").Path("/articles").Handler(httptransport.NewServer(
		e.GetArticleListEndpoint,
		decodeGetArticleListRequest,
		encodeResponse,
		options...,
	))
	r.Methods("GET").Path("/articles/{id}").Handler(httptransport.NewServer(
		e.GetArticleEndpoint,
		decodeGetArticleRequest,
		encodeResponse,
		options...,
	))
	r.Methods("POST").Path("/articles").Handler(httptransport.NewServer(
		e.PostArticleEndpoint,
		decodePostArticleRequest,
		encodeResponse,
		options...,
	))
	r.Methods("PUT").Path("/articles/{id}").Handler(httptransport.NewServer(
		e.PutArticleEndpoint,
		decodePutArticleRequest,
		encodeResponse,
		options...,
	))
	r.Methods("PATCH").Path("/articles/{id}").Handler(httptransport.NewServer(
		e.PatchArticleEndpoint,
		decodePatchArticleRequest,
		encodeResponse,
		options...,
	))
	r.Methods("DELETE").Path("/articles/{id}").Handler(httptransport.NewServer(
		e.DeleteArticleEndpoint,
		decodeDeleteArticleRequest,
		encodeResponse,
		options...,
	))

	return r
}

func decodePostArticleRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	var req postArticleRequest
	if e := json.NewDecoder(r.Body).Decode(&req.Article); e != nil {
		return nil, e
	}
	return req, nil
}

func decodeGetArticleListRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	return getArticleListRequest{}, nil
}

func decodeGetArticleRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	return getArticleRequest{ID: id}, nil
}

func decodePutArticleRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	var article Article
	if err := json.NewDecoder(r.Body).Decode(&article); err != nil {
		return nil, err
	}
	return putArticleRequest{
		ID:      id,
		Article: article,
	}, nil
}

func decodePatchArticleRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	var article Article
	if err := json.NewDecoder(r.Body).Decode(&article); err != nil {
		return nil, err
	}
	return patchArticleRequest{
		ID:      id,
		Article: article,
	}, nil
}

func decodeDeleteArticleRequest(_ context.Context, r *http.Request) (request interface{}, err error) {
	vars := mux.Vars(r)
	id, ok := vars["id"]
	if !ok {
		return nil, ErrBadRouting
	}
	return deleteArticleRequest{ID: id}, nil
}

func encodePostArticleRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("POST").Path("/articles/")
	req.URL.Path = "/articles"
	return encodeRequest(ctx, req, request)
}

func encodeGetArticleListRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("GET").Path("/articles")
	req.URL.Path = "/articles"
	return encodeRequest(ctx, req, request)
}

func encodeGetArticleRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("GET").Path("/articles/{id}")
	r := request.(getArticleRequest)
	articleID := url.QueryEscape(r.ID)
	req.URL.Path = "/articles/" + articleID
	return encodeRequest(ctx, req, request)
}

func encodePutArticleRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("PUT").Path("/articles/{id}")
	r := request.(putArticleRequest)
	articleID := url.QueryEscape(r.ID)
	req.URL.Path = "/articles/" + articleID
	return encodeRequest(ctx, req, request)
}

func encodePatchArticleRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("PATCH").Path("/articles/{id}")
	r := request.(patchArticleRequest)
	articleID := url.QueryEscape(r.ID)
	req.URL.Path = "/articles/" + articleID
	return encodeRequest(ctx, req, request)
}

func encodeDeleteArticleRequest(ctx context.Context, req *http.Request, request interface{}) error {
	// r.Methods("DELETE").Path("/articles/{id}")
	r := request.(deleteArticleRequest)
	articleID := url.QueryEscape(r.ID)
	req.URL.Path = "/articles/" + articleID
	return encodeRequest(ctx, req, request)
}

func decodePostArticleResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response postArticleResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodeGetArticleResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response getArticleResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodePutArticleResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response putArticleResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodePatchArticleResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response patchArticleResponse
	err := json.NewDecoder(resp.Body).Decode(&response)
	return response, err
}

func decodeDeleteArticleResponse(_ context.Context, resp *http.Response) (interface{}, error) {
	var response deleteArticleResponse
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
// articlesvc endpoints require mutating the HTTP method and request path.
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
	case ErrNoAuth:
		return http.StatusUnauthorized
	case ErrNotFound:
		return http.StatusNotFound
	case ErrAlreadyExists:
		return http.StatusBadRequest
	default:
		return http.StatusInternalServerError
	}
}
