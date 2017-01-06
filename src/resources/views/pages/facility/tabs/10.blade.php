@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">{{ $current_facility->name }} - Maintenance</h1>
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

            <form id="interventions-form" action="">
                @include('biomet::pages.facility.includes.date_filters', [
                    'start_date' => $data['filter_start_date'],
                    'end_date' => $data['filter_end_date'],
                    'current' => false,
                ])
            </form>

            <h4>Liste des interventions</h4>
            <table class="table table-stripped">
                <thead>
                <tr>
                    <th>{{ trans('biomet::interventions.event_date') }}</th>
                    <th>{{ trans('biomet::interventions.title') }}</th>
                    <th>{{ trans('biomet::interventions.description') }}</th>
                    <th>{{ trans('biomet::generic.action') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($data['interventions'] as $intervention)
                    <tr>
                        <td>{{ (new \DateTime($intervention->event_date))->format('d/m/Y') }}</td>
                        <td>{{ $intervention->title }}</td>
                        <td>{{ $intervention->description }}</td>
                        <td width="15%">
                            <a href="{{ route('interventions_edit', ['id' => $intervention->id]) }}"><i class="btn-edit glyphicon glyphicon-pencil"></i></a>
                            <a href="{{ route('interventions_delete', ['id' => $intervention->id]) }}"><i class="btn-remove glyphicon glyphicon-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $data['interventions']->render() !!}
            </div>
        </div>
    </div>

    <a class="btn btn-valid pull-right" style="margin-top: 2rem" href="{{ route('interventions_add', ['id' => $current_facility->id]) }}">{{ trans('biomet::generic.add') }}</a>

    <script src="{{ asset('js/filters.js') }}"></script>
    <script>
        $('#valid').click(function() {
            $('#interventions-form').submit();
        });
    </script>

@endsection