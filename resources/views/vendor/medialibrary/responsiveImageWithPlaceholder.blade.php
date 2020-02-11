<img{!! $attributeString !!} class="responsive-media" srcset="{{ $media->getSrcset($conversion) }}" sizes="1px" src="{{ $media->getUrl($conversion) }}" alt="{{ $media->name }}" width="{{ $width }}">
{!! new \App\Http\SchemasOrg\MediaSchema($media) !!}
