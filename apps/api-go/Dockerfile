FROM golang:1.21 as builder
ARG BUF_TOKEN
WORKDIR /go/src/github.com/agravelot/api-go
# Fetch dependencies
COPY go.mod go.sum ./
RUN go mod download
# Build
COPY . .
RUN CGO_ENABLED=0 GOOS=linux go build -a -installsuffix cgo -o app ./cmd/app

FROM alpine:3.18.2
RUN apk --no-cache add ca-certificates
WORKDIR /root/
COPY --from=builder /go/src/github.com/agravelot/api-go .
CMD ["./app"]   