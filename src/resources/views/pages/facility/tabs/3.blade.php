@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Composition biogaz et biométhane
@endsection

@section('graphs')
    @if ($current_facility->id == 'a054b4ef-64d9-4c46-a6ab-99de9d4c3d11')
        <div class="graph" id="container1" data-title="Composition biogaz brut (AP0201)" data-keys="AP0201_CH4,AP0201_CO2,AP0201_H2O,AP0201_H2S,AP0201_O2" data-legend="CH<sub>4</sub> (%),CO<sub>2</sub> (%),H<sub>2</sub>O (%HR),H<sub>2</sub>S (ppm),O<sub>2</sub> (%)"></div>
        @include('biomet::pages.facility.includes.excel_button')

        <div class="graph" id="container2" data-title="Composition biométhane (AP0101)" data-keys="AP0101_CH4,AP0101_CO2,AP0101_H2O,AP0101_H2S,AP0101_O2" data-legend="CH<sub>4</sub> (%),CO<sub>2</sub> (%),H<sub>2</sub>O (°C),H<sub>2</sub>S (ppm),O<sub>2</sub> (%)"></div>
        @include('biomet::pages.facility.includes.excel_button')
    @elseif ($current_facility->id == '6dc0272e-be4e-4d94-bccd-7f6f3b78289c')
        <div class="graph" id="container1" data-title="Composition biométhane (GrDF)" data-keys="COMPO_CH4_GRDF,COMPO_CO2_GRDF,COMPO_O2_GRDF,COMPO_N2_GRDF,COMPO_H2_GRDF,COMPO_H2S_GRDF" data-legend="CH<sub>4</sub> (%),CO<sub>2</sub> (%),O<sub>2</sub> (%),N<sub>2</sub> (%),H<sub>2</sub> (%),H<sub>2</sub>S (ppm)"></div>
        @include('biomet::pages.facility.includes.excel_button')
    @endif
@endsection