<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse">
            <span class="sr-only">Plateforme web AROL ENERGY</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard') }}">Plateforme web AROL ENERGY</a>
    </div>

    <div class="collapse navbar-collapse">
        <ul class="nav navbar-right top-nav">
            <li><a href="{{ route('logout') }}">Se déconnecter</a></li>
        </ul>

        <ul class="nav navbar-nav side-nav">
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
    </div>

    <select id="facilities-switcher">
        @foreach ($navigation_facilities as $f)
            <option value="{{ $f->id }}" @if(isset($current_facility) && isset($current_facility->id) && $f->id === $current_facility->id)selected="selected"@endif>{{ $f->name }}</option>
        @endforeach
    </select>
</nav>

<script>
    $('.toggle-facilities').click(function() {
        $(this).next().toggle();
        $(this).find('.toggle-icon').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
    });

    @if (isset($current_facility) && $current_facility->id && preg_match('/facility/', $current_route))
        var route = "{{ route($current_route, ['id' => '@@@', 'tab' => $current_tab]) }}";
    @else
        var route = "{{ route('facility', ['id' => '@@@', 'tab' => 0]) }}";
    @endif

    $('#facilities-switcher').change(function() {
        window.location.href = route.replace(/@@@/g,$(this).val())
    });
</script>