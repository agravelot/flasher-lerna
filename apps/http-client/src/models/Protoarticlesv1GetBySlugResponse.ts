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
 * @interface Protoarticlesv1GetBySlugResponse
 */
export interface Protoarticlesv1GetBySlugResponse {
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1GetBySlugResponse
     */
    id?: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1GetBySlugResponse
     */
    slug?: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1GetBySlugResponse
     */
    name?: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1GetBySlugResponse
     */
    metaDescription?: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1GetBySlugResponse
     */
    content?: string;
    /**
     * 
     * @type {Date}
     * @memberof Protoarticlesv1GetBySlugResponse
     */
    publishedAt?: Date;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1GetBySlugResponse
     */
    authorId?: string;
}

/**
 * Check if a given object implements the Protoarticlesv1GetBySlugResponse interface.
 */
export function instanceOfProtoarticlesv1GetBySlugResponse(value: object): boolean {
    let isInstance = true;

    return isInstance;
}

export function Protoarticlesv1GetBySlugResponseFromJSON(json: any): Protoarticlesv1GetBySlugResponse {
    return Protoarticlesv1GetBySlugResponseFromJSONTyped(json, false);
}

export function Protoarticlesv1GetBySlugResponseFromJSONTyped(json: any, ignoreDiscriminator: boolean): Protoarticlesv1GetBySlugResponse {
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
        'authorId': !exists(json, 'authorId') ? undefined : json['authorId'],
    };
}

export function Protoarticlesv1GetBySlugResponseToJSON(value?: Protoarticlesv1GetBySlugResponse | null): any {
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
        'authorId': value.authorId,
    };
}
