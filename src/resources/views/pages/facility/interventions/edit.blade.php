@extends('biomet::default')

@section('page-title') Editer une intervention @endsection

@section('page-content')
    <h1>Editer une intervention</h1>

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

    @include('biomet::pages.facility.interventions.form', [
        'form_action' => route('interventions_update'),
        'intervention_id' => $intervention->id,
        'facility_id' => $intervention->facility_id,
        'intervention_event_date' => $intervention->event_date,
        'intervention_title' => $intervention->title,
        'intervention_personal_information' => $intervention->personal_information,
        'intervention_description' => $intervention->description,
    ])
@endsection