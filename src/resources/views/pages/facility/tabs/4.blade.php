@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Suivi prétraitement
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Concentration H2S/O2 autour des filtres de prétraitement" data-keys="AP0201_H2S,AP0201_O2,AP0202_H2S,AP0202_O2,AP0203_H2S,AP0203_O2" data-legend="0"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection
