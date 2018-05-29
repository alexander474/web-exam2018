<?php
function my_button() {
    if ( !is_admin() ) {
        wp_enqueue_script('jquery');
        wp_enqueue_script ( 'my_button_script', get_stylesheet_directory_uri() . 
        '/js/Button.js' , array( 'jquery' ), false, true );
    }
}

add_action('wp_enqueue_scripts','my_button');

function button() {
    include "Button.php";
}

function set_button($atts, $content = null ){
    $content = button();
    return $content;
}
add_shortcode('set_button', 'set_button');
?>