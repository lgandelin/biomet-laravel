@extends('biomet::master')

@section('main-content')

    @include('biomet::includes.navigation')
    @include('biomet::includes.left_column')

    <div class="container-fluid">
        @yield('page-content')
    </div>
@endsection