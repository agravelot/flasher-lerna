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
import {
    V1ArticleResponse,
    V1ArticleResponseFromJSON,
    V1ArticleResponseFromJSONTyped,
    V1ArticleResponseToJSON,
} from './V1ArticleResponse';

/**
 * 
 * @export
 * @interface V1IndexResponse
 */
export interface V1IndexResponse {
    /**
     * 
     * @type {Array<V1ArticleResponse>}
     * @memberof V1IndexResponse
     */
    data?: Array<V1ArticleResponse>;
}

/**
 * Check if a given object implements the V1IndexResponse interface.
 */
export function instanceOfV1IndexResponse(value: object): boolean {
    let isInstance = true;

    return isInstance;
}

export function V1IndexResponseFromJSON(json: any): V1IndexResponse {
    return V1IndexResponseFromJSONTyped(json, false);
}

export function V1IndexResponseFromJSONTyped(json: any, ignoreDiscriminator: boolean): V1IndexResponse {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'data': !exists(json, 'data') ? undefined : ((json['data'] as Array<any>).map(V1ArticleResponseFromJSON)),
    };
}

export function V1IndexResponseToJSON(value?: V1IndexResponse | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'data': value.data === undefined ? undefined : ((value.data as Array<any>).map(V1ArticleResponseToJSON)),
    };
}

