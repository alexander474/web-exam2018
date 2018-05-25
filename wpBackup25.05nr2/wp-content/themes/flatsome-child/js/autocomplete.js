var textValue = "";
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              textValue = inp.value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }

  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
      });
}

var roomList = [
  {Room:"F100",Map:"floor1"},
  {Room:"F101",Map:"floor1"},
  {Room:"F102",Map:"floor1"},
  {Room:"F103",Map:"floor1"},
  {Room:"F200",Map:"floor2"},
  {Room:"F201",Map:"floor2"},
  {Room:"F202",Map:"floor2"},
  {Room:"F203",Map:"floor2"},
  {Room:"F204",Map:"floor2"},
  {Room:"F205",Map:"floor2"},
  {Room:"F206",Map:"floor2"},
  {Room:"F207",Map:"floor2"},
  {Room:"F208",Map:"floor2"},
  {Room:"F209",Map:"floor2"},
  {Room:"F210",Map:"floor2"},
  {Room:"F301",Map:"floor3"},
  {Room:"F302",Map:"floor3"},
  {Room:"F303",Map:"floor3"},
  {Room:"F304",Map:"floor3"},
  {Room:"F305",Map:"floor3"},
  {Room:"F306",Map:"floor3"},
  {Room:"F307",Map:"floor3"},
  {Room:"F308",Map:"floor3"},
  {Room:"F309",Map:"floor3"},
  {Room:"F310",Map:"floor3"},
  {Room:"F311",Map:"floor3"},
  {Room:"F312",Map:"floor3"},
  {Room:"F34-101",Map:"floor3"},
  {Room:"F34-102",Map:"floor3"},
  {Room:"F34-103",Map:"floor3"},
  {Room:"F34-104",Map:"floor3"},
  {Room:"F501",Map:"floor5"},
  {Room:"F502",Map:"floor5"},
  {Room:"F503",Map:"floor5"},
  {Room:"F504",Map:"floor5"},
  {Room:"F601",Map:"floor6"},
  {Room:"F602",Map:"floor6"},
  {Room:"F603",Map:"floor6"},
  {Room:"F604",Map:"floor6"},
  {Room:"F605",Map:"floor6"},
  {Room:"F606",Map:"floor6"},
  {Room:"F607",Map:"floor6"},
  {Room:"F608",Map:"floor6"},
  {Room:"F609",Map:"floor6"},
  {Room:"F610",Map:"floor6"},
  {Room:"F611",Map:"floor6"},
  {Room:"F612",Map:"floor6"},
  {Room:"F613",Map:"floor6"},
  {Room:"F614",Map:"floor6"},
  {Room:"F615",Map:"floor6"},
  {Room:"FU100",Map:"flooru1"},
  {Room:"FU101",Map:"flooru1"},
  {Room:"FU102",Map:"flooru1"},
  {Room:"FU103",Map:"flooru1"},
  {Room:"FU104",Map:"flooru1"},
  {Room:"FU105",Map:"flooru1"},
  {Room:"FU106",Map:"flooru1"},
  {Room:"FU107",Map:"flooru1"},
  {Room:"FU108",Map:"flooru1"},
  {Room:"FU109",Map:"flooru1"},
  {Room:"FU110",Map:"flooru1"},
  {Room:"FU111",Map:"flooru1"},
  {Room:"FU112",Map:"flooru1"},
  {Room:"FU113",Map:"flooru1"},
  {Room:"FU114",Map:"flooru1"},
  {Room:"FU115",Map:"flooru1"},
  {Room:"FU116",Map:"flooru1"},
  {Room:"FU117",Map:"flooru1"},
  {Room:"FU200",Map:"flooru2"},
  {Room:"FU201",Map:"flooru2"},
  {Room:"FU202",Map:"flooru2"},
  {Room:"FU203",Map:"flooru2"},
  {Room:"FU204",Map:"flooru2"},
  {Room:"FU205",Map:"flooru2"},
  {Room:"FU206",Map:"flooru2"},
  {Room:"FU207",Map:"flooru2"},
  {Room:"FU208",Map:"flooru2"},
  {Room:"FU209",Map:"flooru2"},
  {Room:"FU210",Map:"flooru2"},
  {Room:"FU211",Map:"flooru2"},
  {Room:"FU212",Map:"flooru2"},
  {Room:"FU213",Map:"flooru2"},
  {Room:"FU214",Map:"flooru2"},
  {Room:"FU215",Map:"flooru2"},
  {Room:"FU216",Map:"flooru2"}];

var availableRooms = [];
for(i = 0; i < roomList.length; i++){
  availableRooms.push(roomList[i].Room)
};


document.getElementById("button").addEventListener('click',function getMap(){
  var floorName;
  textValue = document.getElementById("rooms").value;
  for(i = 0; i < roomList.length; i++){
      if(roomList[i].Room===textValue){
        floorName = roomList[i].Map;
        };
      };
  var destinationDiv = document.getElementById("map");
  while (destinationDiv.firstChild) {
      destinationDiv.removeChild(destinationDiv.firstChild);
      }
  var floorImage = document.createElement("IMG");
  floorImage.setAttribute("src", "https://tek.westerdals.no/~breale17/wp/wp-content/themes/flatsome-child/images/plans/" + floorName + "_" + textValue + ".png");
  floorImage.setAttribute("width", "400");
  floorImage.setAttribute("height", "228");
  floorImage.setAttribute("alt", "Floor Map");
  destinationDiv.appendChild(floorImage);
});

autocomplete(document.getElementById("rooms"), availableRooms);