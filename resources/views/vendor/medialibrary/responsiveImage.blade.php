<img{!! $attributeString !!} srcset="{{ $media->getSrcset($conversion) }}" src="{{ $media->getUrl($conversion) }}" width="{{ $width }}" alt="{{ $media->model->title ?? $media->name }}">
{!! new \App\Http\SchemasOrg\MediaSchema($media) !!}
