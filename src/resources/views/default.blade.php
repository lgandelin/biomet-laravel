@extends('biomet::master')

@section('main-content')

    @include('biomet::includes.navigation')

    @include('biomet::includes.left_column')

    <div class="main-content">
        @yield('page-content')
    </div>

@endsection