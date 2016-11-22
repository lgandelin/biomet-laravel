@extends('biomet::pages.facility.tabs.tab')

@section('tab_name')
    IGP
@endsection

@section('graphs')
    <div class="graph" id="container1" data-title="IGP" data-keys="IGP,IGP_AVG"></div>
    @include('biomet::pages.facility.includes.excel_button')
@endsection
