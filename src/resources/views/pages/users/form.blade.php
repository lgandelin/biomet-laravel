<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="first_name">{{ trans('biomet::users.first_name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::users.first_name') }}" name="first_name" @if (isset($user_first_name))value="{{ $user_first_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="last_name">{{ trans('biomet::users.last_name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::users.last_name') }}" name="last_name" @if (isset($user_last_name))value="{{ $user_last_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="email">{{ trans('biomet::users.email') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::users.email') }}" name="email" @if (isset($user_email))value="{{ $user_email }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="password">{{ trans('biomet::users.password') }}</label><br/>
        <input class="form-control" type="password" placeholder="{{ trans('biomet::users.password') }}" name="password" autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="password_confirmation">{{ trans('biomet::users.password_confirmation') }}</label><br/>
        <input class="form-control" type="password" placeholder="{{ trans('biomet::users.password_confirmation') }}" name="password_confirmation" autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="email">{{ trans('biomet::users.client') }}</label>
        <select class="form-control" placeholder="{{ trans('biomet::users.client') }}" name="client_id" autocomplete="off">
            <option value="">{{ trans('biomet::generic.choose_value') }}</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @if (isset($user) && $user->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="is_administrator">{{ trans('biomet::users.is_administrator') }}</label><br/>
        Oui <input type="radio" placeholder="{{ trans('biomet::users.is_administrator') }}" name="is_administrator" value="y" @if (isset($is_administrator) && $is_administrator) checked @endif autocomplete="off" />
        Non <input type="radio" placeholder="{{ trans('biomet::users.is_administrator') }}" name="is_administrator" value="n" @if (isset($is_administrator) && !$is_administrator) checked @endif @if (!isset($is_administrator)) checked @endif autocomplete="off" />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('biomet::generic.valid') }}
        </button>
        <a href="{{ route('users') }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('biomet::generic.back') }}</a>
    </div>

    @if (isset($user_id))
        <input type="hidden" name="user_id" value="{{ $user_id }}" />
    @endif

    {!! csrf_field() !!}
</form>