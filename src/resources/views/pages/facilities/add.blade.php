@extends('biomet::default')

@section('page-title') Ajouter un site @endsection

@section('page-content')
    <h1>Ajouter un site</h1>

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
        'form_action' => route('facilities_store'),
    ])
@endsection