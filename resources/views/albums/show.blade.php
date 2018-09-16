@extends("layouts.app")

@section("content")
    @include('albums.partials._show_item', ['album' => $album])
@endsection

{{--@section('js')--}}
    {{--<script src="{{ mix('js/carousel.js') }}"></script>--}}
{{--@endsection--}}
