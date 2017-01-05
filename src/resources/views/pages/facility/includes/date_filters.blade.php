<p style="text-align: center;">
    <strong>Date de début :</strong> <input id="start_date" name="start_date" class="datepicker form-control" value="@if (isset($start_date)){{$start_date}}@else{{ date('d/m/Y', strtotime( '-1 days' )) }}@endif" style="display: inline; width:175px; margin-bottom: 1rem; margin-right: 2.5rem;"/>
    <strong>Date de fin :</strong> <input id="end_date" name="end_date" class="datepicker form-control" value="@if (isset($end_date)){{$end_date}}@else{{ date('d/m/Y', strtotime( '-1 days' )) }}@endif" style="display: inline; width:175px; margin-bottom: 1rem;"/>
    <input type="button" id="valid" class="btn btn-valid" value="{{ trans('biomet::generic.valid') }}" />

    <ul style="text-align: center;">
        <li style="display: inline;"><a href="javascript:last_24h()">Dernières 24h</a></li> |
        <li style="display: inline;"><a href="javascript:last_week()">Dernière semaine</a></li> |
        <li style="display: inline;"><a href="javascript:last_month()">Dernier mois</a></li> |
        <li style="display: inline;"><a href="javascript:current_year()">Année en cours</a></li>
    </ul>
</p>

{{ csrf_field() }}
<input type="hidden" id="facility_id" value="{{ $current_facility->id }}" />
<input type="hidden" id="current_date" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" />