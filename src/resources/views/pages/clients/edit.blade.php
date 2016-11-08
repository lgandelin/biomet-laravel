@extends('biomet::default')

@section('page-title') Editer un client @endsection

@section('page-content')
    <h1>Editer un client</h1>

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

    @include('biomet::pages.clients.form', [
        'form_action' => route('clients_update'),
        'client_id' => $client->id,
        'client_name' => $client->name,
    ])
@endsection