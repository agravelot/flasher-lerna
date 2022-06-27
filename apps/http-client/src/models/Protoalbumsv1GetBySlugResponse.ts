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
    V1AlbumResponse,
    V1AlbumResponseFromJSON,
    V1AlbumResponseFromJSONTyped,
    V1AlbumResponseToJSON,
} from './V1AlbumResponse';

/**
 * 
 * @export
 * @interface Protoalbumsv1GetBySlugResponse
 */
export interface Protoalbumsv1GetBySlugResponse {
    /**
     * 
     * @type {V1AlbumResponse}
     * @memberof Protoalbumsv1GetBySlugResponse
     */
    album?: V1AlbumResponse;
}

/**
 * Check if a given object implements the Protoalbumsv1GetBySlugResponse interface.
 */
export function instanceOfProtoalbumsv1GetBySlugResponse(value: object): boolean {
    let isInstance = true;

    return isInstance;
}

export function Protoalbumsv1GetBySlugResponseFromJSON(json: any): Protoalbumsv1GetBySlugResponse {
    return Protoalbumsv1GetBySlugResponseFromJSONTyped(json, false);
}

export function Protoalbumsv1GetBySlugResponseFromJSONTyped(json: any, ignoreDiscriminator: boolean): Protoalbumsv1GetBySlugResponse {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'album': !exists(json, 'album') ? undefined : V1AlbumResponseFromJSON(json['album']),
    };
}

export function Protoalbumsv1GetBySlugResponseToJSON(value?: Protoalbumsv1GetBySlugResponse | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'album': V1AlbumResponseToJSON(value.album),
    };
}

