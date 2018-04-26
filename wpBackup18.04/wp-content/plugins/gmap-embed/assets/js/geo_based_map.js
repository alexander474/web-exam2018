function initWpGmap(lat, lng) {
        initAutocomplete('map', 'pac-input', lat, lng, 'roadmap', 13);
}
var tryAPIGeolocation = function () {
    jQuery.post("https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyBcVcz5OZ6eNBi5d7CFYHIdtsEI5BQlm68", function (success) {
        initWpGmap(success.location.lat, success.location.lng);
    })
        .fail(function (err) {
            console.log("API Geolocation error! \n\n" + err);
        });
};
var browserGeolocationSuccess = function (position) {
    initWpGmap(position.coords.latitude, position.coords.longitude);
};

var browserGeolocationFail = function (error) {
    switch (error.code) {
        case error.TIMEOUT:
            console.log("Browser geolocation error !\n\nTimeout.");
            initWpGmap(40.73359922990751, -74.02791395625002);
            break;
        case error.PERMISSION_DENIED:
            tryAPIGeolocation();
            break;
        case error.POSITION_UNAVAILABLE:
            console.log("Browser geolocation error !\n\nPosition unavailable.");
            initWpGmap(40.73359922990751, -74.02791395625002);
            break;

    }
};

var tryGeolocation = function () {
    initWpGmap(40.73359922990751, -74.02791395625002);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            browserGeolocationSuccess,
            browserGeolocationFail,
            {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true});
    } else {
        initWpGmap(40.73359922990751, -74.02791395625002);
    }
};

tryGeolocation();