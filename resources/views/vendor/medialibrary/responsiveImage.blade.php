<img{!! $attributeString !!} srcset="{{ $media->getSrcset($conversion) }}" src="{{ $media->getUrl($conversion) }}" width="{{ $width }}">
{!! new \App\Http\Schemas\MediaSchema($media) !!}
