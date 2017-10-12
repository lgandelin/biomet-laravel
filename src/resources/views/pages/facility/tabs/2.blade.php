@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Volume biogaz et biométhane
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Volume biogaz et biométhane" data-keys="{{ $data['keys'] }}" data-legend="{{ $data['legends'] }}"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection
