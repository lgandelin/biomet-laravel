@extends('biomet::master')

@section('page-title') Mot de passe oublié @endsection

@section('main-content')

    @if ($message)
        <div class="bg-info">
            {{ $message }}
        </div>
    @endif

    @if ($error)
        <div class="bg-danger">
            {{ $error }}
        </div>
    @endif

    <form class="form-horizontal" role="form" method="POST" action="{{ route('forgotten_password_handler') }}">
        <div class="form-group">
            <label>{{ trans('biomet::login.email') }}</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" />
        </div>

        <div class="form-group">
            <button type="submit">{{ trans('biomet::login.send_new_password') }}</button>
            <a href="{{ route('login') }}" title="{{ trans('biomet::generic.back') }}">{{ trans('biomet::generic.back') }}</a>
        </div>

        {!! csrf_field() !!}
    </form>

@endsection
