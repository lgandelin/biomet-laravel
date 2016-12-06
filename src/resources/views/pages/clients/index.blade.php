@extends('biomet::default')

@section('page-title') Gestion des clients @endsection

@section('page-content')
    <h1>Gestion des clients</h1>

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
            <th>{{ trans('biomet::clients.name') }}</th>
            <th>{{ trans('biomet::clients.access_limit_date') }}</th>
            <th>{{ trans('biomet::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($clients as $client)
            <tr>
                <td>{{ $client->name }}</td>
                <td>@if ($client->access_limit_date){{ date('d/m/Y', strtotime($client->access_limit_date)) }}@endif</td>
                <td align="right">
                    <a class="btn btn-primary" href="{{ route('clients_edit', ['id' => $client->id]) }}">{{ trans('biomet::generic.edit') }}</a>
                    <a class="btn btn-danger" href="{{ route('clients_delete', ['id' => $client->id]) }}">{{ trans('biomet::generic.delete') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a class="btn btn-success" href="{{ route('clients_add') }}">{{ trans('biomet::generic.add') }}</a>

    <div class="text-center">
        @include('biomet::includes.items_per_page')
        {{ $clients->appends(['items_per_page' => $items_per_page])->links() }}
    </div>

@endsection
