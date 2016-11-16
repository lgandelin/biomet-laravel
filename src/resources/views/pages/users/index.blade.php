@extends('biomet::default')

@section('page-title') Gestion des utilisateurs @endsection

@section('page-content')

    <h1>Gestion des utilisateurs</h1>

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

    <div class="filters" style="margin-top: 3rem; margin-bottom: 3rem;">
        <h4>Filtres</h4>
        <form action="">

            <input type="text" class="form-control" name="filter_client_name" value="{{ $filter_client_name }}" placeholder="Recherche" style="width: 15%; display: inline;"/>

            <select name="filter_client_id" class="form-control" style="width: 15%; display: inline;">
                <option value="">Filtrer par client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @if ($filter_client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
                @endforeach
            </select>

            <select name="filter_profile_id" class="form-control" style="width: 15%; display: inline;">
                <option value="">Filtrer par profil</option>
                <option value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_ADMINISTRATOR }}" @if ($filter_profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_ADMINISTRATOR)selected="selected"@endif>Administrateur</option>
                <option value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT }}" @if ($filter_profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT)selected="selected"@endif>Utilisateur client</option>
                <option value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_ADMINISTRATOR }}" @if ($filter_profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_ADMINISTRATOR)selected="selected"@endif>Prestataire</option>
            </select>

            <input class="btn btn-success" type="submit" value="{{ trans('biomet::generic.valid') }}" />
        </form>
    </div>

    <h4>Liste des utilisateurs</h4>
    <table class="table table-stripped">
        <thead>
        <tr>
            <th>{{ trans('biomet::users.name') }}</th>
            <th>{{ trans('biomet::users.email') }}</th>
            <th>{{ trans('biomet::users.client') }}</th>
            <th>{{ trans('biomet::users.profile') }}</th>
            <th>{{ trans('biomet::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->last_name }} {{ $user->first_name }}</td>
                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                <td>@if ($user->client){{ $user->client->name }}@else N/A @endif</td>
                <td>@if ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_PROVIDER)Prestataire
                    @elseif ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT)Utilisateur client
                    @elseif ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_ADMINISTRATOR)Administrateur
                    @endif
                </td>
                <td align="right">
                    <a class="btn btn-primary" href="{{ route('users_edit', ['id' => $user->id]) }}">{{ trans('biomet::generic.edit') }}</a>
                    <a class="btn btn-danger" href="{{ route('users_delete', ['id' => $user->id]) }}">{{ trans('biomet::generic.delete') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a class="btn btn-success" href="{{ route('users_add') }}">{{ trans('biomet::generic.add') }}</a>

    <div class="text-center">
        {!! $users->render() !!}
    </div>
    
@endsection