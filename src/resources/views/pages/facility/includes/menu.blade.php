<ul class="nav nav-tabs">
    <li @if ($current_route == 'facility')class="active"@endif><a href="{{ route('facility', ['id' => $current_facility->id]) }}">Résumé</a></li>
    <li @if ($current_tab == 1)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 1]) }}">Débit</a></li>
    <li @if ($current_tab == 2)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 2]) }}">Volume</a></li>
    <li @if ($current_tab == 3)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 3]) }}">Composition</a></li>
    <li @if ($current_tab == 4)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 4]) }}">Suivi prétraitement</a></li>
    <li @if ($current_tab == 5)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 5]) }}">Indicateur IGP</a></li>
    <li @if ($current_tab == 6)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 6]) }}">Consommation électrique</a></li>
    <li @if ($current_tab == 7)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 7]) }}">Puissance fournie</a></li>
    <li class="disabled"><a href="#">Heures en fonctionnement</a></li>
    <li @if ($current_tab == 9)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 9]) }}">Historique des alarmes</a></li>
    <li @if ($current_tab == 10)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 10]) }}">Maintenance</a></li>
    <li @if ($current_tab == 11)class="active"@endif><a href="{{ route('facility_tab', ['id' => $current_facility->id, 'tab' => 11]) }}">Fichiers de données</a></li>
</ul>