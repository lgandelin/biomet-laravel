@extends('biomet::default')

@section('page-title') Ajouter un site @endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">Ajouter un site</h1>
        <div class="box-content">
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

            @include('biomet::pages.facilities.form', [
                'form_action' => route('facilities_store'),
            ])
        </div>
    </div>

@endsection