@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }} - Fichiers de données</h1>

    @include('biomet::pages.facility.includes.menu')

    <div class="facility-template">

        <div style="margin-bottom: 3rem;">
            <h4>Regroupement de fichiers</h4>

            <form action="{{ route('facility_group_excel') }}" method="post">
                <strong>Date de début :</strong> <input id="start_date" name="start_date" class="datepicker form-control" value="@if (isset($start_date)){{$start_date}}@else{{ date('d/m/Y', strtotime( '-1 days' )) }}@endif" style="display: inline; width:175px; margin-bottom: 1rem; margin-right: 2.5rem;"/>
                <strong>Date de fin :</strong> <input id="end_date" name="end_date" class="datepicker form-control" value="@if (isset($end_date)){{$end_date}}@else{{ date('d/m/Y', strtotime( '-1 days' )) }}@endif" style="display: inline; width:175px; margin-bottom: 1rem;"/>
                <input type="submit" class="btn btn-success" value="{{ trans('biomet::generic.valid') }}" />
                {{ csrf_field() }}
                <input type="hidden" name="facility_id" id="facility_id" value="{{ $current_facility->id }}" />
            </form>
        </div>

        <div>
            <h4>Téléchargement des fichiers</h4>
            <ul>
                @foreach ($data['entries'] as $entry)
                    @if ($entry['type'] == 'folder')
                        <li>[Dossier] <a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 11, 'query_string' => $data['query_string'] . (($data['query_string'] == '') ? '' : '/') . $entry['name']]) }}">{{ $entry['name'] }}</a></li>
                    @elseif ($entry['type'] == 'file')
                        <li>[Fichier] <a href="{{ $entry['link'] }}" class="download">Télécharger</a></li>
                    @endif
                @endforeach
            </ul>
        </div>

        <a style="clear:both; margin-top: 10px" class="btn btn-default" href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 11, 'query_string' => dirname($data['query_string'])]) }}">{{ trans('biomet::generic.back') }}</a>

    </div>

@endsection