@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <div class="box">
        <h1 class="box-title">{{ $current_facility->name }} - Tableau de bord</h1>
        <div class="box-content facility-template">

            @if (isset($error))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endif

            @if (isset($confirmation))
                <div class="alert alert-success">
                    {{ $confirmation }}
                </div>
            @endif
        </div>
    </div>

@endsection