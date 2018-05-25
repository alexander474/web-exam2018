var map; //the map
var infoWindow;
var isMobile = false; //initiate as false

var service;
var searchKeyword;
var searchRadius = 250;

var searchMarksArray = [];

var currentPosMark; //you're geolocation point
var startingPoint; //the starting point of route
var destination; //destination/end of route
var travelType = 'TRANSIT';
var buttonType;
var buttonTextTranslate;

var directionsService; //the service to calculate the route
var directionsDisplay; //the service to display and where to display route
var iconTypes;

var iconMap = 'https://tek.westerdals.no/~breale17/wp/wp-content/themes/flatsome-child/images/';
//var iconMap = './images/' //ofline TEST path

var currentPressedButton;
var lastPressedButton;


//Adds the locator controller on map and executes the getgeolocation function.
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

function addHTML(elementType, type, id, clas, value, div) {
  //Create an input type dynamically.   
  var element = document.createElement(elementType);
  //Assign different attributes to the element. 
  element.setAttribute("type", type);
  element.setAttribute("id", id);
  element.setAttribute("class", clas);
  element.setAttribute("value", value);

  var div = document.getElementById(div);
  //Append the element in page (in span).  
  div.appendChild(element);
}
function addText(divID, text){
  var theDiv = document.getElementById(divID);
  theDiv.innerHTML = "";
  var content = document.createTextNode(text);
  theDiv.appendChild(content);
}

function addingBaseHTML(){
  addHTML("div", null, "infoTopContainer", null, null, "mapContainer");
  addHTML("div", null, "map", null, null, "mapContainer");
  addHTML("div", null, "rightWindow", null, null, "mapContainer");
}

function addingHTML(){
  console.log('BUTTON TYPE FUNCTION RUNNING');
  if(buttonType == 'transport'){
    addHTML("BUTTON", "button", "transitBtn", "searchBtn", "TRANSIT", "infoTopContainer");
    addHTML("BUTTON", "button", "walkingBtn", "searchBtn", "WALKING", "infoTopContainer");
    addHTML("BUTTON", "button", "bicyclingBtn", "searchBtn", "BICYCLING", "infoTopContainer");
    /*
    addHTML("div", null, null, "transitBox", null, "rightWindow");
    addHTML("div", null, null, "transitBox", null, "rightWindow");
    addHTML("div", null, null, "transitBox", null, "rightWindow");
    addHTML("div", null, null, "transitBox", null, "rightWindow");
    addHTML("div", null, null, "transitBox", null, "rightWindow");
    addHTML("div", null, null, "transitBox", null, "rightWindow");
    */
  }
  if(buttonType == 'search'){
    addHTML("BUTTON", "button", "cafeBtn", "searchBtn", "cafe", "infoTopContainer");
    addHTML("BUTTON", "button", "barBtn", "searchBtn", "bar", "infoTopContainer");
    addHTML("BUTTON", "button", "grocery_storeBtn", "searchBtn", "grocery", "infoTopContainer");
    addHTML("BUTTON", "button", "parkBtn", "searchBtn", "park", "infoTopContainer");
    var mapDiv = document.getElementById('map');
    mapDiv.style.width = '99%';
    var wrapper = document.getElementById('mapContainer');
    wrapper.style.width = '400px';
  }
  addHTML("div", null, "buttonTextDiv", null, null, "infoTopContainer")
}

function onMobile(){
  if(isMobile == true){
    var mapDiv = document.getElementById('map');
    mapDiv.style.display = 'none';
    var rightWindowDiv = document.getElementById('rightWindow');
    rightWindowDiv.style.cssFloat = 'left';
    rightWindowDiv.style.width = '99%';
    var wrapper = document.getElementById('mapContainer');
    wrapper.style.width = '400px';
    var topDiv = document.getElementById('infoTopContainer');
  }
}

function setButtonUnderline(buttonID){  
  currentPressedButton = document.getElementById(buttonID);
  if(lastPressedButton != null){
  lastPressedButton.style.borderBottom = 'none'; 
  lastPressedButton = '';
 }
  currentPressedButton.style.borderBottom = '2px solid black'; 
  lastPressedButton = currentPressedButton;
  currentPressedButton = '';
}




