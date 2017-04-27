@extends('biomet::default')

@section('page-title') Régénérer des données @endsection

@section('page-content')
    <div class="box">
        <h1 class="box-title">Régénérer des données</h1>
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

            <form action="{{ route('regenerate_data_handler') }}" method="post">

                <div class="form-group">
                    <label>Site</label><br/>
                    <select name="facility_id">
                        @foreach ($facilities as $facility)
                            <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Date de début</label>
                    <input name="start_date" class="datepicker form-control" value="{{ date('d/m/Y', strtotime( '-1 days' )) }}" />
                </div>

                <div class="form-group">
                    <label>Date de fin</label>
                    <input name="end_date" class="datepicker form-control" value="{{ date('d/m/Y', strtotime( '-1 days' )) }}" />
                </div>

                {{ csrf_field() }}
                <input type="submit" class="btn btn-valid valid" value="{{ trans('biomet::generic.valid') }}" />
            </form>

        </div>
    </div>

@endsection
