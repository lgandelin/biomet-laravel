@extends('biomet::default')

@section('page-title') Gestion des sites @endsection

@section('page-content')
    <h1>Gestion des sites</h1>

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

            <input class="btn btn-valid" type="submit" value="{{ trans('biomet::generic.valid') }}" />
        </form>
    </div>

    <h4>Liste des sites</h4>
    <table class="table table-stripped">
        <thead>
        <tr>
            <th>{{ trans('biomet::facilities.name') }}</th>
            <th>{{ trans('biomet::facilities.client') }}</th>
            <th>{{ trans('biomet::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($facilities as $facility)
            <tr>
                <td>{{ $facility->name }}</td>
                <td>@if ($facility->client){{ $facility->client->name }}@else N/A @endif</td>
                <td align="right">
                    <a class="btn btn-primary" href="{{ route('facilities_edit', ['id' => $facility->id]) }}">{{ trans('biomet::generic.edit') }}</a>
                    <a class="btn btn-danger" href="{{ route('facilities_delete', ['id' => $facility->id]) }}">{{ trans('biomet::generic.delete') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a class="btn btn-valid" href="{{ route('facilities_add') }}">{{ trans('biomet::generic.add') }}</a>

    <div class="text-center">
        @include('biomet::includes.items_per_page')
        {{ $facilities->appends(['items_per_page' => $items_per_page])->links() }}
    </div>

@endsection
