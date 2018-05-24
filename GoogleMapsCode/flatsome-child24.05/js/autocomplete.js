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

 jQuery(function autoComplete() {
    var availableRooms = [];
    for(i = 0; i < roomList.length; i++){
    availableRooms.push(roomList[i].Room);
  };
  
    jQuery( "#automplete-1" ).autocomplete({
       source: availableRooms
    });
 })
 var getMapButton = document.getElementById("getMapButton");
 if(getMapButton){
  getMapButton.addEventListener("click", getMap);
}
function getMap(){
    var x = document.getElementById("automplete-1").value;
    var floorName;
    for(i = 0; i < roomList.length; i++){
        if(roomList[i].Room===x){
          floorName = roomList[i].Map;
          };
        };
    var destinationDiv = document.getElementById("map");
    while (destinationDiv.firstChild) {
        destinationDiv.removeChild(destinationDiv.firstChild);
        }
    var floorImage = document.createElement("IMG");
    floorImage.setAttribute("src", floorName + ".png");
    floorImage.setAttribute("width", "400");
    floorImage.setAttribute("height", "228");
    floorImage.setAttribute("alt", "Floor Map");
    destinationDiv.appendChild(floorImage);
    }
