@extends('biomet::master')

@section('main-content')
    <a href="{{ route('logout') }}">Se déconnecter</a>

    @yield('page-content')

    <ul>
        <li><a href="{{ route('users') }}">Gestion des utilisateurs</a></li>
    </ul>
@endsection