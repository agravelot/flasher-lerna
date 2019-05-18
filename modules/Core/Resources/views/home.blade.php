@extends('layouts.app')

@section('pageTitle', 'Photographe')

@section('content')
    <section class="hero is-primary is-medium has-hero-background">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="columns is-centered is-vcentered">
                            <div class="column">
                                <figure class="image is-128x128 is-pulled-right">
                                    <img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png"
                                         alt="">
                                </figure>
                            </div>
                            <div class="column">
                                <h1 class="title">
                                    JKanda
                                </h1>
                                <p class="subtitle">
                                    Photographe passionnée
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="has-text-centered">
                <a href="{{ route('albums.index') }}">
                    <h2 class="title">Découvrez mes albums</h2>

                </a>
            </div>

            <div class="columns is-vcentered is-centered">
                @foreach($albums as $album)
                    <div class="column">
                        <a class="box" href="{{ route('albums.show', compact('album')) }}">
                            <figure class="image is-square">
                                {{ $album->cover }}
                            </figure>
                        </a>
                    </div>
                @endforeach
            </div>



        </div>
    </section>

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h2 class="title">Qui suis-je ?</h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec placerat non urna nec feugiat. Mauris
                    porta, dolor sed elementum placerat, purus enim tristique orci, eget gravida sapien arcu quis elit.
                    Interdum et malesuada fames ac ante ipsum primis in faucibus. Vestibulum interdum orci quis
                    pellentesque
                    pretium. Fusce orci risus, dignissim ac ex in, porta iaculis enim. Curabitur vestibulum nisl
                    pellentesque nunc posuere iaculis. Sed auctor pulvinar rhoncus.
                </p>
            </div>
            <section class="has-margin-top-lg">
                <h3 class="title is-5 has-text-centered">Souillez informé dès que je publie quelque chose de nouveau.</h3>
                <div class="field has-addons has-addons-centered has-margin-top-sm">
                    <div class="control">
                        <input class="input" type="text" placeholder="{{ __('Votre addresse email') }}">
                    </div>
                    <div class="control">
                        <a class="button is-info">
                            {{ __('Valider') }}
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </section>
@stop

@section('head')
    <style>
        .has-hero-background {
            background: url("https://images.unsplash.com/photo-1557952736-0084356ba2da?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80") center center;
            background-size: cover;
        }

        .box {
            padding: unset;
        }
    </style>
@endsection