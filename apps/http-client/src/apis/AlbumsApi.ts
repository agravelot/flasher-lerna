/* tslint:disable */
/* eslint-disable */
/**
 * proto/articles/v1/articles.proto
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 1.0
 * 
 *
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


import * as runtime from '../runtime';
import {
    AlbumServiceUpdateRequest,
    AlbumServiceUpdateRequestFromJSON,
    AlbumServiceUpdateRequestToJSON,
    Protoalbumsv1CreateRequest,
    Protoalbumsv1CreateRequestFromJSON,
    Protoalbumsv1CreateRequestToJSON,
    Protoalbumsv1CreateResponse,
    Protoalbumsv1CreateResponseFromJSON,
    Protoalbumsv1CreateResponseToJSON,
    Protoalbumsv1DeleteResponse,
    Protoalbumsv1DeleteResponseFromJSON,
    Protoalbumsv1DeleteResponseToJSON,
    Protoalbumsv1GetBySlugResponse,
    Protoalbumsv1GetBySlugResponseFromJSON,
    Protoalbumsv1GetBySlugResponseToJSON,
    Protoalbumsv1IndexResponse,
    Protoalbumsv1IndexResponseFromJSON,
    Protoalbumsv1IndexResponseToJSON,
    Protoalbumsv1UpdateResponse,
    Protoalbumsv1UpdateResponseFromJSON,
    Protoalbumsv1UpdateResponseToJSON,
    RpcStatus,
    RpcStatusFromJSON,
    RpcStatusToJSON,
} from '../models';

export interface AlbumServiceCreateRequest {
    body: Protoalbumsv1CreateRequest;
}

export interface AlbumServiceDeleteRequest {
    id: number;
}

export interface AlbumServiceGetBySlugRequest {
    slug: string;
}

export interface AlbumServiceIndexRequest {
    limit?: number;
    next?: number;
    joinsCategories?: boolean;
    joinsMedias?: boolean;
}

export interface AlbumServiceUpdateOperationRequest {
    id: number;
    body: AlbumServiceUpdateRequest;
}

/**
 * 
 */
export class AlbumsApi extends runtime.BaseAPI {

    /**
     * Add an album to the server.
     * Add an album
     */
    async albumServiceCreateRaw(requestParameters: AlbumServiceCreateRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoalbumsv1CreateResponse>> {
        if (requestParameters.body === null || requestParameters.body === undefined) {
            throw new runtime.RequiredError('body','Required parameter requestParameters.body was null or undefined when calling albumServiceCreate.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        headerParameters['Content-Type'] = 'application/json';

        const response = await this.request({
            path: `/api/v1/albums`,
            method: 'POST',
            headers: headerParameters,
            query: queryParameters,
            body: Protoalbumsv1CreateRequestToJSON(requestParameters.body),
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoalbumsv1CreateResponseFromJSON(jsonValue));
    }

    /**
     * Add an album to the server.
     * Add an album
     */
    async albumServiceCreate(requestParameters: AlbumServiceCreateRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoalbumsv1CreateResponse> {
        const response = await this.albumServiceCreateRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Delete an album to the server.
     * Delete an album
     */
    async albumServiceDeleteRaw(requestParameters: AlbumServiceDeleteRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoalbumsv1DeleteResponse>> {
        if (requestParameters.id === null || requestParameters.id === undefined) {
            throw new runtime.RequiredError('id','Required parameter requestParameters.id was null or undefined when calling albumServiceDelete.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        const response = await this.request({
            path: `/api/v1/albums/{id}`.replace(`{${"id"}}`, encodeURIComponent(String(requestParameters.id))),
            method: 'DELETE',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoalbumsv1DeleteResponseFromJSON(jsonValue));
    }

    /**
     * Delete an album to the server.
     * Delete an album
     */
    async albumServiceDelete(requestParameters: AlbumServiceDeleteRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoalbumsv1DeleteResponse> {
        const response = await this.albumServiceDeleteRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Create an album to the server.
     * Create an album
     */
    async albumServiceGetBySlugRaw(requestParameters: AlbumServiceGetBySlugRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoalbumsv1GetBySlugResponse>> {
        if (requestParameters.slug === null || requestParameters.slug === undefined) {
            throw new runtime.RequiredError('slug','Required parameter requestParameters.slug was null or undefined when calling albumServiceGetBySlug.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        const response = await this.request({
            path: `/api/v1/albums/{slug}`.replace(`{${"slug"}}`, encodeURIComponent(String(requestParameters.slug))),
            method: 'GET',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoalbumsv1GetBySlugResponseFromJSON(jsonValue));
    }

    /**
     * Create an album to the server.
     * Create an album
     */
    async albumServiceGetBySlug(requestParameters: AlbumServiceGetBySlugRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoalbumsv1GetBySlugResponse> {
        const response = await this.albumServiceGetBySlugRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * List albums to the server.
     * List albums
     */
    async albumServiceIndexRaw(requestParameters: AlbumServiceIndexRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoalbumsv1IndexResponse>> {
        const queryParameters: any = {};

        if (requestParameters.limit !== undefined) {
            queryParameters['limit'] = requestParameters.limit;
        }

        if (requestParameters.next !== undefined) {
            queryParameters['next'] = requestParameters.next;
        }

        if (requestParameters.joinsCategories !== undefined) {
            queryParameters['joins.categories'] = requestParameters.joinsCategories;
        }

        if (requestParameters.joinsMedias !== undefined) {
            queryParameters['joins.medias'] = requestParameters.joinsMedias;
        }

        const headerParameters: runtime.HTTPHeaders = {};

        const response = await this.request({
            path: `/api/v1/albums`,
            method: 'GET',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoalbumsv1IndexResponseFromJSON(jsonValue));
    }

    /**
     * List albums to the server.
     * List albums
     */
    async albumServiceIndex(requestParameters: AlbumServiceIndexRequest = {}, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoalbumsv1IndexResponse> {
        const response = await this.albumServiceIndexRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Update an album to the server.
     * Update an album
     */
    async albumServiceUpdateRaw(requestParameters: AlbumServiceUpdateOperationRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoalbumsv1UpdateResponse>> {
        if (requestParameters.id === null || requestParameters.id === undefined) {
            throw new runtime.RequiredError('id','Required parameter requestParameters.id was null or undefined when calling albumServiceUpdate.');
        }

        if (requestParameters.body === null || requestParameters.body === undefined) {
            throw new runtime.RequiredError('body','Required parameter requestParameters.body was null or undefined when calling albumServiceUpdate.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        headerParameters['Content-Type'] = 'application/json';

        const response = await this.request({
            path: `/api/v1/albums/{id}`.replace(`{${"id"}}`, encodeURIComponent(String(requestParameters.id))),
            method: 'PUT',
            headers: headerParameters,
            query: queryParameters,
            body: AlbumServiceUpdateRequestToJSON(requestParameters.body),
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoalbumsv1UpdateResponseFromJSON(jsonValue));
    }

    /**
     * Update an album to the server.
     * Update an album
     */
    async albumServiceUpdate(requestParameters: AlbumServiceUpdateOperationRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoalbumsv1UpdateResponse> {
        const response = await this.albumServiceUpdateRaw(requestParameters, initOverrides);
        return await response.value();
    }

}