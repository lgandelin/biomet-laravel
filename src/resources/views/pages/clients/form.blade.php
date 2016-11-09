<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('biomet::clients.name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::clients.name') }}" name="name" @if (isset($client_name))value="{{ $client_name }}"@endif />
    </div>
    
    <div class="form-group">
        <label for="access_limit_date">{{ trans('biomet::clients.access_limit_date') }}</label>
        <input class="form-control" type="date" placeholder="{{ trans('biomet::clients.access_limit_date') }}" name="access_limit_date" @if (isset($client_access_limit_date))value="{{ $client_access_limit_date }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit">{{ trans('biomet::generic.valid') }}</button>
        <a href="{{ route('clients') }}">{{ trans('biomet::generic.back') }}</a>
    </div>

    @if (isset($client_id))
        <input type="hidden" name="client_id" value="{{ $client_id }}" />
    @endif

    {!! csrf_field() !!}
</form>