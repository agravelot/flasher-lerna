/* Do not change, this code is generated from Golang structs */


export interface ArticleRequest {
    id: number;
    slug: string;
    name: string;
    meta_description: string;
    content: string;
    published_at?: string;
}
export interface ArticleUpdateRequest {
    id: number;
    slug: string;
    name: string;
    meta_description: string;
    content: string;
    published_at?: string;
}
export interface ArticleResponse {
    id: number;
    slug: string;
    name: string;
    meta_description: string;
    content: string;
    author_uuid: string;
    published_at?: string;
}
export interface AlbumRequest {
    id: number;
    slug?: string;
    title: string;
    body?: string;
    published_at?: string;
    private: boolean;
    created_at?: string;
    updated_at?: string;
    notify_users_on_published: boolean;
    meta_description: string;
    sso_id?: number[];
}
export interface ResponsiveResponse {
    urls: string[];
    base64svg: string;
}
export interface ResponsiveImagesResponse {
    responsive: ResponsiveResponse;
}
export interface CustomPropertiesResponse {
    height: number;
    width: number;
}
export interface MediaReponse {
    id: number;
    name: string;
    file_name: string;
    mime_type?: string;
    size: number;
    custom_properties: CustomPropertiesResponse;
    responsive_images: ResponsiveImagesResponse;
    created_at?: string;
    updated_at?: string;
}
export interface CategoryReponse {
    id: number;
    name: string;
}
export interface AlbumResponse {
    id: number;
    slug: string;
    title: string;
    body?: string;
    published_at?: string;
    private: boolean;
    created_at?: string;
    updated_at?: string;
    notify_users_on_published: boolean;
    meta_description: string;
    sso_id?: string;
    categories?: CategoryReponse[];
    medias?: MediaReponse[];
}
export interface AlbumUpdateRequest {
    id: number;
    slug?: string;
    title: string;
    body?: string;
    published_at?: string;
    private: boolean;
    created_at?: string;
    updated_at?: string;
    notify_users_on_published: boolean;
    meta_description: string;
    sso_id?: number[];
}