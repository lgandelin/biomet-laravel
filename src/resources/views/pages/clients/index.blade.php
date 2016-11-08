@extends('biomet::default')

@section('page-title') Gestion des clients @endsection

@section('page-content')
    <h1>Gestion des clients</h1>

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
            <th>{{ trans('biomet::clients.name') }}</th>
            <th>{{ trans('biomet::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($clients as $client)
            <tr>
                <td>{{ $client->name }}</td>
                <td align="right">
                    <a href="{{ route('clients_edit', ['id' => $client->id]) }}">{{ trans('biomet::generic.edit') }}</a>
                    <a href="{{ route('clients_delete', ['id' => $client->id]) }}">{{ trans('biomet::generic.delete') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('clients_add') }}">{{ trans('biomet::generic.add') }}</a>

    <div class="text-center">
        {!! $clients->render() !!}
    </div>
    
@endsection