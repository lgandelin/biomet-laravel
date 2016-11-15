<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('biomet::facilities.name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::facilities.name') }}" name="name" @if (isset($facility_name))value="{{ $facility_name }}"@endif />
    </div>

    <div class="form-group">
        <label for="latitude">{{ trans('biomet::facilities.latitude') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::facilities.latitude') }}" name="latitude" @if (isset($facility_latitude))value="{{ $facility_latitude }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="longitude">{{ trans('biomet::facilities.longitude') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::facilities.longitude') }}" name="longitude" @if (isset($facility_longitude))value="{{ $facility_longitude }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="address">{{ trans('biomet::facilities.address') }}</label>
        <textarea class="form-control" rows="5" placeholder="{{ trans('biomet::facilities.address') }}" name="address">@if (isset($facility_address)){{ $facility_address }}@endif</textarea>
    </div>

    <div class="form-group">
        <label for="city">{{ trans('biomet::facilities.city') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::facilities.city') }}" name="city" @if (isset($facility_city))value="{{ $facility_city }}"@endif autocomplete="off" />
    </div>
    
    <div class="form-group">
        <label for="department">{{ trans('biomet::facilities.department') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::facilities.department') }}" name="department" @if (isset($facility_department))value="{{ $facility_department }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="email">{{ trans('biomet::users.client') }}</label>
        <select class="form-control" placeholder="{{ trans('biomet::users.client') }}" name="client_id" autocomplete="off">
            <option value="">{{ trans('biomet::generic.choose_value') }}</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @if (isset($facility) && $facility->client_id == $client->id)selected="selected"@endif>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <button class="btn btn-success" type="submit">{{ trans('biomet::generic.valid') }}</button>
        <a class="btn btn-default" href="{{ route('facilities') }}">{{ trans('biomet::generic.back') }}</a>
    </div>

    @if (isset($facility_id))
        <input type="hidden" name="facility_id" value="{{ $facility_id }}" />
    @endif

    {!! csrf_field() !!}
</form>