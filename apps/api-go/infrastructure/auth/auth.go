package auth

import (
	"context"
	"github.com/golang-jwt/jwt/v4"
	grpcauth "github.com/grpc-ecosystem/go-grpc-middleware/auth"
	grpcctxtags "github.com/grpc-ecosystem/go-grpc-middleware/tags"
	"github.com/grpc-ecosystem/go-grpc-middleware/util/metautils"
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/status"
)

type InvalidTokenError struct{}

func (ite *InvalidTokenError) Error() string {
	return "invalid token"
}

func (c Claims) IsAdmin() bool {
	r := c.RealmAccess.Roles
	for _, a := range r {
		if a == "admin" {
			return true
		}
	}
	return false
}

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
	token, _, err := parser.ParseUnverified(tokenString, &jwt.RegisteredClaims{})
	if err != nil {
		return Claims{}, err
	}

	claims, _ := token.Claims.(Claims)

	return claims, nil
}

// GrpcInterceptor is used to inject user into context
func GrpcInterceptor(ctx context.Context) (context.Context, error) {
	val := metautils.ExtractIncoming(ctx).Get("authorization")
	// do not force login if no bearer included
	if val == "" {
		return ctx, nil
	}

	token, err := grpcauth.AuthFromMD(ctx, "bearer")

	if err != nil {
		return ctx, err
	}

	parsedToken, err := parseToken(token)
	if err != nil {
		return ctx, status.Errorf(codes.Unauthenticated, "invalid auth token: %v", err)
	}

	grpcctxtags.Extract(ctx).Set("user", parsedToken)

	newCtx := context.WithValue(ctx, UserClaimsKey, parsedToken)

	return newCtx, nil
}
