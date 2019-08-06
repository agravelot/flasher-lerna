@extends('admin.admin')

@section('pageTitle', __('Contact me'))

@section('admin-content')
    <div class="columns">
        <div class="column">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        Golden book posts
                    </p>
                </header>
                <div class="card-table">
                    <div class="content">
                        <table class="table is-hoverable is-striped">
                            <tbody>
                            @each('admin.testimonials._goldenbook_item', $goldenBookPosts, 'goldenBookPost', 'layouts.partials._empty')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @foreach($goldenBookPosts as $goldenBookPost)
                @include('admin.partials._modal_confirm_delete', ['key' => $goldenBookPost->id, 'route' => route('admin.testimonials.destroy', compact('goldenBookPost'))])
            @endforeach
            {{ $goldenBookPosts->links() }}
        </div>
    </div>
@endsection
