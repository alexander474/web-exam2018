<?php
function my_gmaps() {
    wp_enqueue_style( 'my_gmaps_style', get_stylesheet_directory_uri() . 
    '/css/Gmaps.css' );
    if ( !is_admin() ) {
        wp_enqueue_script('jquery');
        wp_enqueue_script ( 'my_gmaps_script', get_stylesheet_directory_uri() . 
        '/js/Gmaps.js' , array( 'jquery' ), false, true );
    }
}

add_action('wp_enqueue_scripts','my_gmaps');

function maps_page() {
    include "maps-Page.php";
}

function set_google_map($atts, $content = null ){

    $content = maps_page();
    return $content;
}
add_shortcode('set_google_map', 'set_google_map');
?>