/*
Initializes the map with info from the URL-parameters and displays the route.
sets the koordinates and traveltypes that is available.
*/
function initMap() {
      addingBaseHTML()
      // device detection
      if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
          || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
        isMobile = true;
      }
      onMobile();
      
      this.directionsService = new google.maps.DirectionsService();
      this.directionsDisplay = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
      });
      this.infoWindow = new google.maps.InfoWindow;

      this.iconTypes = {
          currentCampusIcon: this.iconMap+'school-location.png',
          endPoint: this.iconMap+'school-location.png',
          beachFlag: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
          default: this.iconMap+'location.png'
      };
      var campusLocation = {
          fjerdingen: {
              lat: 59.9160606, 
              lng: 10.7599732
          },
          brenneriveien: {
              lat: 59.9201627, 
              lng: 10.7506793
          },
          vulkan: {
              lat: 59.9233093261719, 
              lng: 10.7521686553955
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
     var buttonTypes = {
       search : 'search',
       transport: 'transport'
     }

     buttonTextTranslate = {
       cafe:'Kaféer',
       bar: 'Barer',
       grocery: 'Matbutikker',
       park: 'Parker',
       TRANSIT: 'Kollektivt',
       BICYCLING: 'Sykkel',
       WALKING: 'Gå'
     }

  startingPoint = campusLocation[getAllUrlParams().origin];
  destination = campusLocation[getAllUrlParams().destination];
  buttonType = buttonTypes[getAllUrlParams().type];
  console.log(getAllUrlParams());

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

    addingHTML();

    this.service = new google.maps.places.PlacesService(map);
    if(buttonType == 'transport'){
    document.getElementById("infoTopContainer").addEventListener("click", function( event ) {
      travelType = '';
      travelType = event.target.value;
      if(travelType != null){
        setButtonUnderline(event.target.id);
        addText("buttonTextDiv", buttonTextTranslate[travelType]);
        }
      console.log(travelType);
      if(destination != null && travelType != null){
      routeMarkers(destination, iconTypes['endPoint'], 'Destination', map);
      calculateAndDisplayRoute(directionsService, directionsDisplay)
      directionsDisplay.setMap(map);
      directionsDisplay.setPanel(document.getElementById('rightWindow'));
     }
    }, false);
  }
    if(buttonType == 'search'){
    document.getElementById("infoTopContainer").addEventListener("click", function( event ) {
      searchKeyword = '';
      searchKeyword = event.target.value;
      if(searchKeyword != null){
      setButtonUnderline(event.target.id);
      addText("buttonTextDiv", buttonTextTranslate[searchKeyword]);
      }
      console.log(searchKeyword);
      console.log(startingPoint);
      console.log(searchRadius);
      searchSettings();
    }, false);
  }
    addYourLocationButton(map, currentPosMark);

    routeMarkers(this.startingPoint, this.iconTypes['currentCampusIcon'], 'Start', this.map);

    console.log("Showing: (start, destination, traveltype, searchword, radius)");
    console.log(startingPoint);
    console.log(destination);
    console.log(travelType);
    console.log(searchKeyword);
    console.log("Search Radius: "+searchRadius+"m");
    directionsDisplay.setMap(map);
};


function routeMarkers(position, icon, title, map){
  var routeMarker = new google.maps.Marker({
    position: position,
    map: map,
    icon: {
      url: icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(40, 70),
      scaledSize: new google.maps.Size(90, 90)
    },
    title: title
  })
}

function searchSettings(){
  if(this.searchKeyword != null){
  service.nearbySearch({
      location: this.startingPoint,
      radius: this.searchRadius,
      keyword: this.searchKeyword
    },  createMarks);
  }
}


//create array of points of interest based on the keyword
function createMarks(results, status) {
  deleteSearchMarkers();
  if (status === google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      createMarker(results[i]);
      console.log(results[i]);
    }
  }
}

function deleteSearchMarkers(){
  for(var i=0; i<searchMarksArray.length; i++){
  searchMarksArray[i].setMap(null);
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
  searchMarksArray.push(marker);
  
  marker.addListener('click', function() {
    var request = {
      reference: place.reference
    };
    service.getDetails(request, function(details, status) {
        this.infoWindow.setContent([
        details.name,
        details.formatted_address,
        details.website,
        details.formatted_phone_number].join("<br />"));
        this.infoWindow.open(map, marker);
    });
  })
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
        icon: this.iconTypes['beachFlag'],
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
      origin: startingPoint,
      destination: destination,
      travelMode: travelType,
      unitSystem: google.maps.UnitSystem.METRIC,
    }, function(response, status) {
      if (status === 'OK') {
        directionsDisplay.setDirections(response);
      } else {
        window.alert('Directions request failed due to ' + status);
      }
    });
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