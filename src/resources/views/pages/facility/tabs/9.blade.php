@extends('biomet::default')

@section('page-title'){{ $current_facility->name }}@endsection

@section('page-content')

    <div class="box">
        <h1 class="box-title">{{ $current_facility->name }} - Historique des alarmes</h1>
        <div class="box-content facility-template">
            <form id="alarms-form" action="">
                @include('biomet::pages.facility.includes.date_filters', [
                    'start_date' => $data['filter_start_date'],
                    'end_date' => $data['filter_end_date'],
                    'current' => false
                ])
            </form>

            <h4>Liste des alarmes</h4>
            <table class="table table-stripped">
                <thead>
                <tr>
                    <th>{{ trans('biomet::alarms.event_date') }}</th>
                    <th>{{ trans('biomet::alarms.title') }}</th>
                    <th>{{ trans('biomet::alarms.description') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($data['alarms'] as $alarm)
                    <tr>
                        <td>{{ $alarm->event_date }}</td>
                        <td>{{ $alarm->title }}</td>
                        <td>{{ $alarm->description }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {!! $data['alarms']->render() !!}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/filters.js') }}"></script>
    <script>
        $('.valid').click(function() {
            $('#alarms-form').submit();
        });
    </script>

@endsection