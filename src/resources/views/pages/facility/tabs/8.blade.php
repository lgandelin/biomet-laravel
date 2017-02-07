@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">{{ $current_facility->name }} - Heures en fonctionnement</h1>
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

            <h4>Liste des équipements</h4>
            <table class="table table-stripped">
                <thead>
                <tr>
                    <th>{{ trans('biomet::equipments.tag') }}</th>
                    <th>{{ trans('biomet::equipments.name') }}</th>
                    <th>{{ trans('biomet::equipments.hours_functionning') }}</th>
                </tr>
                </thead>

                <tbody>
                @if (sizeof($data['equipments']) > 0)
                    @foreach ($data['equipments'] as $equipment)
                        <tr>
                            <td>{{ $equipment->tag }}</td>
                            <td>{{ $equipment->name }}</td>
                            <td>{{ $equipment->hours_functionning }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="4">Aucun équipement entré pour le moment.</td></tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection