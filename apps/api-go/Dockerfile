FROM golang:1.18 as builder
WORKDIR /go/src/github.com/agravelot/api-go
COPY . .
RUN CGO_ENABLED=0 GOOS=linux go build -a -installsuffix cgo -o app ./cmd/app

FROM alpine:3.15  
RUN apk --no-cache add ca-certificates
WORKDIR /root/
COPY --from=builder /go/src/github.com/agravelot/api-go .
CMD ["./app"]   