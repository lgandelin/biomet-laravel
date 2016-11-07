<form action="{{ $form_action }}" method="post">
    <div class="form-group">
        <label for="name">{{ trans('biomet::clients.name') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::clients.name') }}" name="name" @if (isset($client_name))value="{{ $client_name }}"@endif />
    </div>

    <div class="form-group">
        <button type="submit" class="btn valid">
            <i class="glyphicon glyphicon-ok"></i> {{ trans('biomet::generic.valid') }}
        </button>
        <a href="{{ route('clients') }}" class="btn back"><i class="glyphicon glyphicon-arrow-left"></i> {{ trans('biomet::generic.back') }}</a>
    </div>

    @if (isset($client_id))
        <input type="hidden" name="client_id" value="{{ $client_id }}" />
    @endif

    {!! csrf_field() !!}
</form>