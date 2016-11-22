@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Débits biogaz
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Débits biogaz" data-keys="FT0101F,FT0102F"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection