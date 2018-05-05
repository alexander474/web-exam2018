<?php
// DEFAULT WORDPRESS EDITOR
function uaf_mce_before_init( $init_array ) {
	$theme_advanced_fonts = '';
	$fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			$theme_advanced_fonts .= ucfirst(str_replace('_',' ', $fontData['font_name'])) .'='.$fontData['font_name'].';';		
		endforeach;
	endif;
	
	$init_array['font_formats'] = $theme_advanced_fonts.'Andale Mono=Andale Mono, Times;Arial=Arial, Helvetica, sans-serif;Arial Black=Arial Black, Avant Garde;Book Antiqua=Book Antiqua, Palatino;Comic Sans MS=Comic Sans MS, sans-serif;Courier New=Courier New, Courier;Georgia=Georgia, Palatino;Helvetica=Helvetica;Impact=Impact, Chicago;Symbol=Symbol;Tahoma=Tahoma, Arial, Helvetica, sans-serif;Terminal=Terminal, Monaco;Times New Roman=Times New Roman, Times;Trebuchet MS=Trebuchet MS, Geneva;Verdana=Verdana, Geneva;Webdings=Webdings;Wingdings=Wingdings';
	return $init_array;
}

function wp_editor_fontsize_filter( $options ) {
	array_unshift( $options, 'fontsizeselect');
	array_unshift( $options, 'fontselect');
	return $options;
}

// DIVI CUSTOMIZER AND BUILDER
add_filter('et_websafe_fonts', 'uaf_send_fonts_divi_list',10,2);
function uaf_send_fonts_divi_list($fonts){
    $fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	$fonts_uaf		= array();
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			$fonts_uaf[$fontData['font_name']] = array(
				'styles' 		=> '400',
				'character_set' => 'cyrillic,greek,latin',
				'type'			=> 'serif'
			);	
		endforeach;
	endif;
  	return array_merge($fonts_uaf,$fonts);
}

// SITE ORIGIN BUILDER
add_filter('siteorigin_widgets_font_families', 'uaf_send_fonts_siteorigin_list',10,2);
function uaf_send_fonts_siteorigin_list($fonts){
    $fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	$fonts_uaf		= array();
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			$fonts_uaf[$fontData['font_name']] = $fontData['font_name'];
		endforeach;
	endif;
  	return array_merge($fonts_uaf,$fonts);
}

// REDUX Framework
if (class_exists( 'Redux' ) ) {
   	global $opt_name;
	add_filter('redux/'.$opt_name.'/field/typography/custom_fonts', 'uaf_send_fonts_redux_list' );
}

function uaf_send_fonts_redux_list( $custom_fonts ) {
	$fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	$fonts_uaf		= array('Use Any Fonts' => array());
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			$fonts_uaf['Use Any Fonts'][$fontData['font_name']] = $fontData['font_name'];
		endforeach;
	endif;
  	return $fonts_uaf;
}


// X Theme
add_filter('x_fonts_data', 'uaf_send_fonts_x_theme_list',10,2);
function uaf_send_fonts_x_theme_list($fonts){
    $fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	$fonts_uaf		= array();
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			$fonts_uaf[$fontData['font_name']] = array(
												'source'  => 'Use Any Font',
												'family'  => $fontData['font_name'],
												'stack'   => '"'.$fontData['font_name'].'"',
												'weights' => array( '400' )
												);
		endforeach;
	endif;
  	return array_merge($fonts_uaf,$fonts);
}

// ELEMENTOR
function uaf_send_fonts_elementor_list( $controls_registry ) {
    $fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	$fonts_uaf		= array('Use Any Fonts' => array());
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			$fonts_uaf[$fontData['font_name']] = 'system';
		endforeach;
	endif;
  	
	$fonts = $controls_registry->get_control( 'font' )->get_settings( 'options' ); 
	$new_fonts = array_merge($fonts_uaf, $fonts );
	$controls_registry->get_control( 'font' )->set_settings( 'options', $new_fonts );
}
add_action( 'elementor/controls/controls_registered', 'uaf_send_fonts_elementor_list', 10, 1 );

// Beaver Builder
add_filter('fl_builder_font_families_system', 'uaf_send_fonts_beaver_builder_list',10,2);
function uaf_send_fonts_beaver_builder_list($fonts){
    $fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	$fonts_uaf		= array();
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			$fonts_uaf[$fontData['font_name']] = array(
												'fallback'  => 'Verdana, Arial, sans-serif',
												'weights'  => array('400')
												);
		endforeach;
	endif;
  	return array_merge($fonts_uaf,$fonts);
}

// Themify Builder
add_filter('themify_get_web_safe_font_list', 'uaf_send_fonts_themify_builder_list',10,2);
function uaf_send_fonts_themify_builder_list($fonts){
    $fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	$fonts_uaf		= array();
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			$fonts_uaf[] = array(
				'value' => $fontData['font_name'],
				'name' => $fontData['font_name']
			);
		endforeach;
	endif;
  	return array_merge($fonts_uaf,$fonts);
}