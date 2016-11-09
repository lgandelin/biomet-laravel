@extends('biomet::default')

@section('page-title') {{ $facility->name }} @endsection

@section('page-content')
    <h1>{{ $facility->name }}</h1>

    <a href="{{ route('dashboard') }}">{{ trans('biomet::generic.back') }}</a>

@endsection