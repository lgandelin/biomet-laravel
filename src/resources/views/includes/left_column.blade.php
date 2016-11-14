<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">BIOMET</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard') }}">BIOMET</a>
    </div>

    <div class="collapse navbar-collapse navbar-ex1-collapse">

        <ul class="nav navbar-right top-nav">
            <li><a href="{{ route('logout') }}">Se déconnecter</a></li>
        </ul>

        <ul class="nav navbar-nav side-nav">
            <li @if (Request::is('/'))class="active"@endif><a href="{{ route('dashboard') }}"><i class="fa fa-fw fa-dashboard"></i> Tableau de bord</a></li>

            <li @if(isset($current_facility) && $current_facility->id)class="active"@endif>
                <a data-toggle="collapse" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-bar-chart"></i> Sites <i class="fa fa-fw fa-caret-down"></i></a>
                <ul class="collapse in">
                    @if (count($facilities) > 0)
                        @foreach ($facilities as $f)
                            <li @if(isset($current_facility) && isset($current_facility->id) && $f->id === $current_facility->id)class="active"@endif><a href="{{ route('facility', array('id' => $f->id)) }}">{{ $f->name }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </li>

        @if (Auth::user() && Auth::user()->is_administrator)
                <li><a href="{{ route('facilities') }}"><i class="fa fa-fw fa-cogs"></i> Gestion des sites</a></li>
                <li><a href="{{ route('clients') }}"><i class="fa fa-fw fa-briefcase"></i> Gestion des clients</a></li>
                <li><a href="{{ route('users') }}"><i class="fa fa-fw fa-users"></i> Gestion des utilisateurs</a></li>
            @endif

        </ul>
    </div>
</nav>