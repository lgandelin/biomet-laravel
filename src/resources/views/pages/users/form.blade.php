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
        <label for="profile_id">{{ trans('biomet::users.profile') }}</label><br/>
        Administrateur Arol Energy <input style="margin-right: 2rem" type="radio" name="profile_id" value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_AROL_ENERGY_ADMINISTRATOR }}" @if (isset($profile_id) && $profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_AROL_ENERGY_ADMINISTRATOR) checked @endif autocomplete="off" />
        Administrateur client <input style="margin-right: 2rem" type="radio" name="profile_id" value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_ADMINISTRATOR }}" @if (isset($profile_id) && $profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_ADMINISTRATOR) checked @endif autocomplete="off" />
        Utilisateur client <input style="margin-right: 2rem" type="radio" name="profile_id" value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_USER }}" @if (isset($profile_id) && $profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_USER) checked @endif autocomplete="off" />
        Prestataire <input type="radio" name="profile_id" value="{{ Webaccess\BiometLaravel\Models\User::PROFILE_ID_PROVIDER }}" @if (isset($profile_id) && $profile_id == Webaccess\BiometLaravel\Models\User::PROFILE_ID_PROVIDER) checked @endif autocomplete="off" />
    </div>

    <div class="form-group">
        <button class="btn btn-valid" type="submit">{{ trans('biomet::generic.valid') }}</button>
        <a class="btn btn-default" href="{{ route('users') }}">{{ trans('biomet::generic.back') }}</a>
    </div>

    @if (isset($user_id))
        <input type="hidden" name="user_id" value="{{ $user_id }}" />
    @endif

    {!! csrf_field() !!}
</form>