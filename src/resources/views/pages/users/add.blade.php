@extends('biomet::default')

@section('page-title') Ajouter un utilisateur @endsection

@section('page-content')
    <h1>Ajouter un utilisateur</h1>

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

    @include('biomet::pages.users.form', [
        'form_action' => route('users_store'),
    ])
@endsection