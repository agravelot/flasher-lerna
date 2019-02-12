@extends('admin.admin')

@section('admin-content')
    <div class="card">
        @include('admin.categories._feilds', [
            'route' => route('admin.categories.update', compact('category')),
             'category' => $category,
        ])
    </div>
@endsection