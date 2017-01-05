@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')
    <h1>{{ $current_facility->name }} - Maintenance</h1>

    <div class="facility-template">

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
                    <td align="right">
                        <a class="btn btn-primary" href="{{ route('interventions_edit', ['id' => $intervention->id]) }}">{{ trans('biomet::generic.edit') }}</a>
                        <a class="btn btn-danger" href="{{ route('interventions_delete', ['id' => $intervention->id]) }}">{{ trans('biomet::generic.delete') }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <a class="btn btn-success" href="{{ route('interventions_add', ['id' => $current_facility->id]) }}">{{ trans('biomet::generic.add') }}</a>

        <div class="text-center">
            {!! $data['interventions']->render() !!}
        </div>

    </div>

    <script src="{{ asset('js/filters.js') }}"></script>
    <script>
        $('#valid').click(function() {
            $('#interventions-form').submit();
        });
    </script>

@endsection