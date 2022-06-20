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
 * @interface Protoarticlesv1UpdateRequest
 */
export interface Protoarticlesv1UpdateRequest {
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1UpdateRequest
     */
    id?: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1UpdateRequest
     */
    slug?: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1UpdateRequest
     */
    name?: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1UpdateRequest
     */
    metaDescription?: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1UpdateRequest
     */
    content?: string;
    /**
     * 
     * @type {Date}
     * @memberof Protoarticlesv1UpdateRequest
     */
    publishedAt?: Date;
}

/**
 * Check if a given object implements the Protoarticlesv1UpdateRequest interface.
 */
export function instanceOfProtoarticlesv1UpdateRequest(value: object): boolean {
    let isInstance = true;

    return isInstance;
}

export function Protoarticlesv1UpdateRequestFromJSON(json: any): Protoarticlesv1UpdateRequest {
    return Protoarticlesv1UpdateRequestFromJSONTyped(json, false);
}

export function Protoarticlesv1UpdateRequestFromJSONTyped(json: any, ignoreDiscriminator: boolean): Protoarticlesv1UpdateRequest {
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

export function Protoarticlesv1UpdateRequestToJSON(value?: Protoarticlesv1UpdateRequest | null): any {
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

