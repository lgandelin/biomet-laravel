@extends('biomet::default')

@section('page-title') Gestion des clients @endsection

@section('page-content')
    <div class="box">
        <h1 class="box-title">Gestion des clients</h1>
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

            <table class="table table-stripped">
                <thead>
                <tr>
                    <th><a href="{{ route('clients', ['order_by' => 'name', 'order' => ($order == 'asc') ? 'desc' : 'asc']) }}" title="Trier par nom">{{ trans('biomet::clients.name') }}</a></th>
                    <th>{{ trans('biomet::clients.access_limit_date') }}</th>
                    <th>{{ trans('biomet::clients.users_limit') }}</th>
                    <th>{{ trans('biomet::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>@if ($client->access_limit_date){{ date('d/m/Y', strtotime($client->access_limit_date)) }}@endif</td>
                        <td>{{ $client->users_limit }}</td>
                        <td width="15%">
                            <a href="{{ route('clients_edit', ['id' => $client->id]) }}"><i class="btn-edit glyphicon glyphicon-pencil"></i></a>
                            <a href="{{ route('clients_delete', ['id' => $client->id]) }}"><i class="btn-remove glyphicon glyphicon-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center">
            @include('biomet::includes.items_per_page')
            {{ $clients->appends(['items_per_page' => $items_per_page])->links() }}
        </div>
    </div>

    <a class="btn btn-valid pull-right" style="margin-top: 2rem" href="{{ route('clients_add') }}">{{ trans('biomet::generic.add') }}</a>
@endsection
