var destination;
var start;
var type = 'transport';
var currentURL;
var runFjerdingen = true;
var runKirkegata = true;
var runVulkan = true;
var runBrenneriveien = true;


function main(){
   start = getAllUrlParams().origin;
    currentURL = window.location.pathname;
    switch(start){
        case "fjerdingen":
            greyOutButton("buttonFjerdingen");
            greyOutButton("mobButtonFjerdingen");
            runFjerdingen = false;
            break;
        case "kirkegata":
            greyOutButton("buttonKirkegata");
            greyOutButton("mobButtonKirkegata");
            runKirkegata = false;
            break;
        case "vulkan":
            greyOutButton("buttonVulkan");
            greyOutButton("mobButtonVulkan");
            runVulkan = false;
            break;
        case "brenneriveien":
            greyOutButton("buttonBrenneriveien");
            greyOutButton("mobButtonBrenneriveien");
            runBrenneriveien = false;
            break;
    }
    document.getElementById("buttonFjerdingen").addEventListener("click", function( event ) {
        if(runFjerdingen){
        destination = 'fjerdingen';
        window.location.href = currentURL+'/reisevei-resultat?origin='+start+'&destination='+destination+
        '&type='+type;
        }
    });
    document.getElementById("buttonKirkegata").addEventListener("click", function( event ) {
        if(runKirkegata){
        destination = 'kirkegata';
        window.location.href = currentURL+'/reisevei-resultat?origin='+start+'&destination='+destination+
        '&type='+type;
        }
    });
    document.getElementById("buttonVulkan").addEventListener("click", function( event ) {
        if(runVulkan){
        destination = 'vulkan';
        window.location.href = currentURL+'/reisevei-resultat?origin='+start+'&destination='+destination+
        '&type='+type;
        }
    });
    document.getElementById("buttonBrenneriveien").addEventListener("click", function( event ) {
        if(runBrenneriveien){
        destination = 'brenneriveien';
        window.location.href = currentURL+'/reisevei-resultat?origin='+start+'&destination='+destination+
        '&type='+type;
        }
    });

    document.getElementById("mobButtonFjerdingen").addEventListener("click", function( event ) {
        if(runFjerdingen){
            destination = 'fjerdingen';
            window.location.href = currentURL+'/reisevei-resultat?origin='+start+'&destination='+destination+
            '&type='+type;
            }
    });
    document.getElementById("mobButtonKirkegata").addEventListener("click", function( event ) {
        if(runKirkegata){
            destination = 'kirkegata';
            window.location.href = currentURL+'/reisevei-resultat?origin='+start+'&destination='+destination+
            '&type='+type;
            }
    });
    document.getElementById("mobButtonVulkan").addEventListener("click", function( event ) {
        if(runVulkan){
            destination = 'vulkan';
            window.location.href = currentURL+'/reisevei-resultat?origin='+start+'&destination='+destination+
            '&type='+type;
            }
    });
    document.getElementById("mobButtonBrenneriveien").addEventListener("click", function( event ) {
        if(runBrenneriveien){
            destination = 'brenneriveien';
            window.location.href = currentURL+'/reisevei-resultat?origin='+start+'&destination='+destination+
            '&type='+type;
            }
    });
}

function greyOutButton(buttonDiv){
        var startDiv = document.getElementById(buttonDiv);
        startDiv.style.backgroundColor = 'rgb(128,128,128)';
        startDiv.style.opacity = '0.5';
        startDiv.style.cursor = 'default !important';
        startDiv.innerHTML = '<p id="youAreHere"></p>';
        youAreHereP = document.getElementById('youAreHere');
        youAreHereP.style.textAlign = 'center';
}

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

  main();