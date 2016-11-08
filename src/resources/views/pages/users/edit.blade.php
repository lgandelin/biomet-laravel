@extends('biomet::default')

@section('page-title') Editer un utilisateur @endsection

@section('page-content')
    <h1>Editer un utilisateur</h1>

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
        'form_action' => route('users_update'),
        'user_id' => $user->id,
        'user_first_name' => $user->first_name,
        'user_last_name' => $user->last_name,
        'user_email' => $user->email,
        'is_administrator' => $user->is_administrator,
    ])
@endsection