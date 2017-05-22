@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Composition biogaz et biométhane
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Composition biogaz brut (AP0201)" data-keys="AP0201_CH4,AP0201_CO2,AP0201_H2O,AP0201_H2S,AP0201_O2" data-legend="CH<sub>4</sub> (%),CO<sub>2</sub> (%),H<sub>2</sub>O (%HR),H<sub>2</sub>S (ppm),O<sub>2</sub> (%)"></div>
    @include('biomet::pages.facility.includes.excel_button')

    <div class="graph" id="container2" data-title="Composition biométhane (AP0101)" data-keys="AP0101_CH4,AP0101_CO2,AP0101_H2O,AP0101_H2S,AP0101_O2" data-legend="CH<sub>4</sub> (%),CO<sub>2</sub> (%),H<sub>2</sub>O (°C),H<sub>2</sub>S (ppm),O<sub>2</sub> (%)"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection