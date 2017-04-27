@extends('biomet::default')

@section('page-title') Gestion des utilisateurs @endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">Gestion des utilisateurs</h1>
        <div class="box-content" style="overflow: hidden;">
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

            <div class="filters">
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
                        <option value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_AROL_ENERGY_ADMINISTRATOR }}" @if ($filter_profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_AROL_ENERGY_ADMINISTRATOR)selected="selected"@endif>Administrateur Arol Energy</option>
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
                    <th><a href="{{ route('users', ['order_by' => 'last_name', 'order' => ($order == 'asc') ? 'desc' : 'asc']) }}" title="Trier par nom">{{ trans('biomet::users.name') }}</a></th>
                    <th><a href="{{ route('users', ['order_by' => 'email', 'order' => ($order == 'asc') ? 'desc' : 'asc']) }}" title="Trier par email">{{ trans('biomet::users.email') }}</a></th>
                    <th><a href="{{ route('users', ['order_by' => 'client_id', 'order' => ($order == 'asc') ? 'desc' : 'asc']) }}" title="Trier par client">{{ trans('biomet::users.client') }}</a></th>
                    <th><a href="{{ route('users', ['order_by' => 'profile_id', 'order' => ($order == 'asc') ? 'desc' : 'asc']) }}" title="Trier par nom">{{ trans('biomet::users.profile') }}</a></th>
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
                            @elseif ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_USER)Utilisateur client
                            @elseif ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_ADMINISTRATOR)Administrateur client
                            @elseif ($user->profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_AROL_ENERGY_ADMINISTRATOR)Administrateur Arol Energy
                            @endif
                        </td>
                        <td width="15%">
                            <a href="{{ route('users_edit', ['id' => $user->id]) }}"><i class="btn-edit glyphicon glyphicon-pencil"></i></a>
                            <a href="{{ route('users_delete', ['id' => $user->id]) }}"><i class="btn-remove glyphicon glyphicon-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center">
            @include('biomet::includes.items_per_page')
            {{ $users->appends(['filter_client_name' => $filter_client_name, 'filter_client_id' => $filter_client_id, 'filter_profile_id' => $filter_profile_id, 'items_per_page' => $items_per_page, 'order' => $order, 'order_by' => $order_by])->links() }}
        </div>
    </div>

    <a class="btn btn-valid pull-right" style="margin-top: 2rem" href="{{ route('users_add') }}">{{ trans('biomet::generic.add') }}</a>

@endsection
