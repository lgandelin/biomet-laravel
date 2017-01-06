@extends('biomet::default')

@section('page-title') Gestion des sites @endsection

@section('page-content')
    <div class="box">
        <h1 class="box-title">Gestion des sites</h1>
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

                    <input class="btn btn-valid" type="submit" value="{{ trans('biomet::generic.valid') }}" />
                </form>
            </div>

            <h4>Liste des sites</h4>
            <table class="table table-stripped">
                <thead>
                <tr>
                    <th align="left">{{ trans('biomet::facilities.name') }}</th>
                    <th align="center">{{ trans('biomet::facilities.client') }}</th>
                    <th align="left">{{ trans('biomet::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($facilities as $facility)
                    <tr>
                        <td>{{ $facility->name }}</td>
                        <td>@if ($facility->client){{ $facility->client->name }}@else N/A @endif</td>
                        <td width="15%">
                            <a href="{{ route('facilities_edit', ['id' => $facility->id]) }}"><i class="btn-edit glyphicon glyphicon-pencil"></i></a>
                            <a href="{{ route('facilities_delete', ['id' => $facility->id]) }}"><i class="btn-remove glyphicon glyphicon-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center">
            @include('biomet::includes.items_per_page')
            {{ $facilities->appends(['items_per_page' => $items_per_page])->links() }}
        </div>
    </div>

    <a class="btn btn-valid pull-right" style="margin-top: 2rem" href="{{ route('facilities_add') }}">{{ trans('biomet::generic.add') }}</a>

@endsection
