@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }} - Fichiers de données</h1>

    @include('biomet::pages.facility.includes.menu')

    <div class="facility-template">

        <ul>
            @foreach ($data['entries'] as $entry)
                @if ($entry['type'] == 'folder')
                    <li>[Dossier] <a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 11, 'query_string' => $data['query_string'] . (($data['query_string'] == '') ? '' : '/') . $entry['name']]) }}">{{ $entry['name'] }}</a></li>
                @elseif ($entry['type'] == 'file')
                    <li>[Fichier] <a href="{{ $entry['link'] }}" class="download">Télécharger</a></li>
                @endif
            @endforeach
        </ul>

        <a style="margin-top: 50px" class="btn btn-default" href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 11, 'query_string' => dirname($data['query_string'])]) }}">{{ trans('biomet::generic.back') }}</a>

        {{ csrf_field() }}
        <input type="hidden" id="facility_id" value="{{ $current_facility->id }}" />

    </div>

@endsection