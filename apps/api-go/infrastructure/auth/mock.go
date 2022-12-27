package auth

import (
	"context"
	"github.com/golang-jwt/jwt/v4"
)

var UserSsoID = "30151ae5-28b4-4c6c-b0ae-ea2e6a49ef67"

// TODO Move it outside of "auth" module
var UserClaim = Claims{
	RegisteredClaims: jwt.RegisteredClaims{
		ExpiresAt: nil,
		Audience:  nil,
		ID:        "e18e1ce8-9979-476d-9f10-f9999ba040f8",
		IssuedAt:  nil,
		Issuer:    "https://accounts.example.com/auth/realms/test",
		Subject:   UserSsoID,
		NotBefore: nil,
	},
	AuthTime:       1611164364,
	Typ:            "Bearer",
	Azp:            "frontend",
	Nonce:          "1929a0da-156e-45ff-833f-a560b06b5acd",
	SessionState:   "4e1f19f3-1a2c-4e15-b1aa-3ecfa1910db8",
	Acr:            "0",
	AllowedOrigins: []string{},
	RealmAccess: RealmAccess{
		Roles: []string{},
	},
	ResourceAccess: ResourceAccess{
		Account: Account{
			Roles: []string{},
		},
	},
	Scope:             "openid profile email",
	EmailVerified:     true,
	PreferredUsername: "test",
	Email:             "test@test.com",
}

// TODO Move it outside of "auth" module
func AuthAsUser(ctx context.Context) (context.Context, Claims) {
	return context.WithValue(ctx, UserClaimsKey, UserClaim), UserClaim
}

var AdminSsoID = "30151ae5-28b4-4c6c-b0ae-ea2e6a49ef67"

// TODO Move it outside of "auth" module
var AdminClaim = Claims{
	RegisteredClaims: jwt.RegisteredClaims{
		ExpiresAt: nil,
		Audience:  nil,
		ID:        "e18e1ce8-9979-476d-9f10-f9999ba040f8",
		IssuedAt:  nil,
		Issuer:    "https://accounts.example.com/auth/realms/test",
		Subject:   AdminSsoID,
		NotBefore: nil,
	},
	AuthTime:       1611164364,
	Typ:            "Bearer",
	Azp:            "frontend",
	Nonce:          "1929a0da-156e-45ff-833f-a560b06b5acd",
	SessionState:   "4e1f19f3-1a2c-4e15-b1aa-3ecfa1910db8",
	Acr:            "0",
	AllowedOrigins: []string{},
	RealmAccess: RealmAccess{
		Roles: []string{"admin"},
	},
	ResourceAccess: ResourceAccess{
		Account: Account{
			Roles: []string{},
		},
	},
	Scope:             "openid profile email",
	EmailVerified:     true,
	PreferredUsername: "test",
	Email:             "test@test.com",
}

// TODO Move it outside of "auth" module
func AuthAsAdmin(ctx context.Context) (context.Context, Claims) {
	return context.WithValue(ctx, UserClaimsKey, AdminClaim), AdminClaim
}
