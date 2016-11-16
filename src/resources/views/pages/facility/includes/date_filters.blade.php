<p style="text-align: center;">
    <strong>Date de début :</strong> <input id="start_date" type="date" class="form-control" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" style="display: inline; width:175px; margin-bottom: 1rem; margin-right: 2.5rem;"/>
    <strong>Date de fin :</strong> <input id="end_date" type="date" class="form-control" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" style="display: inline; width:175px; margin-bottom: 1rem;"/>
    <input type="button" id="valid_graphs" class="btn btn-success" value="{{ trans('biomet::generic.valid') }}" />

<ul style="text-align: center;">
    <li><a href="javascript:last_24h()">Dernières 24h</a></li>
    <li><a href="javascript:last_week()">Dernière semaine</a></li>
    <li><a href="javascript:last_month()">Dernier mois</a></li>
    <li><a href="javascript:current_year()">Année en cours</a></li>
</ul>
</p>

{{ csrf_field() }}
<input type="hidden" id="facility_id" value="{{ $current_facility->id }}" />
<input type="hidden" id="current_date" value="{{ date('Y-m-d', strtotime( '-1 days' )) }}" />