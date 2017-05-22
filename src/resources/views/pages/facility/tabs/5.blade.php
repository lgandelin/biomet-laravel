@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Indicateur Global de Performance
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Indicateur Global de Performance (IGP)" data-keys="IGP,IGP_AVG" data-legend="IGP (-),IGP moyen (-)"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection