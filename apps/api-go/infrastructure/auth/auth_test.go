package auth_test

import (
	"api-go/infrastructure/auth"
	"context"
	"github.com/stretchr/testify/assert"
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/metadata"
	"google.golang.org/grpc/status"
	"testing"
)

type TestCase struct {
	name                 string
	ctx                  context.Context
	expectedCode         codes.Code
	expectedContextValue bool
}

func ctxWithAuthorization(ctx context.Context, token string) context.Context {
	return metadata.NewIncomingContext(ctx, metadata.Pairs("authorization", token))
}

var testCases = []TestCase{
	{name: "should not return an error no value is present", ctx: context.Background(), expectedCode: codes.OK},
	{
		name:         "should ignore authorization token if missing bearer prefix",
		ctx:          ctxWithAuthorization(context.Background(), "invalidtoken"),
		expectedCode: codes.Unauthenticated,
	},
	{
		name:         "should ignore authorization token with basic scheme",
		ctx:          ctxWithAuthorization(context.Background(), "Basic invalidtoken"),
		expectedCode: codes.Unauthenticated,
	},
	{
		name:         "should not return unauthenticated if invalidToken with Bearer prefix token is provided",
		ctx:          ctxWithAuthorization(context.Background(), "Bearer invalidToken"),
		expectedCode: codes.Unauthenticated,
	},
	{
		name:         "should not return unauthenticated if realm_access",
		ctx:          ctxWithAuthorization(context.Background(), "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"),
		expectedCode: codes.Unauthenticated,
	},
	{
		name:         "should return authenticated with bare minimum token",
		ctx:          ctxWithAuthorization(context.Background(), "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"),
		expectedCode: codes.OK,
	},
	{
		name:                 "should work with correctly formatted token",
		ctx:                  ctxWithAuthorization(context.Background(), "Bearer eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICI4bkpQdUgybG5DTkFlaEtMQUVRYnd4ODNhU0dqZzgxaVpLZ0V3ME5mRnZnIn0.eyJleHAiOjE2NzIwODgyNTcsImlhdCI6MTY3MjA4Nzk1NywiYXV0aF90aW1lIjoxNjcyMDg3OTU3LCJqdGkiOiIzZjRjZTA1Zi1lZTE2LTRmMWYtYjZiYy1kYTg2OTgzZDY1MWQiLCJpc3MiOiJodHRwczovL2FjY291bnRzLmprYW5kYS5mci9hdXRoL3JlYWxtcy9qa2FuZGEiLCJhdWQiOlsiYm8iLCJhY2NvdW50Il0sInN1YiI6ImY1N2IwM2RhLWI4YjYtNDU1Mi1iYjA3LTkxOWFlZGEwNzViZCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImZsYXNoZXIiLCJzZXNzaW9uX3N0YXRlIjoiNjU1MDliNjYtNmMxYy00OWI1LTg5YmYtZTkwMTQyMTY5YTRiIiwiYWNyIjoiMSIsImFsbG93ZWQtb3JpZ2lucyI6WyJodHRwczovL2FkbWluLmprYW5kYS5mciIsImh0dHBzOi8vamthbmRhLmZyIiwiaHR0cHM6Ly9hZG1pbnYyLmprYW5kYS5mciIsIioiLCJodHRwOi8vbG9jYWxob3N0OjMwMDEiLCJodHRwOi8vbG9jYWxob3N0OjMwMDAiXSwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwiYWRtaW4iLCJ1bWFfYXV0aG9yaXphdGlvbiJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImJvIjp7InJvbGVzIjpbImFkbWluIl19LCJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJkZWxldGUtYWNjb3VudCIsInZpZXctcHJvZmlsZSJdfSwiZmxhc2hlciI6eyJyb2xlcyI6WyJhZG1pbiJdfX0sInNjb3BlIjoib3BlbmlkIGVtYWlsIHByb2ZpbGUiLCJzaWQiOiI2NTUwOWI2Ni02YzFjLTQ5YjUtODliZi1lOTAxNDIxNjlhNGIiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiZ3JvdXBzIjpbIm9mZmxpbmVfYWNjZXNzIiwiYWRtaW4iLCJ1bWFfYXV0aG9yaXphdGlvbiJdLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJuZXZheCIsImVtYWlsIjoiYW50b2luZS5ncmF2ZWxvdEBob3RtYWlsLmZyIn0.noSign"),
		expectedCode:         codes.OK,
		expectedContextValue: true,
	},
}

func TestGrpcInterceptor(t *testing.T) {
	for _, tc := range testCases {
		t.Run(tc.name, func(t *testing.T) {
			// TODO assert context value
			_, err := auth.GrpcInterceptor(tc.ctx)

			assert.Equal(t, tc.expectedCode, status.Code(err))
		})
	}
}
