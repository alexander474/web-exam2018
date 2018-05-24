<?php
function my_gmaps() {
    wp_enqueue_style( 'my_gmaps_style', get_stylesheet_directory_uri() . 
    '/css/Gmaps.css' );
    if ( !is_admin() ) {
        wp_enqueue_script('jquery');
        wp_enqueue_script ( 'my_gmaps_script', get_stylesheet_directory_uri() . 
        '/js/Gmaps.js' , array( 'jquery' ), false, true );
        /*
        wp_enqueue_script ( 'googleapis', 
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyDV_mIG20yMqdE1FddkaSUMxlBejEFEW-Y&libraries=places&callback=initMap', 
        array('my_gmaps_script'), false, true );
        */
    }
}

add_action('wp_enqueue_scripts','my_gmaps');

function maps_page() {
    include "maps-Page.php";
}
/*
function add_async_defer_attribute($tag, $handle) {
	if ( 'googleapis' !== $handle )
	return $tag;
	return str_replace( ' src', ' async defer src', $tag );
}
add_filter('script_loader_tag', 'add_async_defer_attribute', 10, 2);
*/
function set_google_map($atts, $content = null ){

    $content = maps_page();
    return $content;
}
add_shortcode('set_google_map', 'set_google_map');
?>