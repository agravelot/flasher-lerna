@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns">

            @include('admin.partials._menu_list')

            <div class="column is-9">
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

                                        @foreach($contacts as $contact)
                                            <tr>
                                                <td width="5%">
                                                    <i class="far fa-images"></i>
                                                    {{ $contact->name }}
                                                    {{ $contact->email }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('contact.show', ['album' => $contact]) }}">{{ $contact->message }}</a>
                                                </td>
                                                <td><a class="button is-small is-primary"
                                                       href="{{ route('contact.edit', ['album' => $contact]) }}">Update</a>
                                                <td>
                                                    <form action="{{ route('contact.destroy', ['album' => $contact]) }}"
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
