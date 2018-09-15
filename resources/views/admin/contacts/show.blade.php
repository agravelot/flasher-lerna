@extends("layouts.app")

@section("content")
    <div class="container is-centered">

        <h1 class="title is-2 has-text-centered">{{ $contact->name }}</h1>

        <div class="column is-8 is-offset-2">
            <!-- START ARTICLE -->
            <div class="card article">
                <div class="card-content">
                    <div class="media">
                        <div class="media-content has-text-centered">
                            <p class="title article-title">  {{-- {{ $contact->title }}--}}</p>
                            <div class="tags has-addons level-item">
                                <span class="tag is-rounded is-info">{{ $contact->email}}</span>
                                <span class="tag is-rounded">{{ $contact->message }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="content article-body">
                        <p class="has-text-justified"></p>
                    </div>
                </div>
            </div>
            <!-- END ARTICLE -->
        </div>

    </div>
@endsection
