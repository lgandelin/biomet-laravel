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
        <label for="department">{{ trans('biomet::facilities.country') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::facilities.country') }}" name="country" @if (isset($facility_country))value="{{ $facility_country }}"@endif autocomplete="off" />
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
        <label for="department">{{ trans('biomet::facilities.technology') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::facilities.technology') }}" name="technology" @if (isset($facility_technology))value="{{ $facility_technology }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="serial_number">{{ trans('biomet::facilities.serial_number') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::facilities.serial_number') }}" name="serial_number" @if (isset($facility_serial_number))value="{{ $facility_serial_number }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="startup_date">{{ trans('biomet::facilities.startup_date') }}</label>
        <input class="datepicker form-control" type="text" placeholder="{{ trans('biomet::facilities.startup_date') }}" name="startup_date" @if (isset($facility_startup_date))value="{{ $facility_startup_date }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="tabs[]">{{ trans('biomet::facilities.tabs') }}</label>
        <select multiple class="form-control" name="tabs[]" autocomplete="off" style="height:200px">
            <option value="1" @if (in_array(1, $facility_tabs))selected="selected"@endif>Débit</option>
            <option value="2" @if (in_array(2, $facility_tabs))selected="selected"@endif>Volume</option>
            <option value="3" @if (in_array(3, $facility_tabs))selected="selected"@endif>Composition</option>
            <option value="4" @if (in_array(4, $facility_tabs))selected="selected"@endif>Suivi prétraitement</option>
            <option value="5" @if (in_array(5, $facility_tabs))selected="selected"@endif>Indicateur IGP</option>
            <option value="6" @if (in_array(6, $facility_tabs))selected="selected"@endif>Consommation électrique</option>
            <option value="7" @if (in_array(7, $facility_tabs))selected="selected"@endif>Puissance fournie</option>
            <option value="8" @if (in_array(8, $facility_tabs))selected="selected"@endif>Heure en fonctionnement</option>
            <option value="9" @if (in_array(9, $facility_tabs))selected="selected"@endif>Historique des alarmes</option>
            <option value="10" @if (in_array(10, $facility_tabs))selected="selected"@endif>Maintenance</option>
            <option value="11" @if (in_array(11, $facility_tabs))selected="selected"@endif>Fichiers de données</option>
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
