


# proto/articles/v2/articles.proto
  
> [gRPC-gateway flasher api](https://github.com/agravelot/flasher-lerna/apps/api-go)

## Informations

### Version

1.0

## Tags

  ### <span id="tag-article-service"></span>ArticleService

  ### <span id="tag-media-service"></span>MediaService

  ### <span id="tag-album-service"></span>AlbumService

## Content negotiation

### URI Schemes
  * http

### Consumes
  * application/json

### Produces
  * application/json

## All endpoints

###  albums

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/v2/albums | [album service create](#album-service-create) | Add an album |
| DELETE | /api/v2/albums/{id} | [album service delete](#album-service-delete) | Delete an album |
| GET | /api/v2/albums/{slug} | [album service get by slug](#album-service-get-by-slug) | Create an album |
| GET | /api/v2/albums | [album service index](#album-service-index) | List albums |
| PUT | /api/v2/albums/{id} | [album service update](#album-service-update) | Update an album |
  


###  articles

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/v2/articles | [article service create](#article-service-create) | Add an article |
| DELETE | /api/v2/articles/{id} | [article service delete](#article-service-delete) | Delete an article |
| GET | /api/v2/articles/{slug} | [article service get by slug](#article-service-get-by-slug) | Get an article by slug |
| GET | /api/v2/articles | [article service index](#article-service-index) | List articles |
| PUT | /api/v2/articles | [article service update](#article-service-update) | Update an article |
  


###  medias

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/v2/medias | [media service create](#media-service-create) | Add an media |
| DELETE | /api/v2/medias/{id} | [media service delete](#media-service-delete) | Delete an media |
  


## Paths

### <span id="album-service-create"></span> Add an album (*AlbumService_Create*)

```
POST /api/v2/albums
```

Add an album to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| body | `body` | [Protoalbumsv2CreateRequest](#protoalbumsv2-create-request) | `models.Protoalbumsv2CreateRequest` | | ✓ | |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#album-service-create-200) | OK | A successful response. |  | [schema](#album-service-create-200-schema) |
| [default](#album-service-create-default) | | An unexpected error response. |  | [schema](#album-service-create-default-schema) |

#### Responses


##### <span id="album-service-create-200"></span> 200 - A successful response.
Status: OK

###### <span id="album-service-create-200-schema"></span> Schema
   
  

[Protoalbumsv2CreateResponse](#protoalbumsv2-create-response)

##### <span id="album-service-create-default"></span> Default Response
An unexpected error response.

###### <span id="album-service-create-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="album-service-delete"></span> Delete an album (*AlbumService_Delete*)

```
DELETE /api/v2/albums/{id}
```

Delete an album to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| id | `path` | int32 (formatted integer) | `int32` |  | ✓ |  |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#album-service-delete-200) | OK | A successful response. |  | [schema](#album-service-delete-200-schema) |
| [default](#album-service-delete-default) | | An unexpected error response. |  | [schema](#album-service-delete-default-schema) |

#### Responses


##### <span id="album-service-delete-200"></span> 200 - A successful response.
Status: OK

###### <span id="album-service-delete-200-schema"></span> Schema
   
  

[Protoalbumsv2DeleteResponse](#protoalbumsv2-delete-response)

##### <span id="album-service-delete-default"></span> Default Response
An unexpected error response.

###### <span id="album-service-delete-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="album-service-get-by-slug"></span> Create an album (*AlbumService_GetBySlug*)

```
GET /api/v2/albums/{slug}
```

Create an album to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| slug | `path` | string | `string` |  | ✓ |  |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#album-service-get-by-slug-200) | OK | A successful response. |  | [schema](#album-service-get-by-slug-200-schema) |
| [default](#album-service-get-by-slug-default) | | An unexpected error response. |  | [schema](#album-service-get-by-slug-default-schema) |

#### Responses


##### <span id="album-service-get-by-slug-200"></span> 200 - A successful response.
Status: OK

###### <span id="album-service-get-by-slug-200-schema"></span> Schema
   
  

[Protoalbumsv2GetBySlugResponse](#protoalbumsv2-get-by-slug-response)

##### <span id="album-service-get-by-slug-default"></span> Default Response
An unexpected error response.

###### <span id="album-service-get-by-slug-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="album-service-index"></span> List albums (*AlbumService_Index*)

```
GET /api/v2/albums
```

List albums to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| joins.categories | `query` | boolean | `bool` |  |  |  |  |
| joins.medias | `query` | boolean | `bool` |  |  |  |  |
| limit | `query` | int32 (formatted integer) | `int32` |  |  |  |  |
| next | `query` | int32 (formatted integer) | `int32` |  |  |  |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#album-service-index-200) | OK | A successful response. |  | [schema](#album-service-index-200-schema) |
| [default](#album-service-index-default) | | An unexpected error response. |  | [schema](#album-service-index-default-schema) |

#### Responses


##### <span id="album-service-index-200"></span> 200 - A successful response.
Status: OK

###### <span id="album-service-index-200-schema"></span> Schema
   
  

[Protoalbumsv2IndexResponse](#protoalbumsv2-index-response)

##### <span id="album-service-index-default"></span> Default Response
An unexpected error response.

###### <span id="album-service-index-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="album-service-update"></span> Update an album (*AlbumService_Update*)

```
PUT /api/v2/albums/{id}
```

Update an album to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| id | `path` | int32 (formatted integer) | `int32` |  | ✓ |  |  |
| body | `body` | [AlbumServiceUpdateBody](#album-service-update-body) | `AlbumServiceUpdateBody` | | ✓ | |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#album-service-update-200) | OK | A successful response. |  | [schema](#album-service-update-200-schema) |
| [default](#album-service-update-default) | | An unexpected error response. |  | [schema](#album-service-update-default-schema) |

#### Responses


##### <span id="album-service-update-200"></span> 200 - A successful response.
Status: OK

###### <span id="album-service-update-200-schema"></span> Schema
   
  

[Protoalbumsv2UpdateResponse](#protoalbumsv2-update-response)

##### <span id="album-service-update-default"></span> Default Response
An unexpected error response.

###### <span id="album-service-update-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

###### Inlined models

**<span id="album-service-update-body"></span> AlbumServiceUpdateBody**


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| content | string| `string` |  | |  |  |
| metaDescription | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |
| private | boolean| `bool` |  | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |



### <span id="article-service-create"></span> Add an article (*ArticleService_Create*)

```
POST /api/v2/articles
```

Add an article to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| body | `body` | [Protoarticlesv2CreateRequest](#protoarticlesv2-create-request) | `models.Protoarticlesv2CreateRequest` | | ✓ | |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#article-service-create-200) | OK | A successful response. |  | [schema](#article-service-create-200-schema) |
| [default](#article-service-create-default) | | An unexpected error response. |  | [schema](#article-service-create-default-schema) |

#### Responses


##### <span id="article-service-create-200"></span> 200 - A successful response.
Status: OK

###### <span id="article-service-create-200-schema"></span> Schema
   
  

[Protoarticlesv2CreateResponse](#protoarticlesv2-create-response)

##### <span id="article-service-create-default"></span> Default Response
An unexpected error response.

###### <span id="article-service-create-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="article-service-delete"></span> Delete an article (*ArticleService_Delete*)

```
DELETE /api/v2/articles/{id}
```

Delete an article to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| id | `path` | int32 (formatted integer) | `int32` |  | ✓ |  |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#article-service-delete-200) | OK | A successful response. |  | [schema](#article-service-delete-200-schema) |
| [default](#article-service-delete-default) | | An unexpected error response. |  | [schema](#article-service-delete-default-schema) |

#### Responses


##### <span id="article-service-delete-200"></span> 200 - A successful response.
Status: OK

###### <span id="article-service-delete-200-schema"></span> Schema
   
  

[Protoarticlesv2DeleteResponse](#protoarticlesv2-delete-response)

##### <span id="article-service-delete-default"></span> Default Response
An unexpected error response.

###### <span id="article-service-delete-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="article-service-get-by-slug"></span> Get an article by slug (*ArticleService_GetBySlug*)

```
GET /api/v2/articles/{slug}
```

Get an article to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| slug | `path` | string | `string` |  | ✓ |  |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#article-service-get-by-slug-200) | OK | A successful response. |  | [schema](#article-service-get-by-slug-200-schema) |
| [default](#article-service-get-by-slug-default) | | An unexpected error response. |  | [schema](#article-service-get-by-slug-default-schema) |

#### Responses


##### <span id="article-service-get-by-slug-200"></span> 200 - A successful response.
Status: OK

###### <span id="article-service-get-by-slug-200-schema"></span> Schema
   
  

[Protoarticlesv2GetBySlugResponse](#protoarticlesv2-get-by-slug-response)

##### <span id="article-service-get-by-slug-default"></span> Default Response
An unexpected error response.

###### <span id="article-service-get-by-slug-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="article-service-index"></span> List articles (*ArticleService_Index*)

```
GET /api/v2/articles
```

List articles to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| limit | `query` | int32 (formatted integer) | `int32` |  |  |  |  |
| next | `query` | int32 (formatted integer) | `int32` |  |  |  |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#article-service-index-200) | OK | A successful response. |  | [schema](#article-service-index-200-schema) |
| [default](#article-service-index-default) | | An unexpected error response. |  | [schema](#article-service-index-default-schema) |

#### Responses


##### <span id="article-service-index-200"></span> 200 - A successful response.
Status: OK

###### <span id="article-service-index-200-schema"></span> Schema
   
  

[Protoarticlesv2IndexResponse](#protoarticlesv2-index-response)

##### <span id="article-service-index-default"></span> Default Response
An unexpected error response.

###### <span id="article-service-index-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="article-service-update"></span> Update an article (*ArticleService_Update*)

```
PUT /api/v2/articles
```

Update an article to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| body | `body` | [Protoarticlesv2UpdateRequest](#protoarticlesv2-update-request) | `models.Protoarticlesv2UpdateRequest` | | ✓ | |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#article-service-update-200) | OK | A successful response. |  | [schema](#article-service-update-200-schema) |
| [default](#article-service-update-default) | | An unexpected error response. |  | [schema](#article-service-update-default-schema) |

#### Responses


##### <span id="article-service-update-200"></span> 200 - A successful response.
Status: OK

###### <span id="article-service-update-200-schema"></span> Schema
   
  

[Protoarticlesv2UpdateResponse](#protoarticlesv2-update-response)

##### <span id="article-service-update-default"></span> Default Response
An unexpected error response.

###### <span id="article-service-update-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="media-service-create"></span> Add an media (*MediaService_Create*)

```
POST /api/v2/medias
```

Add an media  to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| body | `body` | [Protomediasv2CreateRequest](#protomediasv2-create-request) | `models.Protomediasv2CreateRequest` | | ✓ | |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#media-service-create-200) | OK | A successful response. |  | [schema](#media-service-create-200-schema) |
| [default](#media-service-create-default) | | An unexpected error response. |  | [schema](#media-service-create-default-schema) |

#### Responses


##### <span id="media-service-create-200"></span> 200 - A successful response.
Status: OK

###### <span id="media-service-create-200-schema"></span> Schema
   
  

[Protomediasv2CreateResponse](#protomediasv2-create-response)

##### <span id="media-service-create-default"></span> Default Response
An unexpected error response.

###### <span id="media-service-create-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

### <span id="media-service-delete"></span> Delete an media (*MediaService_Delete*)

```
DELETE /api/v2/medias/{id}
```

Delete an media to the server.

#### Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| id | `path` | int32 (formatted integer) | `int32` |  | ✓ |  |  |

#### All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#media-service-delete-200) | OK | A successful response. |  | [schema](#media-service-delete-200-schema) |
| [default](#media-service-delete-default) | | An unexpected error response. |  | [schema](#media-service-delete-default-schema) |

#### Responses


##### <span id="media-service-delete-200"></span> 200 - A successful response.
Status: OK

###### <span id="media-service-delete-200-schema"></span> Schema
   
  

[Protomediasv2DeleteResponse](#protomediasv2-delete-response)

##### <span id="media-service-delete-default"></span> Default Response
An unexpected error response.

###### <span id="media-service-delete-default-schema"></span> Schema

  

[RPCStatus](#rpc-status)

## Models

### <span id="index-request-joins"></span> IndexRequestJoins


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| categories | boolean| `bool` |  | |  |  |
| medias | boolean| `bool` |  | |  |  |



### <span id="media-custom-properties"></span> MediaCustomProperties


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| height | int32 (formatted integer)| `int32` |  | |  |  |
| width | int32 (formatted integer)| `int32` |  | |  |  |



### <span id="media-responsive"></span> MediaResponsive


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| base64Svg | string| `string` |  | |  |  |
| urls | []string| `[]string` |  | |  |  |



### <span id="media-responsive-images"></span> MediaResponsiveImages


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| responsive | [MediaResponsive](#media-responsive)| `MediaResponsive` |  | |  |  |



### <span id="protoalbumsv2-create-request"></span> protoalbumsv2CreateRequest


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| content | string| `string` |  | |  |  |
| metaDescription | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |
| private | boolean| `bool` |  | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |



### <span id="protoalbumsv2-create-response"></span> protoalbumsv2CreateResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| authorId | string| `string` |  | |  |  |
| content | string| `string` |  | |  |  |
| createdAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| id | int32 (formatted integer)| `int32` |  | |  |  |
| metaDescription | string| `string` |  | |  |  |
| notifyUsersOnPublished | boolean| `bool` |  | |  |  |
| private | boolean| `bool` |  | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |
| title | string| `string` |  | |  |  |
| updatedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| userId | int64 (formatted string)| `string` |  | |  |  |



### <span id="protoalbumsv2-delete-response"></span> protoalbumsv2DeleteResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| deleted | boolean| `bool` |  | |  |  |



### <span id="protoalbumsv2-get-by-slug-response"></span> protoalbumsv2GetBySlugResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| album | [V2AlbumResponse](#v2-album-response)| `V2AlbumResponse` |  | |  |  |



### <span id="protoalbumsv2-index-response"></span> protoalbumsv2IndexResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| data | [][V2AlbumResponse](#v2-album-response)| `[]*V2AlbumResponse` |  | |  |  |



### <span id="protoalbumsv2-update-response"></span> protoalbumsv2UpdateResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| authorId | string| `string` |  | |  |  |
| content | string| `string` |  | |  |  |
| createdAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| id | int32 (formatted integer)| `int32` |  | |  |  |
| metaDescription | string| `string` |  | |  |  |
| notifyUsersOnPublished | boolean| `bool` |  | |  |  |
| private | boolean| `bool` |  | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |
| title | string| `string` |  | |  |  |
| updatedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| userId | int64 (formatted string)| `string` |  | |  |  |



### <span id="protoarticlesv2-create-request"></span> protoarticlesv2CreateRequest


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| content | string| `string` | ✓ | |  |  |
| metaDescription | string| `string` | ✓ | |  |  |
| name | string| `string` | ✓ | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |



### <span id="protoarticlesv2-create-response"></span> protoarticlesv2CreateResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| authorId | string| `string` | ✓ | |  |  |
| content | string| `string` | ✓ | |  |  |
| id | int64 (formatted string)| `string` | ✓ | |  |  |
| metaDescription | string| `string` | ✓ | |  |  |
| name | string| `string` | ✓ | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` | ✓ | |  |  |



### <span id="protoarticlesv2-delete-response"></span> protoarticlesv2DeleteResponse


  

[interface{}](#interface)

### <span id="protoarticlesv2-get-by-slug-response"></span> protoarticlesv2GetBySlugResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| authorId | string| `string` |  | |  |  |
| content | string| `string` |  | |  |  |
| id | int64 (formatted string)| `string` |  | |  |  |
| metaDescription | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |



### <span id="protoarticlesv2-index-response"></span> protoarticlesv2IndexResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| data | [][V2ArticleResponse](#v2-article-response)| `[]*V2ArticleResponse` | ✓ | |  |  |



### <span id="protoarticlesv2-update-request"></span> protoarticlesv2UpdateRequest


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| content | string| `string` |  | |  |  |
| id | int64 (formatted string)| `string` |  | |  |  |
| metaDescription | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |



### <span id="protoarticlesv2-update-response"></span> protoarticlesv2UpdateResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| authorId | string| `string` |  | |  |  |
| content | string| `string` |  | |  |  |
| id | int64 (formatted string)| `string` |  | |  |  |
| metaDescription | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |



### <span id="protobuf-any"></span> protobufAny


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| @type | string| `string` |  | |  |  |



**Additional Properties**

any

### <span id="protomediasv2-create-request"></span> protomediasv2CreateRequest


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| fileNames | []string| `[]string` |  | |  |  |
| resourceID | int32 (formatted integer)| `int32` |  | |  |  |
| type | [V2MediasResourceType](#v2-medias-resource-type)| `V2MediasResourceType` |  | |  |  |



### <span id="protomediasv2-create-response"></span> protomediasv2CreateResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| FileUploadUrls | map of string| `map[string]string` |  | |  |  |



### <span id="protomediasv2-delete-response"></span> protomediasv2DeleteResponse


  

[interface{}](#interface)

### <span id="rpc-status"></span> rpcStatus


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| code | int32 (formatted integer)| `int32` |  | |  |  |
| details | [][ProtobufAny](#protobuf-any)| `[]*ProtobufAny` |  | |  |  |
| message | string| `string` |  | |  |  |



### <span id="v2-album-response"></span> v2AlbumResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| authorId | string| `string` |  | |  |  |
| categories | [][V2Category](#v2-category)| `[]*V2Category` |  | |  |  |
| content | string| `string` |  | |  |  |
| createdAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| id | int32 (formatted integer)| `int32` |  | |  |  |
| medias | [][V2Media](#v2-media)| `[]*V2Media` |  | |  |  |
| metaDescription | string| `string` |  | |  |  |
| notifyUsersOnPublished | boolean| `bool` |  | |  |  |
| private | boolean| `bool` |  | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` |  | |  |  |
| title | string| `string` |  | |  |  |
| updatedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| userId | int64 (formatted string)| `string` |  | |  |  |



### <span id="v2-article-response"></span> v2ArticleResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| authorId | string| `string` |  | |  |  |
| content | string| `string` | ✓ | |  |  |
| id | int64 (formatted string)| `string` | ✓ | |  |  |
| metaDescription | string| `string` | ✓ | |  |  |
| name | string| `string` | ✓ | |  |  |
| publishedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| slug | string| `string` | ✓ | |  |  |



### <span id="v2-category"></span> v2Category


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| id | int32 (formatted integer)| `int32` |  | |  |  |



### <span id="v2-media"></span> v2Media


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| createdAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |
| customProperties | [MediaCustomProperties](#media-custom-properties)| `MediaCustomProperties` |  | |  |  |
| fileName | string| `string` |  | |  |  |
| id | int32 (formatted integer)| `int32` |  | |  |  |
| mimeType | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |
| responsiveImages | [MediaResponsiveImages](#media-responsive-images)| `MediaResponsiveImages` |  | |  |  |
| size | int64 (formatted string)| `string` |  | |  |  |
| updatedAt | date-time (formatted string)| `strfmt.DateTime` |  | |  |  |



### <span id="v2-medias-resource-type"></span> v2MediasResourceType


  

| Name | Type | Go type | Default | Description | Example |
|------|------|---------| ------- |-------------|---------|
| v2MediasResourceType | string| string | `"RESOURCE_TYPE_UNSPECIFIED"`|  |  |


