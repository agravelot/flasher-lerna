@extends('layouts.app')

@section('content')
    <div class="container is-centered">
        @include('layouts.partials._messages')
        <a href="{{ route('goldenbook.create') }}" class="button has-margin-sm">
            Write
        </a>

        <div class="">
            @each('goldenbook._goldenbook_item', $goldenBooksPosts, 'goldenBookPost', 'layouts.partials._empty')
        </div>
    </div>
@endsection
