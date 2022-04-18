package auth

import (
	"context"

	jwtgo "github.com/dgrijalva/jwt-go"
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/metadata"
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

// type UserClaimsKeyType string

// const UserClaimsKey UserClaimsKeyType = "user"

// func GetUserClaims(ctx context.Context) *Claims {
// 	claims, ok := ctx.Value("user").(*Claims)

// 	if !ok {
// 		return nil
// 	}

// 	return claims
// }

func extractHeader(ctx context.Context, header string) (string, error) {
	md, ok := metadata.FromIncomingContext(ctx)
	if !ok {
		return "", status.Error(codes.Unauthenticated, "no headers in request")
	}

	authHeaders, ok := md[header]
	if !ok {
		return "", status.Error(codes.Unauthenticated, "no header in request")
	}

	if len(authHeaders) != 1 {
		return "", status.Error(codes.Unauthenticated, "more than 1 header in request")
	}

	return authHeaders[0], nil
}

func GetUserClaims(ctx context.Context) (*Claims, error) {
	h, err := extractHeader(ctx, "authorization")
	if err != nil {
		return nil, err
	}
	return ParseHeader(h)
}

func ParseHeader(bearer string) (*Claims, error) {
	return ParseToken(bearer[7:])
}

func ParseToken(tokenString string) (*Claims, error) {
	parser := new(jwtgo.Parser)
	token, _, err := parser.ParseUnverified(tokenString, &Claims{})
	if err != nil {
		return nil, err
	}

	claims := token.Claims.(*Claims)

	return claims, nil
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
