@section('head_tag')
    prefix="og: http://ogp.me/ns#"
@stop

@section('head')
    <meta property="og:title" content="{{ $openGraph->title() }}"/>
    <meta property="og:description" content="{{ $openGraph->description() }}"/>
    <meta property="og:type" content="{{ $openGraph->type() }}"/>
    <meta property="og:url" content="{{ request()->url() }}"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}"/>
    <meta name="twitter:card" content="{{ $openGraph->title() }}" />
    <meta name="twitter:site" content="@jujunne_kanda" />
    <meta name="twitter:creator" content="@jujunne_kanda" />

    @if ($openGraph instanceof \App\Http\OpenGraphs\Contracts\ProfileOpenGraphable)
        <meta property="profile:username" content="{{ $openGraph->username() }}">
        <meta property="profile:first_name" content="{{ $openGraph->firstName() }}">
        <meta property="profile:last_name" content="{{ $openGraph->lastName() }}">
        <meta property="profile:gender" content="{{ $openGraph->gender() }}">
    @endif

    @if ($openGraph instanceof \App\Http\OpenGraphs\Contracts\ArticleOpenGraphable)
        <meta property="article:published_time" content="{{ $openGraph->publishedAt() }}"/>
        <meta property="article:modified_time" content="{{ $openGraph->updatedAt() }}"/>
        {{--<meta property="article:expiration_time" content="" />--}}
        <meta property="article:author" content="{{ $openGraph->author() }}"/>
        <meta property="article:section" content="Photography"/>
        @foreach($openGraph->tags() as $tag)
            <meta property="article:tag" content="{{ $tag }}"/>
        @endforeach
    @endif

    @if ($openGraph instanceof \App\Http\OpenGraphs\Contracts\ImagesOpenGraphable)
        @foreach($openGraph->images() as $image)
            <meta property="og:image" content="{{ $image->getUrl('thumb') }}"/>
            <meta property="og:image:secure_url" content="{{ $image->getUrl('thumb') }}"/>
            <meta property="og:image:type" content="{{ $image->mime_type }}"/>
            <meta property="og:image:width" content="{{ $image->width }}"/>
            <meta property="og:image:height" content="{{ $image->height }}"/>
            <meta property="og:image:alt" content="{{ $openGraph->title() ?? $image->name }}"/>
        @endforeach
    @endif
@stop


