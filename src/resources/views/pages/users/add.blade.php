@extends('biomet::default')

@section('page-title') Ajouter un utilisateur @endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">Ajouter un utilisateur</h1>
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

            @include('biomet::pages.users.form', [
                'form_action' => route('users_store'),
            ])
        </div>
    </div>

@endsection