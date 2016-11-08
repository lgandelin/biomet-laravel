@extends('biomet::default')

@section('page-title') Ajouter un client @endsection

@section('page-content')
    <h1>Ajouter un client</h1>

    @if (isset($error))
        <div class="bg-danger">
            {{ $error }}
        </div>
    @endif

    @if (isset($confirmation))
        <div class="bg-success">
            {{ $confirmation }}
        </div>
    @endif

    @include('biomet::pages.clients.form', [
        'form_action' => route('clients_store'),
    ])
@endsection