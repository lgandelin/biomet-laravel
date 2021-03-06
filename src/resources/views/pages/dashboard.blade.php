@extends('biomet::default')

@section('page-title'){{ trans('biomet::dashboard.meta_title') }}@endsection

@section('page-content')
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
    </div>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
    <script type="text/javascript">

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