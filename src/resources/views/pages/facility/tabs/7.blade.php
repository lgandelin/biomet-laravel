@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Puissance fournie aux digesteurs
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Puissance fournie aux digesteurs" data-keys="Q_DIGEST"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection
