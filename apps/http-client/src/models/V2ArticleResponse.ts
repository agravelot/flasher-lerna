/* tslint:disable */
/* eslint-disable */
/**
 * proto/articles/v2/articles.proto
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
 * @interface V2ArticleResponse
 */
export interface V2ArticleResponse {
    /**
     * 
     * @type {string}
     * @memberof V2ArticleResponse
     */
    id: string;
    /**
     * 
     * @type {string}
     * @memberof V2ArticleResponse
     */
    slug: string;
    /**
     * 
     * @type {string}
     * @memberof V2ArticleResponse
     */
    name: string;
    /**
     * 
     * @type {string}
     * @memberof V2ArticleResponse
     */
    metaDescription: string;
    /**
     * 
     * @type {string}
     * @memberof V2ArticleResponse
     */
    content: string;
    /**
     * 
     * @type {Date}
     * @memberof V2ArticleResponse
     */
    publishedAt?: Date;
    /**
     * 
     * @type {string}
     * @memberof V2ArticleResponse
     */
    authorId?: string;
}

/**
 * Check if a given object implements the V2ArticleResponse interface.
 */
export function instanceOfV2ArticleResponse(value: object): boolean {
    let isInstance = true;
    isInstance = isInstance && "id" in value;
    isInstance = isInstance && "slug" in value;
    isInstance = isInstance && "name" in value;
    isInstance = isInstance && "metaDescription" in value;
    isInstance = isInstance && "content" in value;

    return isInstance;
}

export function V2ArticleResponseFromJSON(json: any): V2ArticleResponse {
    return V2ArticleResponseFromJSONTyped(json, false);
}

export function V2ArticleResponseFromJSONTyped(json: any, ignoreDiscriminator: boolean): V2ArticleResponse {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'id': json['id'],
        'slug': json['slug'],
        'name': json['name'],
        'metaDescription': json['metaDescription'],
        'content': json['content'],
        'publishedAt': !exists(json, 'publishedAt') ? undefined : (new Date(json['publishedAt'])),
        'authorId': !exists(json, 'authorId') ? undefined : json['authorId'],
    };
}

export function V2ArticleResponseToJSON(value?: V2ArticleResponse | null): any {
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
