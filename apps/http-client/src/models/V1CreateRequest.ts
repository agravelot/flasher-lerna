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

import { exists, mapValues } from '../runtime';
/**
 * 
 * @export
 * @interface V1CreateRequest
 */
export interface V1CreateRequest {
    /**
     * 
     * @type {string}
     * @memberof V1CreateRequest
     */
    slug?: string;
    /**
     * 
     * @type {string}
     * @memberof V1CreateRequest
     */
    name?: string;
    /**
     * 
     * @type {string}
     * @memberof V1CreateRequest
     */
    metaDescription?: string;
    /**
     * 
     * @type {string}
     * @memberof V1CreateRequest
     */
    content?: string;
    /**
     * 
     * @type {Date}
     * @memberof V1CreateRequest
     */
    publishedAt?: Date;
    /**
     * 
     * @type {boolean}
     * @memberof V1CreateRequest
     */
    _private?: boolean;
    /**
     * 
     * @type {string}
     * @memberof V1CreateRequest
     */
    id?: string;
}

/**
 * Check if a given object implements the V1CreateRequest interface.
 */
export function instanceOfV1CreateRequest(value: object): boolean {
    let isInstance = true;

    return isInstance;
}

export function V1CreateRequestFromJSON(json: any): V1CreateRequest {
    return V1CreateRequestFromJSONTyped(json, false);
}

export function V1CreateRequestFromJSONTyped(json: any, ignoreDiscriminator: boolean): V1CreateRequest {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'slug': !exists(json, 'slug') ? undefined : json['slug'],
        'name': !exists(json, 'name') ? undefined : json['name'],
        'metaDescription': !exists(json, 'metaDescription') ? undefined : json['metaDescription'],
        'content': !exists(json, 'content') ? undefined : json['content'],
        'publishedAt': !exists(json, 'publishedAt') ? undefined : (new Date(json['publishedAt'])),
        '_private': !exists(json, 'private') ? undefined : json['private'],
        'id': !exists(json, 'id') ? undefined : json['id'],
    };
}

export function V1CreateRequestToJSON(value?: V1CreateRequest | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'slug': value.slug,
        'name': value.name,
        'metaDescription': value.metaDescription,
        'content': value.content,
        'publishedAt': value.publishedAt === undefined ? undefined : (value.publishedAt.toISOString()),
        'private': value._private,
        'id': value.id,
    };
}

