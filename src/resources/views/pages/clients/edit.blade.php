@extends('biomet::default')

@section('page-title') Editer un client @endsection

@section('page-content')
    <h1>Editer un client</h1>

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

    @include('biomet::pages.clients.form', [
        'form_action' => route('clients_update'),
        'client_id' => $client->id,
        'client_name' => $client->name,
        'client_access_limit_date' => $client->access_limit_date,
    ])
@endsection