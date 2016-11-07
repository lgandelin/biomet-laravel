@extends('biomet::master')

@section('page-title'){{ trans('biomet::dashboard.meta_title') }}@endsection

@section('main-content')
    <h1>Dashboard</h1>
    <a href="{{ route('logout') }}">Se déconnecter</a>

    <ul>
        <li><a href="{{ route('users') }}">Gestion des utilisateurs</a></li>
    </ul>
@endsection