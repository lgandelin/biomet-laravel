<ul class="nav nav-tabs">
    <li @if ($current_route == 'facility')class="active"@endif><a href="{{ route('facility', ['id' => $current_facility->id]) }}">Infos</a></li>
    <li @if ($current_route == 'facility_1')class="active"@endif><a href="{{ route('facility_1', ['id' => $current_facility->id]) }}">Débit</a></li>
    <li><a href="#">Volume</a></li>
    <li @if ($current_route == 'facility_3')class="active"@endif><a href="{{ route('facility_3', ['id' => $current_facility->id]) }}">Composition</a></li>
    <li><a href="#">Suivi prétraitement</a></li>
    <li @if ($current_route == 'facility_5')class="active"@endif><a href="{{ route('facility_5', ['id' => $current_facility->id]) }}">Indicateur IGP</a></li>
    <li><a href="#">Consommation électrique</a></li>
    <li><a href="#">Puissance fournie</a></li>
    <li><a href="#">Heures en fonctionnement</a></li>
    <li><a href="#">Historique des alarmes</a></li>
    <li @if ($current_route == 'facility_10')class="active"@endif><a href="{{ route('facility_10', ['id' => $current_facility->id]) }}">Maintenance</a></li>
</ul>