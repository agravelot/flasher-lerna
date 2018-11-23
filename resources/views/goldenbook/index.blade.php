@extends('layouts.app')

@section('content')
   <div class="container">
       @include('layouts.partials._messages')
       <a href="{{ route('goldenbook.create') }}" class="button is-primary is-outlined is-medium has-margin-md">
           Write
       </a>

       @each('goldenbook._goldenbook_item', $goldenBooksPosts, 'goldenBookPost', 'layouts.partials._empty')
   </div>
@endsection
