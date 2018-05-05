var map; //the map
var currentPosMark; //you're geolocation point
var startingPoint; //the starting point of route
var destination; //destination/end of route
var travelType; //how you want to travel? (DRIVING, TRANSIT, etc..)

var directionsService; //the service to calculate the route
var directionsDisplay; //the service to display and where to display route


var icon = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';

/*
Adds the locator controller on map and executes the getgeolocation function.
*/
function addYourLocationButton(map, marker){
var controlDiv = document.createElement('div');

var firstChild = document.createElement('button');
firstChild.style.backgroundColor = '#fff';
firstChild.style.border = 'none';
firstChild.style.outline = 'none';
firstChild.style.width = '28px';
firstChild.style.height = '28px';
firstChild.style.borderRadius = '2px';
firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
firstChild.style.cursor = 'pointer';
firstChild.style.marginRight = '10px';
firstChild.style.padding = '0px';
firstChild.title = 'Your Location';
controlDiv.appendChild(firstChild);

var secondChild = document.createElement('div');
secondChild.style.margin = '5px';
secondChild.style.width = '18px';
secondChild.style.height = '18px';
secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)';
secondChild.style.backgroundSize = '180px 18px';
secondChild.style.backgroundPosition = '0px 0px';
secondChild.style.backgroundRepeat = 'no-repeat';
secondChild.id = 'you_location_img';
firstChild.appendChild(secondChild);

google.maps.event.addListener(map, 'dragend', function() {
    $('#you_location_img').css('background-position', '0px 0px');
});

firstChild.addEventListener('click', function() {
    var imgX = '0';
    var animationInterval = setInterval(function(){
        if(imgX == '-18') imgX = '0';
        else imgX = '-18';
        $('#you_location_img').css('background-position', imgX+'px 0px');
    }, 500);
    if(navigator.geolocation) {
        getGeoLocation();
    }
    else{
        clearInterval(animationInterval);
        $('#you_location_img').css('background-position', '0px 0px');
    }
});

controlDiv.index = 1;
map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
}

/*
Initializes the map with info from the URL-parameters and displays the route.
sets the koordinates and traveltypes that is available.
*/
function initMap() {
  var campusLocation = {
        fjerdingen: {
            lat: 59.9160546, 
            lng: 10.7586542
        },
        brenneriveien: {
            lat: 59.9201627, 
            lng: 10.7506793
        },
        vulkan: {
            lat: 59.9231527, 
            lng: 10.7516181
        },
        kirkegata: {
            lat: 59.9111719, 
            lng: 10.742772
        },
    };

    var travelTypes = {
      driving: 'DRIVING',
      walking: 'WALKING',
      bicycling: 'BICYCLING ',
      transit: 'TRANSIT'
    }

    this.directionsService = new google.maps.DirectionsService();
    this.directionsDisplay = new google.maps.DirectionsRenderer();
    //Map Options
    this.startingPoint = campusLocation[getAllUrlParams().origin];
    this.destination = campusLocation[getAllUrlParams().destination];
    this.travelType = travelTypes[getAllUrlParams().transporttype];
    console.log(getAllUrlParams());
    this.map = new google.maps.Map(document.getElementById('map'), {
      center: this.startingPoint,
      zoom: 14,
      mapTypeControl: true,
      mapTypeControlOptions: {
        style: google.maps.MapTypeControlStyle.HORIZONTAL_DEFAULT,
        position: google.maps.ControlPosition.LEFT_TOP,
        mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain']
      },
    });
    addYourLocationButton(map, currentPosMark);
    
    console.log(this.startingPoint);
    console.log(this.destination);
    console.log(this.travelType);
    calculateAndDisplayRoute(this.directionsService, this.directionsDisplay)
    directionsDisplay.setMap(this.map);
};

/*
Gets the url and filters the parameters and returns a object that can be used to save the parameter to a local variable.
*/
function getAllUrlParams(url) {

// get query string from url (optional) or window
var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

// we'll store the parameters here
var obj = {};

// if query string exists
if (queryString) {

  // stuff after # is not part of query string, so get rid of it
  queryString = queryString.split('#')[0];

  // split our query string into its component parts
  var arr = queryString.split('&');

  for (var i=0; i<arr.length; i++) {
    // separate the keys and the values
    var a = arr[i].split('=');

    // in case params look like: list[]=thing1&list[]=thing2
    var paramNum = undefined;
    var paramName = a[0].replace(/\[\d*\]/, function(v) {
      paramNum = v.slice(1,-1);
      return '';
    });

    // set parameter value (use 'true' if empty)
    var paramValue = typeof(a[1])==='undefined' ? true : a[1];

    // (optional) keep case consistent
    paramName = paramName.toLowerCase();
    paramValue = paramValue.toLowerCase();

    // if parameter name already exists
    if (obj[paramName]) {
      // convert value to array (if still string)
      if (typeof obj[paramName] === 'string') {
        obj[paramName] = [obj[paramName]];
      }
      // if no array index number specified...
      if (typeof paramNum === 'undefined') {
        // put the value on the end of the array
        obj[paramName].push(paramValue);
      }
      // if array index number specified...
      else {
        // put the value at that index number
        obj[paramName][paramNum] = paramValue;
      }
    }
    // if param name doesn't exist yet, set it
    else {
      obj[paramName] = paramValue;
    }
}
}

return obj;
}

  /*
  Gets you're current location (raltime location) and places a marker on the map, map centers around the location.
  */
  function getGeoLocation(){
        //Getting current position        
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        this.currentPosMark = new google.maps.Marker({
        position: pos,
        map: this.map,
        icon: this.icon,
        animation: google.maps.Animation.DROP,
        title: 'You are here!'
         });
        map.setCenter(pos);
      }, function() {
        handleLocationError(true, infoWindow, this.map.getCenter());
      });
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, this.map.getCenter());
    }
  }
  /*
  Handels the errors from the getGeoLocation function.
  */
  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  window.alert(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
  }

/*
function setDestination(koordinates){
  this.destination = koordinates;
  calculateAndDisplayRoute(directionsService, directionsDisplay);
}
*/
function calculateAndDisplayRoute(directionsService, directionsDisplay) {
      directionsService.route({
      origin: this.startingPoint,
      destination: this.destination,
      travelMode: this.travelType,
      unitSystem: google.maps.UnitSystem.METRIC
    }, function(response, status) {
      if (status === 'OK') {
        directionsDisplay.setDirections(response);
      } else {
        window.alert('Directions request failed due to ' + status);
      }
    });
}