package auth

import (
	"context"
	"github.com/golang-jwt/jwt/v4"
	grpc_auth "github.com/grpc-ecosystem/go-grpc-middleware/auth"
	grpc_ctxtags "github.com/grpc-ecosystem/go-grpc-middleware/tags"
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/status"
)

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
type RealmAccess struct {
	Roles []string `json:"roles"`
}
type Account struct {
	Roles []string `json:"roles"`
}
type ResourceAccess struct {
	Account Account `json:"account"`
}

func (c Claims) Valid() error {
	return nil
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
	// no need to check jwt signature since it's check by api gateway.
	token, _, err := parser.ParseUnverified(tokenString, Claims{})
	if err != nil {
		return Claims{}, err
	}

	claims := token.Claims.(Claims)

	return claims, nil
}

// Interceptor is used by a middleware to authenticate requests
func Interceptor(ctx context.Context) (context.Context, error) {
	token, err := grpc_auth.AuthFromMD(ctx, "bearer")
	if err != nil {
		if status.Code(err) == codes.Unauthenticated {
			return ctx, nil
		}
		return ctx, err
	}

	parsedToken, err := parseToken(token)
	if err != nil {
		return ctx, status.Errorf(codes.Unauthenticated, "invalid auth token: %v", err)
	}

	grpc_ctxtags.Extract(ctx).Set("user", parsedToken)

	newCtx := context.WithValue(ctx, UserClaimsKey, parsedToken)

	return newCtx, nil
}

// ClaimsToContext Inject user claims into context
// func ClaimsToContext() httptransport.RequestFunc {
// 	return func(ctx context.Context, r *http.Request) context.Context {

// 		tokenString, ok := ctx.Value(jwt.JWTTokenContextKey).(string)

// 		// Unable to find token in current context, do not inject user
// 		if ok == false {
// 			return ctx
// 		}

// 		parser := new(jwtgo.Parser)
// 		token, _, err := parser.ParseUnverified(tokenString, &Claims{})
// 		if err != nil {
// 			panic("Unable to parse token")
// 		}

// 		claims := token.Claims.(*Claims)

// 		return context.WithValue(ctx, "user", claims)
// 	}
// }
