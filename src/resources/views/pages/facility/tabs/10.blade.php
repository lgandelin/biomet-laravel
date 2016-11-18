@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }} - Maintenance</h1>

    @include('biomet::pages.facility.includes.menu')

    <div class="facility-template">

        {{ csrf_field() }}
        <input type="hidden" id="facility_id" value="{{ $current_facility->id }}" />

    </div>

@endsection