@extends('admin.admin')

@section('admin-content')
    <section class="hero is-info welcome is-small has-margin-bottom-sm">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Hello, {{ Auth::user()->name }}.
                </h1>
                <h2 class="subtitle">
                    I hope you are having a great day!
                </h2>
            </div>
        </div>
    </section>
    <section class="info-tiles">
        <div class="tile is-ancestor has-text-centered">
            <a class="tile is-parent"
               @can('index', \App\Models\User::class) href="{{ route('admin.users.index') }}" @endcan>
                <article class="tile is-child box">
                    <p class="title">{{ $userCount }}</p>
                    <p class="subtitle">Users</p>
                </article>
            </a>
            <a class="tile is-parent"
               @can('index', \App\Models\Album::class) href="{{ route('admin.albums.index') }}" @endcan>
                <article class="tile is-child box">
                    <p class="title">{{ $albumCount }}</p>
                    <p class="subtitle">Albums</p>
                </article>
            </a>
            <a class="tile is-parent"
               @can('index', \App\Models\Cosplayer::class) href="{{ route('admin.cosplayers.index') }}" @endcan>
                <article class="tile is-child box">
                    <p class="title">{{ $cosplayerCount }}</p>
                    <p class="subtitle">Cosplayers</p>
                </article>
            </a>
            <a class="tile is-parent"
               @can('index', \App\Models\Contact::class) href="{{ route('admin.contacts.index') }}" @endcan>
                <article class="tile is-child box">
                    <p class="title">{{ $contactCount }}</p>
                    <p class="subtitle">Contacts</p>
                </article>
            </a>
        </div>
    </section>
    <div class="columns">
        <div class="column is-6">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        Events
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
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="far fa-bell"></i></td>
                                <td>Lorum ipsum dolem aire</td>
                                <td><a class="button is-small is-primary" href="#">Action</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="#" class="card-footer-item">View All</a>
                </footer>
            </div>
        </div>
        <div class="column is-6">
            <div class="card has-margin-bottom-sm">
                <header class="card-header">
                    <p class="card-header-title">
                        Inventory Search
                    </p>
                    <a href="#" class="card-header-icon" aria-label="more options">
                        <span class="icon">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </span>
                    </a>
                </header>
                <div class="card-content">
                    <div class="content">
                        <div class="control has-icons-left has-icons-right">
                            <input class="input is-large" type="text" placeholder="">
                            <span class="icon is-medium is-left">
                                <i class="fa fa-search"></i>
                            </span>
                            <span class="icon is-medium is-right">
                                <i class="fa fa-check"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card has-margin-bottom-sm">
                <header class="card-header">
                    <p class="card-header-title">
                        User Search
                    </p>
                    <a href="#" class="card-header-icon" aria-label="more options">
                        <span class="icon">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </span>
                    </a>
                </header>
                <div class="card-content">
                    <div class="content">
                        <div class="control has-icons-left has-icons-right">
                            <input class="input is-large" type="text" placeholder="">
                            <span class="icon is-medium is-left">
                                <i class="fa fa-search"></i>
                            </span>
                            <span class="icon is-medium is-right">
                                <i class="fa fa-check"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
