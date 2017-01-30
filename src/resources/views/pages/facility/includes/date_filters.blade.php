<div class="date-filters">
    <div style="display:inline-block"><strong>Date de début :</strong> <input name="start_date" class="datepicker form-control" value="@if (isset($start_date)){{$start_date}}@else{{ date('d/m/Y', strtotime( '-1 days' )) }}@endif" /></div>
    <div style="display:inline-block"><strong>Date de fin :</strong> <input name="end_date" class="datepicker form-control" value="@if (isset($end_date)){{$end_date}}@else{{ date('d/m/Y', strtotime( '-1 days' )) }}@endif" /></div>
    <input type="button" class="btn btn-valid valid" value="{{ trans('biomet::generic.valid') }}" />

    <ul>
        <li><a class="date_filter_label last_24h @if (isset($current) && $current == false) @else current @endif" href="javascript:void(0)">Dernières 24h</a></li> |
        <li><a class="date_filter_label last_week" href="javascript:void(0)">Dernière semaine</a></li> |
        <li><a class="date_filter_label last_month" href="javascript:void(0)">Dernier mois</a></li> |
        <li><a class="date_filter_label current_year" href="javascript:void(0)">Année en cours</a></li>
    </ul>
</div>

{{ csrf_field() }}
<input type="hidden" id="facility_id" value="{{ $current_facility->id }}" />
<input type="hidden" id="current_date" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" />