<img{!! $attributeString !!} src="{{ $media->getUrl($conversion) }}" alt="{{ $media->model->title ?? $media->name }}">
{!! new \App\Http\SchemasOrg\MediaSchema($media) !!}
