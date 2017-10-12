@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Consommation électrique
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Consommation électrique installation complète" data-keys="CONSO_ELEC_INSTAL,CONSO_ELEC_INSTAL_AVG" data-legend="CONSO_ELEC_INSTAL (kW),CONSO_ELEC_INSTAL_AVG (kW)"></div>
    @include('biomet::pages.facility.includes.excel_button')

    @if ($current_facility->id == 'a054b4ef-64d9-4c46-a6ab-99de9d4c3d11')
        <div class="graph" id="container2" data-title="Consommation électrique chaudière" data-keys="CONSO_ELEC_CHAUD,CONSO_ELEC_CHAUD_AVG" data-legend="CONSO_ELEC_CHAUD (kW),CONSO_ELEC_CHAUD_AVG (kW)"></div>
        @include('biomet::pages.facility.includes.excel_button')

        <div class="graph" id="container3" data-title=" Consommation électrique pompe de circulation d’eau chaude" data-keys="CONSO_ELEC_PEC,CONSO_ELEC_PEC_AVG" data-legend="CONSO_ELEC_PEC (kW),CONSO_ELEC_PEC_AVG (kW)"></div>
        @include('biomet::pages.facility.includes.excel_button')
    @endif
@endsection
