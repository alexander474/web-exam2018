<?php 

function my_gmaps() {
    wp_enqueue_style( 'my_gmaps_style', get_stylesheet_directory_uri() . '/css/Gmaps.css' );
    if ( !is_admin() ) {
        wp_enqueue_script ( 'Gmaps', 'http://maps.google.com/maps/api/js?sensor=false', array(), false, true );
        wp_enqueue_script ( 'my_gmaps_script', get_stylesheet_directory_uri() . '/js/Gmaps.js' , array( 'Gmaps' ), '1.0', true );
    }
}

add_action('wp_enqueue_scripts','my_gmaps');

function set_google_map($atts, $content = null ){
    global $add_my_script;
    $add_my_script = true;

    $content = '<div style="width:500px;height:600px;" id="map" ></div>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDV_mIG20yMqdE1FddkaSUMxlBejEFEW-Y&callback=initMap">
    </script>';
    
    return $content;
}
add_shortcode('set_google_map', 'set_google_map');

/* Usage:
[set_google_map width="400px" height="350px"]
width:500px;height:600px; background-color: red;
width:$width;height:$height;
*/
/* Usage:
[put_google_map width="400px" height="350px" align="left" zoom="17" 
mlat="33.910859" mlon="-98.489586" clat="33.911499" clon="-98.489586" 
mtitle="Alley Cat Collective"]<strong>Alley Cat Collective</strong>
<br/>922 Indiana Ave<br/>Across from Wichita Theatre[/put_google_map]
Helpful Link: http://itouchmap.com/latlong.html 
*/