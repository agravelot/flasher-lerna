// Code generated by protoc-gen-go-grpc. DO NOT EDIT.
// versions:
// - protoc-gen-go-grpc v1.2.0
// - protoc             (unknown)
// source: proto/albums/v1/albums.proto

package albumsgrpc

import (
	context "context"
	grpc "google.golang.org/grpc"
	codes "google.golang.org/grpc/codes"
	status "google.golang.org/grpc/status"
)

// This is a compile-time assertion to ensure that this generated file
// is compatible with the grpc package it is being compiled against.
// Requires gRPC-Go v1.32.0 or later.
const _ = grpc.SupportPackageIsVersion7

// AlbumServiceClient is the client API for AlbumService service.
//
// For semantics around ctx use and closing/ending streaming RPCs, please refer to https://pkg.go.dev/google.golang.org/grpc/?tab=doc#ClientConn.NewStream.
type AlbumServiceClient interface {
	Index(ctx context.Context, in *IndexRequest, opts ...grpc.CallOption) (*IndexResponse, error)
	GetBySlug(ctx context.Context, in *GetBySlugRequest, opts ...grpc.CallOption) (*GetBySlugResponse, error)
	Create(ctx context.Context, in *CreateRequest, opts ...grpc.CallOption) (*CreateResponse, error)
	Delete(ctx context.Context, in *DeleteRequest, opts ...grpc.CallOption) (*DeleteResponse, error)
}

type albumServiceClient struct {
	cc grpc.ClientConnInterface
}

func NewAlbumServiceClient(cc grpc.ClientConnInterface) AlbumServiceClient {
	return &albumServiceClient{cc}
}

func (c *albumServiceClient) Index(ctx context.Context, in *IndexRequest, opts ...grpc.CallOption) (*IndexResponse, error) {
	out := new(IndexResponse)
	err := c.cc.Invoke(ctx, "/proto.albums.v1.AlbumService/Index", in, out, opts...)
	if err != nil {
		return nil, err
	}
	return out, nil
}

func (c *albumServiceClient) GetBySlug(ctx context.Context, in *GetBySlugRequest, opts ...grpc.CallOption) (*GetBySlugResponse, error) {
	out := new(GetBySlugResponse)
	err := c.cc.Invoke(ctx, "/proto.albums.v1.AlbumService/GetBySlug", in, out, opts...)
	if err != nil {
		return nil, err
	}
	return out, nil
}

func (c *albumServiceClient) Create(ctx context.Context, in *CreateRequest, opts ...grpc.CallOption) (*CreateResponse, error) {
	out := new(CreateResponse)
	err := c.cc.Invoke(ctx, "/proto.albums.v1.AlbumService/Create", in, out, opts...)
	if err != nil {
		return nil, err
	}
	return out, nil
}

func (c *albumServiceClient) Delete(ctx context.Context, in *DeleteRequest, opts ...grpc.CallOption) (*DeleteResponse, error) {
	out := new(DeleteResponse)
	err := c.cc.Invoke(ctx, "/proto.albums.v1.AlbumService/Delete", in, out, opts...)
	if err != nil {
		return nil, err
	}
	return out, nil
}

// AlbumServiceServer is the server API for AlbumService service.
// All implementations should embed UnimplementedAlbumServiceServer
// for forward compatibility
type AlbumServiceServer interface {
	Index(context.Context, *IndexRequest) (*IndexResponse, error)
	GetBySlug(context.Context, *GetBySlugRequest) (*GetBySlugResponse, error)
	Create(context.Context, *CreateRequest) (*CreateResponse, error)
	Delete(context.Context, *DeleteRequest) (*DeleteResponse, error)
}

// UnimplementedAlbumServiceServer should be embedded to have forward compatible implementations.
type UnimplementedAlbumServiceServer struct {
}

func (UnimplementedAlbumServiceServer) Index(context.Context, *IndexRequest) (*IndexResponse, error) {
	return nil, status.Errorf(codes.Unimplemented, "method Index not implemented")
}
func (UnimplementedAlbumServiceServer) GetBySlug(context.Context, *GetBySlugRequest) (*GetBySlugResponse, error) {
	return nil, status.Errorf(codes.Unimplemented, "method GetBySlug not implemented")
}
func (UnimplementedAlbumServiceServer) Create(context.Context, *CreateRequest) (*CreateResponse, error) {
	return nil, status.Errorf(codes.Unimplemented, "method Create not implemented")
}
func (UnimplementedAlbumServiceServer) Delete(context.Context, *DeleteRequest) (*DeleteResponse, error) {
	return nil, status.Errorf(codes.Unimplemented, "method Delete not implemented")
}

