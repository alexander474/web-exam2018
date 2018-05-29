<?php
function my_romSearch() {
    wp_enqueue_style( 'autocomplete_style', get_stylesheet_directory_uri() . 
    '/css/autocomplete.css' );
    if ( !is_admin() ) {
        wp_enqueue_script('jquery');
        wp_enqueue_script ( 'my_romSearch_script', get_stylesheet_directory_uri() . 
        '/js/autocomplete.js' , array( 'jquery' ), false, true );
    }
}

add_action('wp_enqueue_scripts','my_romSearch');

function autoComplete() {
    include "autoComplete.php";
}

function set_autoComplete($atts, $content = null ){
    $content = autoComplete();
    return $content;
}
add_shortcode('set_autoComplete', 'set_autoComplete');
?>