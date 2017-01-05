@extends('biomet::default')

@section('page-title') Editer un utilisateur @endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">Editer un utilisateur</h1>
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

            @include('biomet::pages.client_users.form', [
                'form_action' => route('client_users_update'),
                'user_id' => $user->id,
                'user_first_name' => $user->first_name,
                'user_last_name' => $user->last_name,
                'user_email' => $user->email,
                'user_profile_id' => $user->profile_id,
            ])
        </div>
    </div>

@endsection