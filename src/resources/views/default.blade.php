@extends('biomet::master')

@section('main-content')
    <a href="{{ route('logout') }}">Se déconnecter</a>

    @yield('page-content')

    <ul>
        <li><a href="{{ route('users') }}">Gestion des utilisateurs</a></li>
        <li><a href="{{ route('clients') }}">Gestion des clients</a></li>
        <li><a href="{{ route('facilities') }}">Gestion des sites</a></li>
    </ul>
@endsection