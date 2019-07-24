@section('head_tag')
    prefix="og: http://ogp.me/ns#"
@stop

@section('head')
    <meta property="og:title" content="{{ $model->title() }}"/>
    <meta property="og:description" content="{{ $model->description() }}"/>
    <meta property="og:type" content="{{ $model->type() }}"/>
    <meta property="og:url" content="{{ request()->url() }}"/>

    <meta property="og:locale" content="{{ app()->getLocale() }}"/>
    {{--    <meta property="og:locale:alternate" content="{{ app()->getFallbackLocale() }}" />--}}

    @if ($model instanceof \App\Models\Contracts\ArticleOpenGraphable)
        <meta property="article:published_time" content="{{ $model->publishedAt() }}"/>
        <meta property="article:modified_time" content="{{ $model->updatedAt() }}"/>
        {{--<meta property="article:expiration_time" content="" />--}}
        <meta property="article:author" content="{{ $model->author() }}"/>
        <meta property="article:section" content="Photography"/>
        @foreach($model->tags() as $tag)
            <meta property="article:tag" content="{{ $tag }}"/>
        @endforeach
    @endif

    @if ($model instanceof \App\Models\Contracts\ImagesOpenGraphable)
        @foreach($model->images() as $image)
            <meta property="og:image" content="{{ $image->getUrl('thumb') }}"/>
            <meta property="og:image:secure_url" content="{{ $image->getUrl('thumb') }}"/>
            <meta property="og:image:type" content="{{ $image->mime_type }}"/>
            <meta property="og:image:width" content="{{ $image->width }}"/>
            <meta property="og:image:height" content="{{ $image->height }}"/>
            <meta property="og:image:alt" content="{{ $image->name }}"/>
        @endforeach
    @endif
@stop


