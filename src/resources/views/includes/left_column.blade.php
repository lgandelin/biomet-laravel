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
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-fw fa-dashboard"></i> Tableau de bord</a></li>

            @if (Auth::user() && Auth::user()->is_administrator)
                <li><a href="{{ route('users') }}"><i class="fa fa-fw fa-user"></i> Gestion des utilisateurs</a></li>
                <li><a href="{{ route('clients') }}"><i class="fa fa-fw fa-users"></i> Gestion des clients</a></li>
                <li><a href="{{ route('facilities') }}"><i class="fa fa-fw fa-tachometer"></i> Gestion des sites</a></li>
            @endif

        </ul>
    </div>
</nav>