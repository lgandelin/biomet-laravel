@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Consommation électrique
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Consommation électrique installation complète" data-keys="CONSO_ELEC_INSTAL,CONSO_ELEC_INSTAL_AVG"></div>
    @include('biomet::pages.facility.includes.excel_button')

    <div class="graph" id="container2" data-title="Consommation électrique chaudière" data-keys="CONSO_ELEC_CHAUD,CONSO_ELEC_CHAUD_AVG"></div>
    @include('biomet::pages.facility.includes.excel_button')

    <div class="graph" id="container3" data-title=" Consommation électrique pompe de circulation d’eau chaude" data-keys="CONSO_ELEC_PEC,CONSO_ELEC_PEC_AVG"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection
