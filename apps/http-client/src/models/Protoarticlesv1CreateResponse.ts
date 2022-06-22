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
 * @interface Protoarticlesv1CreateResponse
 */
export interface Protoarticlesv1CreateResponse {
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1CreateResponse
     */
    id: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1CreateResponse
     */
    slug: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1CreateResponse
     */
    name: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1CreateResponse
     */
    metaDescription: string;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1CreateResponse
     */
    content: string;
    /**
     * 
     * @type {Date}
     * @memberof Protoarticlesv1CreateResponse
     */
    publishedAt?: Date;
    /**
     * 
     * @type {string}
     * @memberof Protoarticlesv1CreateResponse
     */
    authorId: string;
}

/**
 * Check if a given object implements the Protoarticlesv1CreateResponse interface.
 */
export function instanceOfProtoarticlesv1CreateResponse(value: object): boolean {
    let isInstance = true;
    isInstance = isInstance && "id" in value;
    isInstance = isInstance && "slug" in value;
    isInstance = isInstance && "name" in value;
    isInstance = isInstance && "metaDescription" in value;
    isInstance = isInstance && "content" in value;
    isInstance = isInstance && "authorId" in value;

    return isInstance;
}

export function Protoarticlesv1CreateResponseFromJSON(json: any): Protoarticlesv1CreateResponse {
    return Protoarticlesv1CreateResponseFromJSONTyped(json, false);
}

export function Protoarticlesv1CreateResponseFromJSONTyped(json: any, ignoreDiscriminator: boolean): Protoarticlesv1CreateResponse {
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
        'authorId': json['authorId'],
    };
}

export function Protoarticlesv1CreateResponseToJSON(value?: Protoarticlesv1CreateResponse | null): any {
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

