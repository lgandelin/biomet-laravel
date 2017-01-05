@extends('biomet::default')

@section('page-title') Gestion des utilisateurs @endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">Gestion des utilisateurs</h1>
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

            <div class="filters" style="margin-top: 3rem; margin-bottom: 3rem;">
                <h4>Filtres</h4>
                <form action="">

                    <input type="text" class="form-control" name="filter_client_name" value="{{ $filter_client_name }}" placeholder="Recherche" style="width: 15%; display: inline;"/>

                    <select name="filter_profile_id" class="form-control" style="width: 15%; display: inline;">
                        <option value="">Filtrer par profil</option>
                        <option value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_ADMINISTRATOR }}" @if ($filter_profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_ADMINISTRATOR)selected="selected"@endif>Administrateur client</option>
                        <option value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_USER }}" @if ($filter_profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_USER)selected="selected"@endif>Utilisateur client</option>
                        <option value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_PROVIDER }}" @if ($filter_profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_PROVIDER)selected="selected"@endif>Prestataire</option>
                    </select>

                    <input class="btn btn-valid" type="submit" value="{{ trans('biomet::generic.valid') }}" />
                </form>
            </div>

            <h4>Liste des utilisateurs</h4>
            <table class="table table-stripped">
                <thead>
                <tr>
                    <th>{{ trans('biomet::users.name') }}</th>
                    <th>{{ trans('biomet::users.email') }}</th>
                    <th>{{ trans('biomet::users.profile') }}</th>
                    <th>{{ trans('biomet::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->last_name }} {{ $user->first_name }}</td>
                        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td>@if ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_PROVIDER)Prestataire
                            @elseif ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_USER)Utilisateur client
                            @elseif ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_ADMINISTRATOR)Administrateur client
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('client_users_edit', ['id' => $user->id]) }}"><i class="btn-edit glyphicon glyphicon-pencil"></i></a>
                            <a href="{{ route('client_users_delete', ['id' => $user->id]) }}"><i class="btn-remove glyphicon glyphicon-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-center">
                @include('biomet::includes.items_per_page')
                {{ $users->appends(['items_per_page' => $items_per_page])->links() }}
            </div>
        </div>
    </div>

    <a class="btn btn-valid pull-right" style="margin-top: 2rem" href="{{ route('client_users_add') }}">{{ trans('biomet::generic.add') }}</a>

@endsection
