@extends('biomet::master')

@section('main-content')

    <ul>
        <li><a href="{{ route('dashboard') }}">Tableau de bord</a></li>

        @if (Auth::user() && Auth::user()->is_administrator)
            <li>
                Administration
                <ul>
                    <li><a href="{{ route('users') }}">Gestion des utilisateurs</a></li>
                    <li><a href="{{ route('clients') }}">Gestion des clients</a></li>
                    <li><a href="{{ route('facilities') }}">Gestion des sites</a></li>
                </ul>
            </li>
        @endif

        <li><a href="{{ route('logout') }}">Se déconnecter</a></li>
    </ul>

    @yield('page-content')

@endsection