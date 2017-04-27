@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Volume biogaz et biométhane
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Volume biogaz et biométhane" data-keys="FT0101F_VOLUME,FT0102F_VOLUME,QV_BIO_EA" data-legend="Biogaz brut (Nm<sup>3</sup>),Biométhane (Nm<sup>3</sup>),Section amines (Nm<sup>3</sup>)"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection
