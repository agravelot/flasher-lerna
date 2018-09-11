@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns">

            @include('admin.partials._menu_list')

            <div class="column is-9">

                @include('layouts.partials._messages')

                <div class="columns">
                    <div class="column">
                        <div class="card events-card">
                            <header class="card-header">
                                <p class="card-header-title">
                                    Albums
                                    <a href="{{ route('albums.create') }}">
                                    <span class="icon">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                        <span>Ajouter</span>
                                    </a>
                                </p>

                                <a href="#" class="card-header-icon" aria-label="more options">
                                  <span class="icon">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                  </span>
                                </a>
                            </header>
                            <div class="card-table">
                                <div class="content">
                                    <table class="table is-fullwidth is-striped">
                                        <tbody>

                                        @each('admin.partials._album_item', $albums, 'album', 'admin.partials._empty')

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <footer class="card-footer">
                                <a href="#" class="card-footer-item">View All</a>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
