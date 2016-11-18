<ul class="nav nav-tabs">
    <li @if ($current_route == 'facility')class="active"@endif><a href="{{ route('facility', ['id' => $current_facility->id]) }}">Résumé</a></li>
    <li @if ($current_route == 'facility_1')class="active"@endif><a href="{{ route('facility_1', ['id' => $current_facility->id]) }}">Débit</a></li>
    <li @if ($current_route == 'facility_2')class="active"@endif><a href="{{ route('facility_2', ['id' => $current_facility->id]) }}">Volume</a></li>
    <li @if ($current_route == 'facility_3')class="active"@endif><a href="{{ route('facility_3', ['id' => $current_facility->id]) }}">Composition</a></li>
    <li @if ($current_route == 'facility_4')class="active"@endif><a href="{{ route('facility_4', ['id' => $current_facility->id]) }}">Suivi prétraitement</a></li>
    <li @if ($current_route == 'facility_5')class="active"@endif><a href="{{ route('facility_5', ['id' => $current_facility->id]) }}">Indicateur IGP</a></li>
    <li @if ($current_route == 'facility_6')class="active"@endif><a href="{{ route('facility_6', ['id' => $current_facility->id]) }}">Consommation électrique</a></li>
    <li @if ($current_route == 'facility_7')class="active"@endif><a href="{{ route('facility_7', ['id' => $current_facility->id]) }}">Puissance fournie</a></li>
    <li class="disabled"><a href="#">Heures en fonctionnement</a></li>
    <li class="disabled"><a href="#">Historique des alarmes</a></li>
    <li @if ($current_route == 'facility_10')class="active"@endif><a href="{{ route('facility_10', ['id' => $current_facility->id]) }}">Maintenance</a></li>
</ul>