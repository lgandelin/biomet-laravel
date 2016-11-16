@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Suivi prétraitement
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Concentration H2S et O2 - AP0201" data-keys="AP0201_H2S,AP0201_O2"></div>
    <div class="graph" id="container2" data-title="Concentration H2S et O2 - AP0202" data-keys="AP0202_H2S,AP0202_O2"></div>
    <div class="graph" id="container3" data-title="Concentration H2S et O2 - AP0203" data-keys="AP0203_H2S,AP0203_O2"></div>
@endsection
