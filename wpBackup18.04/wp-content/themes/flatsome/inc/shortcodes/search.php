<?php

// [search]
function search_shortcode($atts) {
	extract(shortcode_atts(array(
		'size' => 'normal',
		'style' => '',
	), $atts));

    ob_start();

    echo '<div class="searchform-wrapper ux-search-box relative form-'.$style.' is-'.$size.'">';
	 	 if(function_exists('get_product_search_form')) {
	        get_product_search_form();
	    } else {
	        get_search_form();
	    }
 	echo '</div>';
 	
 	$content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode("search", "search_shortcode");