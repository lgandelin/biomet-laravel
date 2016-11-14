@extends('biomet::default')

@section('page-title') Gestion des sites @endsection

@section('page-content')
    <h1>Gestion des sites</h1>

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

    <table class="table table-stripped">
        <thead>
        <tr>
            <th>{{ trans('biomet::facilities.name') }}</th>
            <th>{{ trans('biomet::facilities.client') }}</th>
            <th>{{ trans('biomet::generic.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($facilities as $facility)
            <tr>
                <td>{{ $facility->name }}</td>
                <td>@if ($facility->client){{ $facility->client->name }}@else N/A @endif</td>
                <td align="right">
                    <a class="btn btn-primary" href="{{ route('facilities_edit', ['id' => $facility->id]) }}">{{ trans('biomet::generic.edit') }}</a>
                    <a class="btn btn-danger" href="{{ route('facilities_delete', ['id' => $facility->id]) }}">{{ trans('biomet::generic.delete') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a class="btn btn-success" href="{{ route('facilities_add') }}">{{ trans('biomet::generic.add') }}</a>

    <div class="text-center">
        {!! $facilities->render() !!}
    </div>
    
@endsection