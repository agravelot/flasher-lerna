@extends('layouts.app')

@section('pageTitle', $category->name . ' - ' . __('Category'))

@section('seo_description', (new App\Http\OpenGraphs\CategoryOpenGraph($category))->description())

@section('content')
    <div class="hero is-black is-radiusless">
        <div class="hero-body">
            <div class="columns is-mobile is-centered">
                <div class="column is-narrow field has-addons">
                    @can('update', $category)
                        <div class="control">
                            <a class="button is-black" href="{{ config('app.admin_url')."/admin/categories/{$category->slug}" }}">
                                <span class="icon is-small">
                                    <x-fa name="edit" library="solid"/>
                                </span>
                                <span>{{ __('Edit') }}</span>
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container is-centered">

            @if ($category->description)
                <div class="card has-margin-bottom-md">
                    <div class="card-content">
                        <p class="has-text-justified has-margin-bottom-md">{!! $category->description !!}</p>
                    </div>
                </div>
            @endif

            @php
                /** @var App\Models\Category $category */
                $albums = $category->load(['publishedAlbums.media', 'publishedAlbums.categories'])->publishedAlbums()->paginate();
            @endphp
            @include('albums.partials._index_item', compact('albums'))
        </div>
    </section>
@endsection

@include('layouts.partials._og_tags', ['openGraph' => new App\Http\OpenGraphs\CategoryOpenGraph($category)])
