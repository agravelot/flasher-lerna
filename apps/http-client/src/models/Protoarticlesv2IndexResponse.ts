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
import {
    V2ArticleResponse,
    V2ArticleResponseFromJSON,
    V2ArticleResponseFromJSONTyped,
    V2ArticleResponseToJSON,
} from './V2ArticleResponse';

/**
 * 
 * @export
 * @interface Protoarticlesv2IndexResponse
 */
export interface Protoarticlesv2IndexResponse {
    /**
     * 
     * @type {Array<V2ArticleResponse>}
     * @memberof Protoarticlesv2IndexResponse
     */
    data: Array<V2ArticleResponse>;
}

/**
 * Check if a given object implements the Protoarticlesv2IndexResponse interface.
 */
export function instanceOfProtoarticlesv2IndexResponse(value: object): boolean {
    let isInstance = true;
    isInstance = isInstance && "data" in value;

    return isInstance;
}

export function Protoarticlesv2IndexResponseFromJSON(json: any): Protoarticlesv2IndexResponse {
    return Protoarticlesv2IndexResponseFromJSONTyped(json, false);
}

export function Protoarticlesv2IndexResponseFromJSONTyped(json: any, ignoreDiscriminator: boolean): Protoarticlesv2IndexResponse {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'data': ((json['data'] as Array<any>).map(V2ArticleResponseFromJSON)),
    };
}

export function Protoarticlesv2IndexResponseToJSON(value?: Protoarticlesv2IndexResponse | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'data': ((value.data as Array<any>).map(V2ArticleResponseToJSON)),
    };
}

