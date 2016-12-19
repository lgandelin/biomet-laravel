@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Volume biogaz
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Volume biogaz" data-keys="FT0101F_VOLUME,FT0102F_VOLUME"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection
