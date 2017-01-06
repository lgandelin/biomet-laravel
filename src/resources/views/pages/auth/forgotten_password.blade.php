@extends('biomet::master')

@section('page-title') Mot de passe oublié @endsection

@section('main-content')

    <div class="container">
        <div class="login-template">

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" style="margin-top: 5rem">
                    <div class="panel-heading">Mot de passe oublié</div>
                    <div class="panel-body">

                        @if ($message)
                            <div class="alert alert-info">
                                {{ $message }}
                            </div>
                        @endif

                        @if ($error)
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endif

                        <h1 class="logo" style="text-align: center; margin-top: 1rem; margin-bottom: 4rem"><img src="{{asset('img/logo-arol-energy.png')}}"></h1>

                        <form class="form-horizontal" role="form" method="POST" action="{{ route('forgotten_password_handler') }}">
                            <div class="form-group">
                                <label>{{ trans('biomet::login.email') }}</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" />
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-valid">{{ trans('biomet::login.send_new_password') }}</button>
                                <a href="{{ route('login') }}" title="{{ trans('biomet::generic.back') }}">{{ trans('biomet::generic.back') }}</a>
                            </div>

                            {!! csrf_field() !!}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
