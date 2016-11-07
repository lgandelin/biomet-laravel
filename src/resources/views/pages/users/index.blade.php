@extends('biomet::master')

@section('page-title'){{ trans('biomet::users.meta_title') }}@endsection

@section('main-content')
    <h1>Gestion des utilisateurs</h1>
    <a href="{{ route('logout') }}">Se déconnecter</a>
@endsection