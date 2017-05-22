@extends('biomet::default')

@section('page-title') Ajouter un intervention @endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">Ajouter une intervention</h1>
        <div class="box-content">
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
                'form_action' => route('interventions_store'),
                'facility_id' => $facility_id,
            ])
        </div>
    </div>

@endsection