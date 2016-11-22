@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    Puissance fournie aux digesteurs
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="Puissance fournie aux digesteurs" data-keys="Q_DIGEST"></div>
    <button class="btn btn-success download-excel">Export Excel</button>
@endsection
