@extends('admin.admin')

@section('admin-content')
    <div class="columns">
        <div class="column">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">Contacts</p>
                    <a href="#" class="card-header-icon" aria-label="more options">
                        <span class="icon">
                            <i class="fas fa-angle-down" aria-hidden="true"></i>
                        </span>
                    </a>
                </header>
                <div class="card-table">
                    <div class="content">
                        <table class="table is-fullwidth is-striped">
                            <tbody>
                            @each('admin.contacts._contact_item', $contacts, 'contact', 'layouts.partials._empty')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @foreach($contacts as $contact)
                @include('admin.partials._modal_confirm_delete', ['key' => $contact->id, 'route' => route('admin.contacts.destroy', compact('contact'))])
            @endforeach
            {{ $contacts->links() }}
        </div>
    </div>
@endsection