// UnsafeAlbumServiceServer may be embedded to opt out of forward compatibility for this service.
// Use of this interface is not recommended, as added methods to AlbumServiceServer will
// result in compilation errors.
type UnsafeAlbumServiceServer interface {
	mustEmbedUnimplementedAlbumServiceServer()
}

func RegisterAlbumServiceServer(s grpc.ServiceRegistrar, srv AlbumServiceServer) {
	s.RegisterService(&AlbumService_ServiceDesc, srv)
}

func _AlbumService_Index_Handler(srv interface{}, ctx context.Context, dec func(interface{}) error, interceptor grpc.UnaryServerInterceptor) (interface{}, error) {
	in := new(IndexRequest)
	if err := dec(in); err != nil {
		return nil, err
	}
	if interceptor == nil {
		return srv.(AlbumServiceServer).Index(ctx, in)
	}
	info := &grpc.UnaryServerInfo{
		Server:     srv,
		FullMethod: "/proto.albums.v1.AlbumService/Index",
	}
	handler := func(ctx context.Context, req interface{}) (interface{}, error) {
		return srv.(AlbumServiceServer).Index(ctx, req.(*IndexRequest))
	}
	return interceptor(ctx, in, info, handler)
}

func _AlbumService_GetBySlug_Handler(srv interface{}, ctx context.Context, dec func(interface{}) error, interceptor grpc.UnaryServerInterceptor) (interface{}, error) {
	in := new(GetBySlugRequest)
	if err := dec(in); err != nil {
		return nil, err
	}
	if interceptor == nil {
		return srv.(AlbumServiceServer).GetBySlug(ctx, in)
	}
	info := &grpc.UnaryServerInfo{
		Server:     srv,
		FullMethod: "/proto.albums.v1.AlbumService/GetBySlug",
	}
	handler := func(ctx context.Context, req interface{}) (interface{}, error) {
		return srv.(AlbumServiceServer).GetBySlug(ctx, req.(*GetBySlugRequest))
	}
	return interceptor(ctx, in, info, handler)
}

func _AlbumService_Create_Handler(srv interface{}, ctx context.Context, dec func(interface{}) error, interceptor grpc.UnaryServerInterceptor) (interface{}, error) {
	in := new(CreateRequest)
	if err := dec(in); err != nil {
		return nil, err
	}
	if interceptor == nil {
		return srv.(AlbumServiceServer).Create(ctx, in)
	}
	info := &grpc.UnaryServerInfo{
		Server:     srv,
		FullMethod: "/proto.albums.v1.AlbumService/Create",
	}
	handler := func(ctx context.Context, req interface{}) (interface{}, error) {
		return srv.(AlbumServiceServer).Create(ctx, req.(*CreateRequest))
	}
	return interceptor(ctx, in, info, handler)
}

func _AlbumService_Delete_Handler(srv interface{}, ctx context.Context, dec func(interface{}) error, interceptor grpc.UnaryServerInterceptor) (interface{}, error) {
	in := new(DeleteRequest)
	if err := dec(in); err != nil {
		return nil, err
	}
	if interceptor == nil {
		return srv.(AlbumServiceServer).Delete(ctx, in)
	}
	info := &grpc.UnaryServerInfo{
		Server:     srv,
		FullMethod: "/proto.albums.v1.AlbumService/Delete",
	}
	handler := func(ctx context.Context, req interface{}) (interface{}, error) {
		return srv.(AlbumServiceServer).Delete(ctx, req.(*DeleteRequest))
	}
	return interceptor(ctx, in, info, handler)
}

// AlbumService_ServiceDesc is the grpc.ServiceDesc for AlbumService service.
// It's only intended for direct use with grpc.RegisterService,
// and not to be introspected or modified (even as a copy)
var AlbumService_ServiceDesc = grpc.ServiceDesc{
	ServiceName: "proto.albums.v1.AlbumService",
	HandlerType: (*AlbumServiceServer)(nil),
	Methods: []grpc.MethodDesc{
		{
			MethodName: "Index",
			Handler:    _AlbumService_Index_Handler,
		},
		{
			MethodName: "GetBySlug",
			Handler:    _AlbumService_GetBySlug_Handler,
		},
		{
			MethodName: "Create",
			Handler:    _AlbumService_Create_Handler,
		},
		{
			MethodName: "Delete",
			Handler:    _AlbumService_Delete_Handler,
		},
	},
	Streams:  []grpc.StreamDesc{},
	Metadata: "proto/albums/v1/albums.proto",
}
