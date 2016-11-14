@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }}</h1>

    <div class="facility-template">

        @include('biomet::pages.facility.menu')

        <a style="margin-top: 50px" class="btn btn-default" href="{{ route('dashboard') }}">{{ trans('biomet::generic.back') }}</a>

    </div>

@endsection