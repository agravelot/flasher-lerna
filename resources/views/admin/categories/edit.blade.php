@extends('admin.admin')

@section('admin-content')
    <div class="card">
        @include('admin.categories._feilds', ['route' => route('admin.categories.index') , 'category' => $category])
    </div>
@endsection