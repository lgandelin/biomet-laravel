@extends('biomet::master')

@section('page-title')
    {{ trans('biomet::index.seo_title') }}
@endsection

@section('main-content')
    Index
    <a href="{{ route('logout') }}">Se déconnecter</a>
@endsection