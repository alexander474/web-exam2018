var map; //the map
var infoWindow, poiWindow;

var service;
var searchKeyword;
var searchRadius = 250;

var currentPosMark; //you're geolocation point
var startingPoint; //the starting point of route
var destination; //destination/end of route
var travelType; //how you want to travel? (DRIVING, TRANSIT, etc..)

var directionsService; //the service to calculate the route
var directionsDisplay; //the service to display and where to display route
var iconTypes;

var iconMap = 'https://tek.westerdals.no/~breale17/wp/wp-content/themes/flatsome-child/images/';
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

google.maps.event.addListener(map, 'dragend', function(){
  jQuery('#you_location_img').css('background-position', '0px 0px');
});

firstChild.addEventListener('click', function(){
    var imgX = '0';
    var animationInterval = setInterval(function(){
        if(imgX == '-18') imgX = '0';
        else imgX = '-18';
        jQuery('#you_location_img').css('background-position', imgX+'px 0px');
    }, 500);
    if(navigator.geolocation) {
        getGeoLocation();
    }
    else{
        clearInterval(animationInterval);
        jQuery('#you_location_img').css('background-position', '0px 0px');
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
      this.directionsService = new google.maps.DirectionsService();
      this.directionsDisplay = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
      });
      this.infoWindow = new google.maps.InfoWindow;
      this.poiWindow = new google.maps.InfoWindow;

      this.iconTypes = {
          currentCampusIcon: this.iconMap+'school-location.png',
          endPoint: this.iconMap+'school-location.png',
          default: this.iconMap+'location.png'
      };
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
          otori: {
              lat: parseFloat(getAllUrlParams().orilat),
              lng: parseFloat(getAllUrlParams().orilng)
          },
          otdest: {
              lat: parseFloat(getAllUrlParams().destlat),
              lng: parseFloat(getAllUrlParams().destlng)
          }
      };
    var travelTypes = {
      driving: 'DRIVING',
      walking: 'WALKING',
      bicycling: 'BICYCLING',
      transit: 'TRANSIT'
    };

  this.startingPoint = campusLocation[getAllUrlParams().origin];
  this.destination = campusLocation[getAllUrlParams().destination];
  this.travelType = travelTypes[getAllUrlParams().transporttype];
  this.searchKeyword = getAllUrlParams().search;
  //map style settings, currently set to turn off all of Google's points of interest (poi)
    var styles = {
      default: null,
      hide: [
        {
          featureType: 'poi',
          stylers: [{visibility: 'off'}]
        },
        ]
    };

    //Map Options
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

    //setting the map styles
    map.setOptions({styles: styles['hide']});

    this.service = new google.maps.places.PlacesService(map);
    //find local points of interest by keyword
    if(this.searchKeyword != null){
    service.nearbySearch({
      location: this.startingPoint,
      radius: this.searchRadius,
      keyword: this.searchKeyword
  }, createMarks);
}
    addYourLocationButton(map, currentPosMark);

    makeMarker(this.startingPoint, this.iconTypes['currentCampusIcon'], 'title', this.map);
    makeMarker(this.destination, this.iconTypes['endPoint'], 'title', this.map);
    
    console.log(this.startingPoint);
    console.log(this.destination);
    console.log(this.travelType);
    console.log(this.searchKeyword);
    calculateAndDisplayRoute(this.directionsService, this.directionsDisplay)
    directionsDisplay.setMap(this.map);
};

function makeMarker(position, icon, title, map){
  new google.maps.Marker({
    position: position,
    map: map,
    icon: {
      url: icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(90, 90)
    },
    title: title
  })
}

//create array of points of interest based on the keyword
function createMarks(results, status) {
  if (status === google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      createMarker(results[i]);
    }
  }
}

//create markers for all the points of interest found in the callback function
function createMarker(place) {
  
  var placeLoc = place.geometry.location;
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
    icon: {
      url: this.iconTypes['default'],
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(50, 50)
    }
  });
  marker.addListener('click', function() {
    var request = {
      reference: place.reference
    };
    service.getDetails(request, function(details, status) {
        this.poiWindow.setContent([
        details.name,
        details.formatted_address,
        details.website,
        details.formatted_phone_number].join("<br />"));
        this.poiWindow.open(map, marker);
    });
  })
}

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
        infoWindow.setPosition(pos);
        infoWindow.setContent('You are here');
        //infoWindow.open(map);
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
  //infoWindow.open(map);
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
      unitSystem: google.maps.UnitSystem.METRIC,
    }, function(response, status) {
      if (status === 'OK') {
        directionsDisplay.setDirections(response);
      } else {
        window.alert('Directions request failed due to ' + status);
      }
    });
}