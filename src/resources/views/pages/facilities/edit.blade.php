@extends('biomet::default')

@section('page-title') Editer un site @endsection

@section('page-content')
    <h1>Editer un site</h1>

    @if (isset($error))
        <div class="info bg-danger">
            {{ $error }}
        </div>
    @endif

    @if (isset($confirmation))
        <div class="info bg-success">
            {{ $confirmation }}
        </div>
    @endif

    @include('biomet::pages.facilities.form', [
        'form_action' => route('facilities_update'),
        'facility_id' => $facility->id,
        'facility_name' => $facility->name,
        'facility_latitude' => $facility->latitude,
        'facility_longitude' => $facility->longitude,
        'facility_address' => $facility->address,
        'facility_city' => $facility->city,
        'facility_department' => $facility->department,
        'facility_client_id' => $facility->client_id,
    ])
@endsection