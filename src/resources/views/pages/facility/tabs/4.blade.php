@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Suivi prétraitements
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Concentration H<sub>2</sub>S/O<sub>2</sub> autour des filtres de prétraitements" data-keys="AP0201_H2S,AP0201_O2,AP0202_H2S,AP0202_O2,AP0203_H2S,AP0203_O2" data-legend="AP0201 H<sub>2</sub>S (ppm),AP0201 O<sub>2</sub> (%),AP0202 H<sub>2</sub>S (ppm),AP0202 O<sub>2</sub> (%),AP0203 H<sub>2</sub>S (ppm),AP0203 O<sub>2</sub> (%)"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection
