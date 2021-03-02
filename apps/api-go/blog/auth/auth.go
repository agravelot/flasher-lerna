package auth

import (
	"context"
	"fmt"
	"net/http"

	jwtgo "github.com/dgrijalva/jwt-go"
	"github.com/go-kit/kit/auth/jwt"
	httptransport "github.com/go-kit/kit/transport/http"
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

func GetUserClaims(ctx context.Context) *Claims {
	claims, ok := ctx.Value("user").(*Claims)

	fmt.Printf("GetUserClaims %+v\n", ctx.Value("user"))
	fmt.Printf("ok %+v\n", ok)

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
		token, _, err := parser.ParseUnverified(tokenString, &Claims{})
		if err != nil {
			panic("Unable to parse token")
		}

		claims := token.Claims.(*Claims)

		return context.WithValue(ctx, "user", claims)
	}
}
