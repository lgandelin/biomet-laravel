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

    <table class="table table-stripped">
        <thead>
        <tr>
            <th>{{ trans('biomet::users.name') }}</th>
            <th>{{ trans('biomet::users.email') }}</th>
            <th>{{ trans('biomet::users.client') }}</th>
            <th>{{ trans('biomet::users.administrator') }}</th>
            <th>{{ trans('biomet::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->last_name }} {{ $user->first_name }}</td>
                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                <td>@if ($user->client){{ $user->client->name }}@else N/A @endif</td>
                <td>@if ($user->is_administrator) Oui @else Non @endif</td>
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