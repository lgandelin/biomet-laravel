<div class="left-column">
    <nav>
        <ul>
            <li @if (Request::is('/'))class="active"@endif><a href="{{ route('dashboard') }}"><i class="fa fa-fw fa-dashboard"></i> Carte des sites</a></li>

            @include('biomet::pages.facility.includes.menu')

            @if (Auth::user() && Auth::user()->profile_id === Webaccess\BiometLaravel\Models\User::PROFILE_ID_AROL_ENERGY_ADMINISTRATOR)
                <li @if (preg_match('/facilities/', $current_route)) class="active" @endif><a href="{{ route('facilities') }}"><i class="fa fa-fw fa-cogs"></i> Gestion des sites</a></li>
                <li @if (preg_match('/clients/', $current_route)) class="active" @endif><a href="{{ route('clients') }}"><i class="fa fa-fw fa-briefcase"></i> Gestion des clients</a></li>
                <li @if (preg_match('/users/', $current_route)) class="active" @endif><a href="{{ route('users') }}"><i class="fa fa-fw fa-users"></i> Gestion des utilisateurs</a></li>
            @endif

            @if (Auth::user() && Auth::user()->profile_id === Webaccess\BiometLaravel\Models\User::PROFILE_ID_CLIENT_ADMINISTRATOR))
                <li @if (preg_match('/client_users/', $current_route)) class="active" @endif><a href="{{ route('client_users') }}"><i class="fa fa-fw fa-users"></i> Gestion des utilisateurs</a></li>
            @endif
        </ul>
    </nav>
</div>