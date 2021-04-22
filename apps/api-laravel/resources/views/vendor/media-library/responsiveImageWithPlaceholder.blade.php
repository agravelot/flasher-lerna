<img{!! $attributeString !!} loading="{{ $loadingAttributeValue }}" class="responsive-media responsive-media-lazy" srcset="{{ $media->getSrcset($conversion) }}" sizes="1px" src="{{ $media->getUrl($conversion) }}" alt="{{ $media->model->title ?? $media->name }}" width="{{ $width }}">
{!! new \App\Http\SchemasOrg\MediaSchema($media) !!}
