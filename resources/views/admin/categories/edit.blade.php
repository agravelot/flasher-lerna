@extends('admin.admin')

@section('admin-content')
    <div class="container">
        <div class="card-content">
            @include('admin.categories._feilds', ['route' => route('admin.categories.index') , 'category' => $category])
        </div>
    </div>
@endsection