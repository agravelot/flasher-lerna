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
    Protoarticlesv1CreateRequest,
    Protoarticlesv1CreateRequestFromJSON,
    Protoarticlesv1CreateRequestToJSON,
    Protoarticlesv1CreateResponse,
    Protoarticlesv1CreateResponseFromJSON,
    Protoarticlesv1CreateResponseToJSON,
    Protoarticlesv1GetBySlugResponse,
    Protoarticlesv1GetBySlugResponseFromJSON,
    Protoarticlesv1GetBySlugResponseToJSON,
    Protoarticlesv1IndexResponse,
    Protoarticlesv1IndexResponseFromJSON,
    Protoarticlesv1IndexResponseToJSON,
    Protoarticlesv1UpdateRequest,
    Protoarticlesv1UpdateRequestFromJSON,
    Protoarticlesv1UpdateRequestToJSON,
    Protoarticlesv1UpdateResponse,
    Protoarticlesv1UpdateResponseFromJSON,
    Protoarticlesv1UpdateResponseToJSON,
    RpcStatus,
    RpcStatusFromJSON,
    RpcStatusToJSON,
} from '../models';

export interface ArticleServiceCreateRequest {
    body: Protoarticlesv1CreateRequest;
}

export interface ArticleServiceDeleteRequest {
    id: string;
}

export interface ArticleServiceGetBySlugRequest {
    slug: string;
}

export interface ArticleServiceIndexRequest {
    limit?: number;
    next?: number;
}

export interface ArticleServiceUpdateRequest {
    body: Protoarticlesv1UpdateRequest;
}

/**
 * 
 */
export class ArticlesApi extends runtime.BaseAPI {

    /**
     * Add an article to the server.
     * Add an article
     */
    async articleServiceCreateRaw(requestParameters: ArticleServiceCreateRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoarticlesv1CreateResponse>> {
        if (requestParameters.body === null || requestParameters.body === undefined) {
            throw new runtime.RequiredError('body','Required parameter requestParameters.body was null or undefined when calling articleServiceCreate.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        headerParameters['Content-Type'] = 'application/json';

        const response = await this.request({
            path: `/api/v1/articles`,
            method: 'POST',
            headers: headerParameters,
            query: queryParameters,
            body: Protoarticlesv1CreateRequestToJSON(requestParameters.body),
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoarticlesv1CreateResponseFromJSON(jsonValue));
    }

    /**
     * Add an article to the server.
     * Add an article
     */
    async articleServiceCreate(requestParameters: ArticleServiceCreateRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoarticlesv1CreateResponse> {
        const response = await this.articleServiceCreateRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Delete an article to the server.
     * Delete an article
     */
    async articleServiceDeleteRaw(requestParameters: ArticleServiceDeleteRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<object>> {
        if (requestParameters.id === null || requestParameters.id === undefined) {
            throw new runtime.RequiredError('id','Required parameter requestParameters.id was null or undefined when calling articleServiceDelete.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        const response = await this.request({
            path: `/api/v1/articles/{id}`.replace(`{${"id"}}`, encodeURIComponent(String(requestParameters.id))),
            method: 'DELETE',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse<any>(response);
    }

    /**
     * Delete an article to the server.
     * Delete an article
     */
    async articleServiceDelete(requestParameters: ArticleServiceDeleteRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<object> {
        const response = await this.articleServiceDeleteRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Get an article to the server.
     * Get an article by slug
     */
    async articleServiceGetBySlugRaw(requestParameters: ArticleServiceGetBySlugRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoarticlesv1GetBySlugResponse>> {
        if (requestParameters.slug === null || requestParameters.slug === undefined) {
            throw new runtime.RequiredError('slug','Required parameter requestParameters.slug was null or undefined when calling articleServiceGetBySlug.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        const response = await this.request({
            path: `/api/v1/articles/{slug}`.replace(`{${"slug"}}`, encodeURIComponent(String(requestParameters.slug))),
            method: 'GET',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoarticlesv1GetBySlugResponseFromJSON(jsonValue));
    }

    /**
     * Get an article to the server.
     * Get an article by slug
     */
    async articleServiceGetBySlug(requestParameters: ArticleServiceGetBySlugRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoarticlesv1GetBySlugResponse> {
        const response = await this.articleServiceGetBySlugRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * List articles to the server.
     * List articles
     */
    async articleServiceIndexRaw(requestParameters: ArticleServiceIndexRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoarticlesv1IndexResponse>> {
        const queryParameters: any = {};

        if (requestParameters.limit !== undefined) {
            queryParameters['limit'] = requestParameters.limit;
        }

        if (requestParameters.next !== undefined) {
            queryParameters['next'] = requestParameters.next;
        }

        const headerParameters: runtime.HTTPHeaders = {};

        const response = await this.request({
            path: `/api/v1/articles`,
            method: 'GET',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoarticlesv1IndexResponseFromJSON(jsonValue));
    }

    /**
     * List articles to the server.
     * List articles
     */
    async articleServiceIndex(requestParameters: ArticleServiceIndexRequest = {}, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoarticlesv1IndexResponse> {
        const response = await this.articleServiceIndexRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Update an article to the server.
     * Update an article
     */
    async articleServiceUpdateRaw(requestParameters: ArticleServiceUpdateRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<runtime.ApiResponse<Protoarticlesv1UpdateResponse>> {
        if (requestParameters.body === null || requestParameters.body === undefined) {
            throw new runtime.RequiredError('body','Required parameter requestParameters.body was null or undefined when calling articleServiceUpdate.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        headerParameters['Content-Type'] = 'application/json';

        const response = await this.request({
            path: `/api/v1/articles`,
            method: 'PUT',
            headers: headerParameters,
            query: queryParameters,
            body: Protoarticlesv1UpdateRequestToJSON(requestParameters.body),
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => Protoarticlesv1UpdateResponseFromJSON(jsonValue));
    }

    /**
     * Update an article to the server.
     * Update an article
     */
    async articleServiceUpdate(requestParameters: ArticleServiceUpdateRequest, initOverrides?: RequestInit | runtime.InitOverrideFunction): Promise<Protoarticlesv1UpdateResponse> {
        const response = await this.articleServiceUpdateRaw(requestParameters, initOverrides);
        return await response.value();
    }

}
