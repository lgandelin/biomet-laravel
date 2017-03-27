<div class="left-column">
    <nav>
        <ul>
            <li @if (Request::is('/'))class="active"@endif><a href="{{ route('dashboard') }}"><i class="glyphicon glyphicon-globe"></i> Carte des sites</a></li>

            @include('biomet::pages.facility.includes.menu')

            <li class="separator"></li>

            @if (Auth::user() && Auth::user()->profile_id === Webaccess\BiometLaravel\Models\User::PROFILE_ID_AROL_ENERGY_ADMINISTRATOR)
                <li class="admin-tab @if (preg_match('/facilities/', $current_route)) active @endif"><a href="{{ route('facilities') }}"><i class="fa fa-fw fa-cogs"></i> Gestion des sites</a></li>
                <li class="admin-tab @if (preg_match('/clients/', $current_route)) active @endif"><a href="{{ route('clients') }}"><i class="fa fa-fw fa-briefcase"></i> Gestion des clients</a></li>
                <li class="admin-tab @if (preg_match('/users/', $current_route)) active @endif"><a href="{{ route('users') }}"><i class="fa fa-fw fa-users"></i> Gestion des utilisateurs</a></li>
                <li class="admin-tab @if (preg_match('/regenerate_data/', $current_route)) active @endif"><a href="{{ route('regenerate_data') }}"><i class="fa fa-fw fa-database"></i> Régénérer des données</a></li>
            @endif

            @if (Auth::user() && Auth::user()->profile_id === Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_ADMINISTRATOR)
                <li class="admin-tab @if (preg_match('/client_users/', $current_route)) active @endif"><a href="{{ route('client_users') }}"><i class="fa fa-fw fa-users"></i> Gestion des utilisateurs</a></li>
            @endif
        </ul>
    </nav>
</div>