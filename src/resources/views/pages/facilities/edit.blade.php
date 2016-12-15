@extends('biomet::default')

@section('page-title') Editer un site @endsection

@section('page-content')
    <h1>Editer un site</h1>

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
        'facility_country' => $facility->country,
        'facility_technology' => $facility->technology,
        'facility_serial_number' => $facility->serial_number,
        'facility_startup_date' => $facility->startup_date ? DateTime::createFromFormat('Y-m-d', $facility->startup_date)->format('d/m/Y') : null,
    ])
@endsection
