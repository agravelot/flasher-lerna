@extends('admin.admin')

@section('admin-content')
    <div class="container is-centered has-margin-top-md">

        <h1 class="title is-2 has-text-centered">{{ $contact->name }}</h1>

        <div class="column is-8 is-offset-2">
            <div class="card article">
                <div class="card-content">
                    <div class="media">
                        <div class="media-content has-text-centered">
                            <p class="title article-title">  {{-- {{ $contact->title }}--}}</p>
                            <div class="tags has-addons level-item">
                                <span class="tag is-rounded is-info">{{ $contact->email}}</span>
                                <span class="tag is-rounded">{{ $contact->created_at->toFormattedDateString() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="content article-body">
                        <p class="has-text-justified">{{ $contact->message }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
