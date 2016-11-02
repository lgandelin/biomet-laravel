@extends('biomet::master')

@section('page-title')
    {{ trans('biomet::forgotten_password.seo_title') }}
@endsection

@section('main-content')

    @if ($message)
        <div class="info bg-info">
            {{ $message }}
        </div>
    @endif

    @if ($error)
        <div class="info bg-danger">
            {{ $error }}
        </div>
    @endif

    <form class="form-horizontal" role="form" method="POST" action="{{ route('forgotten_password_handler') }}">
        <div class="form-group">
            <label>{{ trans('biomet::forgotten_password.email') }}</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" />
        </div>

        <div class="form-group">
            <button type="submit" class="button button-valid" title="{{ trans('biomet::forgotten_password.title_password_forgotten') }}">
                {{ trans('biomet::forgotten_password.send_new_password') }}
            </button>

            <a href="javascript:history.back()" class="btn btn-dark-gray" title="{{ trans('biomet::forgotten_password.back_to_login') }}">{{ trans('biomet::forgotten_password.back_to_login') }}</a>
        </div>

        {!! csrf_field() !!}
    </form>

@endsection
