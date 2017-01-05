@extends('biomet::default')

@section('page-title') Editer un client @endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">Editer un client</h1>
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

            @include('biomet::pages.clients.form', [
                'form_action' => route('clients_update'),
                'client_id' => $client->id,
                'client_name' => $client->name,
                'client_access_limit_date' => $client->access_limit_date ? DateTime::createFromFormat('Y-m-d', $client->access_limit_date)->format('d/m/Y') : null,
                'client_users_limit' => $client->users_limit,
            ])
        </div>
    </div>

@endsection