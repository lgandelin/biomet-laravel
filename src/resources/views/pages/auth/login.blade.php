@extends('biomet::master')

@section('page-title') Connexion @endsection

@section('main-content')

    <div class="container">
        <div class="login-template">

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" style="margin-top: 5rem">
                    <div class="panel-heading">{{ trans('biomet::login.title') }}</div>
                    <div class="panel-body">

                        @if (isset($error))
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endif

                        <h1 class="logo" style="text-align: center; margin-top: 1rem; margin-bottom: 4rem"><img src="{{asset('img/logo-arol-energy.png')}}"></h1>

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
                                <button type="submit" class="btn btn-valid">{{ trans('biomet::login.login') }}</button>
                                <a href="{{ route('forgotten_password') }}">{{ trans('biomet::login.forgotten_password') }}</a>
                            </div>

                            {!! csrf_field() !!}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
