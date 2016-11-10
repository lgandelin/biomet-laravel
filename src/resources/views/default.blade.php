@extends('biomet::master')

@section('main-content')

    @include('biomet::includes.left_column')

    <div id="page-wrapper">
        <div class="container-fluid">
            @yield('page-content')
        </div>
    </div>

@endsection