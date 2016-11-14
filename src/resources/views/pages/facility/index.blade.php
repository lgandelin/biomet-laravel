@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }}</h1>

    <a href="{{ route('dashboard') }}">{{ trans('biomet::generic.back') }}</a>

@endsection