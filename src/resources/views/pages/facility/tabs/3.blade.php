@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Composition
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Composition biogaz brut" data-keys="AP0201_CH4,AP0201_CO2,AP0201_H2O,AP0201_H2S,AP0201_O2"></div>
    <div class="graph" id="container2" data-title="Composition biométhane" data-keys="AP0101_CH4,AP0101_CO2,AP0101_H2O,AP0101_H2S,AP0101_O2"></div>
@endsection
