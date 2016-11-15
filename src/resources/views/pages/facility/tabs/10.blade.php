@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }} - Maintenance</h1>

    @include('biomet::pages.facility.menu')

    <div class="facility-template">

        <a style="margin-top: 50px" class="btn btn-default" href="{{ route('dashboard') }}">{{ trans('biomet::generic.back') }}</a>

        {{ csrf_field() }}
    </div>

@endsection