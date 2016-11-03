@extends('biomet::master')

@section('page-title'){{ trans('biomet::login.seo_title') }}@endsection

@section('main-content')

    @if (isset($error))
        <div class="info bg-danger">
            {{ $error }}
        </div>
    @endif

    <form class="form-horizontal" role="form" method="POST" action="{{ route('login_handler') }}">

        <div class="form-group">
            <label>{{ trans('biomet::login.email') }}</label>
            <input type="email" class="form-control" name="email" />
        </div>

        <div class="form-group">
            <label>{{ trans('biomet::login.password') }}</label>
            <input type="password" class="form-control" name="password" autocomplete="off" />
        </div>

        <div class="form-group">
            <button type="submit" class="button button-valid">
                {{ trans('biomet::login.login') }}
            </button>

            <a class="btn btn-link" href="{{ route('forgotten_password') }}">{{ trans('biomet::login.forgotten_password') }}</a>
        </div>

        {!! csrf_field() !!}
    </form>
@endsection
