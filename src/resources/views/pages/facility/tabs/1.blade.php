@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Débit biogaz et biométhane
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Débit biogaz et biométhane" data-keys="FT0101F,FT0102F" data-legend="Biogaz brut (Nm<sup>3</sup>/h),Biométhane (Nm<sup>3</sup>/h)"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection