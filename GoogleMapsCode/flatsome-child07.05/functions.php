<?php 

function my_gmaps() {
    wp_enqueue_style( 'my_gmaps_style', get_stylesheet_directory_uri() . 
    '/css/Gmaps.css' );
    if ( !is_admin() ) {
        wp_enqueue_script('jquery');
        wp_enqueue_script ( 'my_gmaps_script', get_stylesheet_directory_uri() . 
        '/js/Gmaps.js' , array( 'jquery' ), false, true );
        wp_enqueue_script ( 'googleapis', 
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyDV_mIG20yMqdE1FddkaSUMxlBejEFEW-Y&libraries=places&callback=initMap', 
        array('my_gmaps_script'), false, true );
    }
}

add_action('wp_enqueue_scripts','my_gmaps');


function add_async_defer_attribute($tag, $handle) {
	if ( 'googleapis' !== $handle )
	return $tag;
	return str_replace( ' src', ' async defer src', $tag );
}
add_filter('script_loader_tag', 'add_async_defer_attribute', 10, 2);

function set_google_map($atts, $content = null ){

    $content = '<div style="width:500px;height:600px;" id="map" ></div>';
    
    return $content;
}
add_shortcode('set_google_map', 'set_google_map');

/* Usage:
[set_google_map"]
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