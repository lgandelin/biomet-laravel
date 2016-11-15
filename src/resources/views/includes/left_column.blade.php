<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse">
            <span class="sr-only">BIOMET</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard') }}">BIOMET</a>
    </div>

    <div class="collapse navbar-collapse">

        <ul class="nav navbar-right top-nav">
            <li><a href="{{ route('logout') }}">Se déconnecter</a></li>
        </ul>

        <ul class="nav navbar-nav side-nav">
            <li @if (Request::is('/'))class="active"@endif><a href="{{ route('dashboard') }}"><i class="fa fa-fw fa-dashboard"></i> Tableau de bord</a></li>

            <li class="@if(isset($current_facility) && $current_facility->id) active @endif ">
                <?php $route = (preg_match('/facility_/', $current_route)) ? $current_route : 'facility'; ?>
                <a class="toggle-facilities" href="#"><i class="fa fa-fw fa-bar-chart"></i> Sites <i class="fa fa-fw toggle-icon fa-caret-down"></i></a>
                <ul @if (count($left_column_facilities) > 15)style="display: none;"@endif>
                    @if (count($left_column_facilities) > 0)
                        @foreach ($left_column_facilities as $f)
                            <li @if(isset($current_facility) && isset($current_facility->id) && $f->id === $current_facility->id)class="active"@endif><a href="{{ route($route, array('id' => $f->id)) }}">{{ $f->name }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </li>

            @if (Auth::user() && Auth::user()->profile_id === Webaccess\BiometLaravel\Models\User::PROFILE_ID_ADMINISTRATOR)
                <li @if (preg_match('/facilities/', $current_route)) class="active" @endif><a href="{{ route('facilities') }}"><i class="fa fa-fw fa-cogs"></i> Gestion des sites</a></li>
                <li @if (preg_match('/clients/', $current_route)) class="active" @endif><a href="{{ route('clients') }}"><i class="fa fa-fw fa-briefcase"></i> Gestion des clients</a></li>
                <li @if (preg_match('/users/', $current_route)) class="active" @endif><a href="{{ route('users') }}"><i class="fa fa-fw fa-users"></i> Gestion des utilisateurs</a></li>
            @endif

        </ul>
    </div>
</nav>

<script>
    $('.toggle-facilities').click(function() {
        $(this).next().toggle();
        $(this).find('.toggle-icon').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
    });
</script>