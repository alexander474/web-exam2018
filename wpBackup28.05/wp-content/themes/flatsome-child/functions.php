<?php 

include "mapsShortCode.php"; //Google Maps ShortCode
include "autoCompleteShortCode.php"; //Rom Search ShortCode
include "ButtonShortcode.php"; //Button URL

function mytheme_custom_scripts(){
        $scriptSrc = get_template_directory_uri() . '/assets/js/jquery_ui.js';
        wp_enqueue_script( 'myhandle', $scriptSrc , array(), '1.0',  true );
}
add_action( 'wp_enqueue_scripts', 'mytheme_custom_scripts' );


function mytheme_custom_style() {
wp_register_style('bootstrap', get_template_directory_uri() . '/assets/css/jquery_ui.css');
wp_enqueue_style( 'bootstrap' );
}
?>