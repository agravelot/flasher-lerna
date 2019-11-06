<img{!! $attributeString !!} src="{{ $media->getUrl($conversion) }}" alt="{{ $media->name }}">
{!! new \App\Http\Schemas\MediaSchema($media) !!}
