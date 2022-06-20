/* tslint:disable */
/* eslint-disable */
/**
 * proto/medias/v1/medias.proto
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: version not set
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
 * @interface V1UpdateRequest
 */
export interface V1UpdateRequest {
    /**
     * 
     * @type {string}
     * @memberof V1UpdateRequest
     */
    id?: string;
    /**
     * 
     * @type {string}
     * @memberof V1UpdateRequest
     */
    slug?: string;
    /**
     * 
     * @type {string}
     * @memberof V1UpdateRequest
     */
    name?: string;
    /**
     * 
     * @type {string}
     * @memberof V1UpdateRequest
     */
    metaDescription?: string;
    /**
     * 
     * @type {string}
     * @memberof V1UpdateRequest
     */
    content?: string;
    /**
     * 
     * @type {Date}
     * @memberof V1UpdateRequest
     */
    publishedAt?: Date;
}

/**
 * Check if a given object implements the V1UpdateRequest interface.
 */
export function instanceOfV1UpdateRequest(value: object): boolean {
    let isInstance = true;

    return isInstance;
}

export function V1UpdateRequestFromJSON(json: any): V1UpdateRequest {
    return V1UpdateRequestFromJSONTyped(json, false);
}

export function V1UpdateRequestFromJSONTyped(json: any, ignoreDiscriminator: boolean): V1UpdateRequest {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'id': !exists(json, 'id') ? undefined : json['id'],
        'slug': !exists(json, 'slug') ? undefined : json['slug'],
        'name': !exists(json, 'name') ? undefined : json['name'],
        'metaDescription': !exists(json, 'metaDescription') ? undefined : json['metaDescription'],
        'content': !exists(json, 'content') ? undefined : json['content'],
        'publishedAt': !exists(json, 'publishedAt') ? undefined : (new Date(json['publishedAt'])),
    };
}

export function V1UpdateRequestToJSON(value?: V1UpdateRequest | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'id': value.id,
        'slug': value.slug,
        'name': value.name,
        'metaDescription': value.metaDescription,
        'content': value.content,
        'publishedAt': value.publishedAt === undefined ? undefined : (value.publishedAt.toISOString()),
    };
}

