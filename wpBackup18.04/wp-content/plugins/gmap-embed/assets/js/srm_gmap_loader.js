function wpgmapSetAddressByLatLng(lat, lng, id) {
    jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + lng + '&sensor=true')
        .done(function (location) {

            document.getElementById('wpgmap_map_address').value = location.results[0].formatted_address;

        });

}
var map;
var marker1;
// to render Google Map
function initAutocomplete(id, input, center_lat, center_lng, map_type, zoom) {
    if (typeof map == 'object') {
        map.setCenter({lat: center_lat, lng: center_lng});
        marker1 = new google.maps.Marker({
            position: new google.maps.LatLng(center_lat, center_lng),
            title: "",
            draggable: true,
            animation: google.maps.Animation.DROP
        });
        marker1.setMap(map);
        marker1.addListener('dragend', function (markerLocation) {
            document.getElementById("wpgmap_latlng").value = markerLocation.latLng.lat() + "," + markerLocation.latLng.lng();
            wpgmapSetAddressByLatLng(markerLocation.latLng.lat(), markerLocation.latLng.lng());
        });
        return;
    }
    document.getElementById("wpgmap_latlng").value = center_lat + "," + center_lng;

    wpgmapSetAddressByLatLng(center_lat, center_lng);

    map = new google.maps.Map(document.getElementById(id), {
        center: {lat: center_lat, lng: center_lng},
        zoom: zoom,
        mapTypeId: map_type
    });
    marker1 = new google.maps.Marker({
        position: new google.maps.LatLng(center_lat, center_lng),
        title: "",
        draggable: true,
        animation: google.maps.Animation.DROP
    });
    marker1.setMap(map);
    // Create the search box and link it to the UI element.
    var input = document.getElementById(input);
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function () {
        searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function () {
        marker1.setMap(null);
        console.log(marker1);
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }
        marker1.setMap(null);
        // Clear out the old markers.
        markers.forEach(function (marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function (place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                title: place.name,
                draggable: true,
                position: place.geometry.location
            }));

            document.getElementById("wpgmap_latlng").value = place.geometry.location.lat() + "," + place.geometry.location.lng();
            wpgmapSetAddressByLatLng(place.geometry.location.lat(), place.geometry.location.lng());

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
        markers[0].addListener('dragend', function (markerLocation) {
            document.getElementById("wpgmap_latlng").value = markerLocation.latLng.lat() + "," + markerLocation.latLng.lng();
            wpgmapSetAddressByLatLng(markerLocation.latLng.lat(), markerLocation.latLng.lng());
        });

    });

}