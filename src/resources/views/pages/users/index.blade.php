@extends('biomet::default')

@section('page-title') Gestion des utilisateurs @endsection

@section('page-content')
    <h1>Gestion des utilisateurs</h1>

    @if (isset($error))
        <div class="info bg-danger">
            {{ $error }}
        </div>
    @endif

    @if (isset($confirmation))
        <div class="info bg-success">
            {{ $confirmation }}
        </div>
    @endif

    <table>
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
                <td>{{ $user->client->name }}</td>
                <td>@if ($user->is_administrator) Oui @else Non @endif</td>
                <td align="right">
                    <a href="{{ route('users_edit', ['id' => $user->id]) }}">{{ trans('biomet::generic.edit') }}</a>
                    <a href="{{ route('users_delete', ['id' => $user->id]) }}">{{ trans('biomet::generic.delete') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('users_add') }}">{{ trans('biomet::generic.add') }}</a>

    <div class="text-center">
        {!! $users->render() !!}
    </div>
    
@endsection