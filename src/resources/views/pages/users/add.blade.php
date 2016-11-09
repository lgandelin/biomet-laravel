@extends('biomet::default')

@section('page-title') Ajouter un utilisateur @endsection

@section('page-content')
    <h1>Ajouter un utilisateur</h1>

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

    @include('biomet::pages.users.form', [
        'form_action' => route('users_store'),
    ])
@endsection