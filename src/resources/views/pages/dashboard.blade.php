@extends('biomet::default')

@section('page-title'){{ trans('biomet::dashboard.meta_title') }}@endsection

@section('page-content')
    <h1>{{ trans('biomet::dashboard.title') }}</h1>

    <div class="dashboard-template">

        @if (isset($error))
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif

        @if (isset($confirmation))
            <div class="alert alert-success">
                {{ $confirmation }}
            </div>
        @endif

        <div id="map-canvas" class="google-map"></div>

        <h2>{{ trans('biomet::dashboard.facilities_list') }}</h2>

        <div class="search-bar">
            <input autocomplete="off" class="search-input" type="text" placeholder="Recherche" />
        </div>

        <ul class="facilities">
            @if (count($facilities) > 0)
                @foreach ($facilities as $facility)
                    <li><i class="fa fa-fw fa-map-marker"></i><a href="{{ route('facility', array('id' => $facility->id)) }}">{{ $facility->name }}</a></li>
                @endforeach
            @endif
        </ul>
    </div>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
    <script type="text/javascript">

        $('.dashboard-template .search-bar input').on('keyup', function() {
            apply_search_filters();
        });

        function apply_search_filters() {
            $('.dashboard-template .facilities li').each(function() {
                var show = false;


                //Search bar
                var input_search = $('.dashboard-template .search-bar input');

                if ((input_search.val().length == 0) || ($(this).is(':contains("' + input_search.val() + '")'))) {
                    show = true;
                }

                if (show)
                    $(this).fadeIn();
                else
                    $(this).fadeOut();
            });
        }

        //Overwrites ":contains" jQuery selector
        $.expr[':'].contains = function(a, i, m) {
            return $(a).text().toUpperCase()
                .indexOf(m[3].toUpperCase()) >= 0;
        };

        function initialize() {

            var mapOptions = {
                center: new google.maps.LatLng(46.5279357, 3.0731843),
                zoom: 5
            };
            var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

            //Map styles
            var styles = [{"featureType": "road", "stylers": [ { "visibility": "off" } ] },{ "featureType": "administrative", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "administrative.country", "stylers": [ { "visibility": "on" } ] },{ "featureType": "landscape", "stylers": [ { "visibility": "simplified" } ] } ];
            var styledMap = new google.maps.StyledMapType(styles, {name: "Map"});

            map.mapTypes.set('map_style', styledMap);
            map.setMapTypeId('map_style');

            @if ($facilities)
                @foreach ($facilities as $i => $facility)

                    var image = '{{ asset("img/tag.png") }}';
                    var marker_{{ $i }} = new google.maps.Marker({
                        position: new google.maps.LatLng({{ $facility->latitude }}, {{ $facility->longitude }}),
                        map: map,
                        icon: image,
                        title: '{{ $facility->name }}'
                    });

                    var infoWindow_{{ $i }} = new google.maps.InfoWindow({
                        content: '<div class="map-info"><span class="name">{{ $facility->name }}</span>' + '<a href="{{ route('facility', array('id' => $facility->id)) }}">Accéder au site' + '</div>'
                    });

                    google.maps.event.addListener(marker_{{ $i }}, 'click', function() {
                        infoWindow_{{ $i }}.open(map, marker_{{ $i }});
                    });

                @endforeach
            @endif
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@stop