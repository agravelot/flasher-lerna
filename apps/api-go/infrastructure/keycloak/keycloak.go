package keycloak

import (
	"context"
	"fmt"
	"golang.org/x/oauth2"
	"golang.org/x/oauth2/clientcredentials"
	"net/http"
)

// Keycloak contain keycloak settings
type Keycloak struct {
	baseURL      string
	realm        string
	clientID     string
	clientSecret string
	conf         clientcredentials.Config
}

// New Return a keycloak instance
func New(baseURL, realm, clientID, clientSecret string) (Keycloak, error) {
	conf := clientcredentials.Config{
		ClientID:     clientID,
		ClientSecret: clientSecret,
		TokenURL:     fmt.Sprintf("%s/auth/realms/%s/protocol/openid-connect/token", baseURL, realm),
		Scopes:       []string{"openid", "profile", "email"},
		AuthStyle:    oauth2.AuthStyleInParams,
	}
	return Keycloak{
		baseURL:      baseURL,
		realm:        realm,
		clientID:     clientID,
		clientSecret: clientSecret,
		conf:         conf,
	}, nil
}

// GetAuthenticatedClient Return a http client with injected authorization header with auto refresh
func (k Keycloak) GetAuthenticatedClient() (*http.Client, error) {
	return k.conf.Client(context.Background()), nil
}

func (k Keycloak) GetAccessToken() (string, error) {
	tok, err := k.conf.Token(context.Background())
	if err != nil {
		return "", fmt.Errorf("unable to get access token: %w", err)
	}

	return tok.AccessToken, nil
}
