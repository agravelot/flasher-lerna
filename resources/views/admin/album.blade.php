@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-3">
                <aside class="menu">
                    <p class="menu-label">
                        General
                    </p>
                    <ul class="menu-list">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin_albums') }}" class="is-active">Albums</a></li>
                    </ul>
                </aside>
            </div>
            <div class="column is-9">
                <nav class="breadcrumb" aria-label="breadcrumbs">
                    <ul>
                        <li><a href="../">Bulma</a></li>
                        <li><a href="../">Templates</a></li>
                        <li><a href="../">Examples</a></li>
                        <li class="is-active"><a href="#" aria-current="page">Admin</a></li>
                    </ul>
                </nav>
                <div class="columns">
                    <div class="column">
                        <div class="card events-card">
                            <header class="card-header">
                                <p class="card-header-title">
                                    Albums
                                </p>
                                <a class="button" href="{{ route('albums.create') }}">Créer</a>
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

                                        @foreach($albums as $album)
                                            <tr>
                                                <td width="5%">
                                                    <i class="far fa-images"></i>
                                                    {{ $album->pictures->count() }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('albums.show', ['album' => $album]) }}">{{ $album->title }}</a>
                                                </td>
                                                <td><a class="button is-small is-primary"
                                                       href="{{ route('albums.edit', ['album' => $album]) }}">Update</a>
                                                <td>
                                                    <form action="{{ route('albums.destroy', ['album' => $album]) }}"
                                                          method="POST">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button class="button is-small is-danger">Delete</button>
                                                    </form>


                                                </td>
                                            </tr>
                                        @endforeach

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
