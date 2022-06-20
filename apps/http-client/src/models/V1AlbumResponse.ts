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
import {
    V1Category,
    V1CategoryFromJSON,
    V1CategoryFromJSONTyped,
    V1CategoryToJSON,
} from './V1Category';
import {
    V1Media,
    V1MediaFromJSON,
    V1MediaFromJSONTyped,
    V1MediaToJSON,
} from './V1Media';

/**
 * 
 * @export
 * @interface V1AlbumResponse
 */
export interface V1AlbumResponse {
    /**
     * 
     * @type {number}
     * @memberof V1AlbumResponse
     */
    id?: number;
    /**
     * 
     * @type {string}
     * @memberof V1AlbumResponse
     */
    slug?: string;
    /**
     * 
     * @type {string}
     * @memberof V1AlbumResponse
     */
    title?: string;
    /**
     * 
     * @type {string}
     * @memberof V1AlbumResponse
     */
    metaDescription?: string;
    /**
     * 
     * @type {string}
     * @memberof V1AlbumResponse
     */
    content?: string;
    /**
     * 
     * @type {Date}
     * @memberof V1AlbumResponse
     */
    publishedAt?: Date;
    /**
     * 
     * @type {string}
     * @memberof V1AlbumResponse
     */
    authorId?: string;
    /**
     * 
     * @type {boolean}
     * @memberof V1AlbumResponse
     */
    _private?: boolean;
    /**
     * 
     * @type {string}
     * @memberof V1AlbumResponse
     */
    userId?: string;
    /**
     * 
     * @type {Date}
     * @memberof V1AlbumResponse
     */
    createdAt?: Date;
    /**
     * 
     * @type {Date}
     * @memberof V1AlbumResponse
     */
    updatedAt?: Date;
    /**
     * 
     * @type {boolean}
     * @memberof V1AlbumResponse
     */
    notifyUsersOnPublished?: boolean;
    /**
     * 
     * @type {Array<V1Category>}
     * @memberof V1AlbumResponse
     */
    categories?: Array<V1Category>;
    /**
     * 
     * @type {Array<V1Media>}
     * @memberof V1AlbumResponse
     */
    medias?: Array<V1Media>;
}

/**
 * Check if a given object implements the V1AlbumResponse interface.
 */
export function instanceOfV1AlbumResponse(value: object): boolean {
    let isInstance = true;

    return isInstance;
}

export function V1AlbumResponseFromJSON(json: any): V1AlbumResponse {
    return V1AlbumResponseFromJSONTyped(json, false);
}

export function V1AlbumResponseFromJSONTyped(json: any, ignoreDiscriminator: boolean): V1AlbumResponse {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'id': !exists(json, 'id') ? undefined : json['id'],
        'slug': !exists(json, 'slug') ? undefined : json['slug'],
        'title': !exists(json, 'title') ? undefined : json['title'],
        'metaDescription': !exists(json, 'metaDescription') ? undefined : json['metaDescription'],
        'content': !exists(json, 'content') ? undefined : json['content'],
        'publishedAt': !exists(json, 'publishedAt') ? undefined : (new Date(json['publishedAt'])),
        'authorId': !exists(json, 'authorId') ? undefined : json['authorId'],
        '_private': !exists(json, 'private') ? undefined : json['private'],
        'userId': !exists(json, 'userId') ? undefined : json['userId'],
        'createdAt': !exists(json, 'createdAt') ? undefined : (new Date(json['createdAt'])),
        'updatedAt': !exists(json, 'updatedAt') ? undefined : (new Date(json['updatedAt'])),
        'notifyUsersOnPublished': !exists(json, 'notifyUsersOnPublished') ? undefined : json['notifyUsersOnPublished'],
        'categories': !exists(json, 'categories') ? undefined : ((json['categories'] as Array<any>).map(V1CategoryFromJSON)),
        'medias': !exists(json, 'medias') ? undefined : ((json['medias'] as Array<any>).map(V1MediaFromJSON)),
    };
}

export function V1AlbumResponseToJSON(value?: V1AlbumResponse | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'id': value.id,
        'slug': value.slug,
        'title': value.title,
        'metaDescription': value.metaDescription,
        'content': value.content,
        'publishedAt': value.publishedAt === undefined ? undefined : (value.publishedAt.toISOString()),
        'authorId': value.authorId,
        'private': value._private,
        'userId': value.userId,
        'createdAt': value.createdAt === undefined ? undefined : (value.createdAt.toISOString()),
        'updatedAt': value.updatedAt === undefined ? undefined : (value.updatedAt.toISOString()),
        'notifyUsersOnPublished': value.notifyUsersOnPublished,
        'categories': value.categories === undefined ? undefined : ((value.categories as Array<any>).map(V1CategoryToJSON)),
        'medias': value.medias === undefined ? undefined : ((value.medias as Array<any>).map(V1MediaToJSON)),
    };
}

