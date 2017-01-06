<div class="date-filters">
    <div style="display:inline-block"><strong>Date de début :</strong> <input id="start_date" name="start_date" class="datepicker form-control" value="@if (isset($start_date)){{$start_date}}@else{{ date('d/m/Y', strtotime( '-1 days' )) }}@endif" /></div>
    <div style="display:inline-block"><strong>Date de fin :</strong> <input id="end_date" name="end_date" class="datepicker form-control" value="@if (isset($end_date)){{$end_date}}@else{{ date('d/m/Y', strtotime( '-1 days' )) }}@endif" /></div>
    <input type="button" id="valid" class="btn btn-valid" value="{{ trans('biomet::generic.valid') }}" />

    <ul>
        <li><a @if (isset($current) && $current == false) @else class="current"@endif href="javascript:last_24h();">Dernières 24h</a></li> |
        <li><a href="javascript:last_week()">Dernière semaine</a></li> |
        <li><a href="javascript:last_month()">Dernier mois</a></li> |
        <li><a href="javascript:current_year()">Année en cours</a></li>
    </ul>
</div>

{{ csrf_field() }}
<input type="hidden" id="facility_id" value="{{ $current_facility->id }}" />
<input type="hidden" id="current_date" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" />