package auth

import (
	"context"
	"errors"
	"github.com/golang-jwt/jwt/v4"
	grpcAuth "github.com/grpc-ecosystem/go-grpc-middleware/auth"
	grpcCtxTags "github.com/grpc-ecosystem/go-grpc-middleware/tags"
	"github.com/grpc-ecosystem/go-grpc-middleware/util/metautils"
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/status"
)

const adminRole = "admin"

type InvalidTokenError struct{}

func (ite *InvalidTokenError) Error() string {
	return "invalid token"
}

func (c Claims) IsAdmin() bool {
	for _, r := range c.RealmAccess.Roles {
		if r == adminRole {
			return true
		}
	}
	return false
}

type Claims struct {
	AuthTime          int            `json:"auth_time"`
	Typ               string         `json:"typ"`
	Azp               string         `json:"azp"`
	Nonce             string         `json:"nonce"`
	SessionState      string         `json:"session_state"` //nolint:tagliatelle
	Acr               string         `json:"acr"`
	AllowedOrigins    []string       `json:"allowed-origins"` //nolint:tagliatelle
	RealmAccess       RealmAccess    `json:"realm_access"`    //nolint:tagliatelle
	ResourceAccess    ResourceAccess `json:"resource_access"`
	Scope             string         `json:"scope"`
	EmailVerified     bool           `json:"email_verified"`
	PreferredUsername string         `json:"preferred_username"`
	Email             string         `json:"email"`
	jwt.RegisteredClaims
}

func (c Claims) Valid() error {
	return nil
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

type UserClaimsKeyType string

const UserClaimsKey UserClaimsKeyType = "user"

// GetUser Return authentication status and user from context.
func GetUser(ctx context.Context) (bool, Claims) {
	claims, ok := ctx.Value(UserClaimsKey).(Claims)

	return ok, claims
}

func parseToken(tokenString string) (Claims, error) {
	parser := new(jwt.Parser)

	// no need to check jwt signature since it's already check by api gateway.
	token, _, err := parser.ParseUnverified(tokenString, &Claims{})
	if err != nil {
		return Claims{}, err
	}

	// Cast token
	// https://pkg.go.dev/github.com/golang-jwt/jwt/v4#example-ParseWithClaims-CustomClaimsType
	claims, ok := token.Claims.(*Claims)
	if !ok {
		return Claims{}, errors.New("unable to cast claim")
	}

	return *claims, nil
}

// GrpcInterceptor is used to inject user into context
func GrpcInterceptor(ctx context.Context) (context.Context, error) {
	val := metautils.ExtractIncoming(ctx).Get("authorization")
	// do not force login if no bearer included
	if val == "" {
		return ctx, nil
	}

	token, err := grpcAuth.AuthFromMD(ctx, "bearer")

	if err != nil {
		return ctx, err
	}

	parsedToken, err := parseToken(token)
	if err != nil {
		return ctx, status.Errorf(codes.Unauthenticated, "invalid auth token: %v", err)
	}

	grpcCtxTags.Extract(ctx).Set("user", parsedToken)

	newCtx := context.WithValue(ctx, UserClaimsKey, parsedToken)

	return newCtx, nil
}
