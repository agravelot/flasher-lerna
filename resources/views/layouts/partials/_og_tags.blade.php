{{--Namespace URI: http://ogp.me/ns/article#--}}


@section('head')
    @php
        /** @var \Modules\Album\Entities\Album $album */
    @endphp

    <meta property="og:title" content="{{ $album->title }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ request()->url() }}"/>

    <meta property="og:locale" content="{{ app()->getLocale() }}"/>
{{--    <meta property="og:locale:alternate" content="{{ app()->getFallbackLocale() }}" />--}}


    <meta property="article:published_time" content="{{ $album->published_at->toIso8601String() }}"/>
    <meta property="article:modified_time" content="{{ $album->updated_at->toIso8601String() }}"/>
    {{--<meta property="article:expiration_time" content="" />--}}
    <meta property="article:author" content="{{ $album->user->name }}"/>
    <meta property="article:section" content="Photography"/>
    @foreach($album->categories()->pluck('name') as $categoryName)
        <meta property="article:tag" content="{{ $categoryName }}"/>
    @endforeach

    @if ($album->cover)
        {{--Image--}}
        <meta property="og:image" content="{{ $album->cover->getUrl('thumb') }}"/>
        <meta property="og:image:secure_url" content="{{ $album->cover->getUrl('thumb') }}"/>
        <meta property="og:image:type" content="{{ $album->cover->mime_type }}"/>
        <meta property="og:image:width" content="{{ $album->cover->width }}"/>
        <meta property="og:image:height" content="{{ $album->cover->height }}"/>
        <meta property="og:image:alt" content="{{ $album->cover->name }}"/>
    @endif

@stop


