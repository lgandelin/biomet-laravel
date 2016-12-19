<form action="{{ $form_action }}" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="event_date">{{ trans('biomet::interventions.event_date') }}</label>
        <input class="form-control" type="date" placeholder="{{ trans('biomet::interventions.event_date') }}" name="event_date" @if (isset($intervention_event_date))value="{{ $intervention_event_date }}"@endif />
    </div>

    <div class="form-group">
        <label for="title">{{ trans('biomet::interventions.title') }}</label>
        <input class="form-control" type="text" placeholder="{{ trans('biomet::interventions.title') }}" name="title" @if (isset($intervention_title))value="{{ $intervention_title }}"@endif autocomplete="off" />
    </div>

    <div class="form-group">
        <label for="description">{{ trans('biomet::interventions.description') }}</label>
        <textarea class="form-control" rows="5" placeholder="{{ trans('biomet::interventions.description') }}" name="description">@if (isset($intervention_description)){{ $intervention_description }}@endif</textarea>
    </div>

    <div class="form-group">
        <label for="personal_information">{{ trans('biomet::interventions.personal_information') }}</label>
        <textarea class="form-control" rows="5" placeholder="{{ trans('biomet::interventions.personal_information_placeholder') }}" name="personal_information">@if (isset($intervention_personal_information)){{ $intervention_personal_information }}@endif</textarea>
    </div>

    <div class="form-group">
        <label for="files">Uploader des fichiers</label>
        <input type="file" name="files[]" multiple>
    </div>

    <div class="form-group">
        <button class="btn btn-success" type="submit">{{ trans('biomet::generic.valid') }}</button>
        <a class="btn btn-default" href="{{ route('facility_tab', ['id' => $facility_id, 'tab' => 10]) }}">{{ trans('biomet::generic.back') }}</a>
    </div>

    @if (isset($intervention_id))
        <input type="hidden" name="intervention_id" value="{{ $intervention_id }}" />
    @endif

    <input type="hidden" name="facility_id" value="{{ $facility_id }}" />

    {!! csrf_field() !!}

    <div class="form-group">
        <label>Liste des fichiers</label>

        <table class="table table-bordered">
            <tr>
                <th>Date de création</th>
                <th>Taille</th>
                <th>Nom du fichier</th>
                <th>Action</th>
            </tr>

            @foreach ($files as $file)
                <tr>
                    <td width="10%">{{ $file['creation_date'] }}</td>
                    <td>{{ $file['name'] }}</td>
                    <td width="10%">{{ $file['size'] }}</td>
                    <td width="15%">
                        <a class="btn btn-success" href="{{ route('interventions_download_file', ['id' => $intervention_id, 'file_name' => $file['name']]) }}">{{ trans('biomet::generic.download') }}</a>
                        <a class="btn btn-danger" href="{{ route('interventions_delete_file', ['id' => $intervention_id, 'file_name' => $file['name']]) }}">{{ trans('biomet::generic.delete') }}</a>
                    </td>
            @endforeach
        </table>
    </div>
</form>