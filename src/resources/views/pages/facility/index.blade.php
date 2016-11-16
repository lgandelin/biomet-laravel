@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }}</h1>

    @include('biomet::pages.facility.includes.menu')

    <div class="facility-template">

        @if (isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif

        @if (isset($confirmation))
            <div class="alert alert-success">
                {{ $confirmation }}
            </div>
        @endif

        <a style="margin-top: 50px" class="btn btn-default" href="{{ route('dashboard') }}">{{ trans('biomet::generic.back') }}</a>
    </div>

@endsection