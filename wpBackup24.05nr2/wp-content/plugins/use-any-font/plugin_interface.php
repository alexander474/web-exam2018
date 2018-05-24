<?php
// SETTINGS
if (isset($_POST['submit-uaf-settings'])){
	if (isset($_POST['uaf_disbale_editor_font_list'])){
		$uaf_disbale_editor_font_list = 1;
	} else {
		$uaf_disbale_editor_font_list = '';
	}
	
	if (isset($_POST['uaf_use_curl_uploader'])){
		$uaf_use_curl_uploader = 1;
	} else {
		$uaf_use_curl_uploader = '';
	}
	
	if (isset($_POST['uaf_use_absolute_font_path'])){
		$uaf_use_absolute_font_path = 1;
	} else {
		$uaf_use_absolute_font_path = '';
	}
	
	if (isset($_POST['uaf_use_alternative_server'])){
		$uaf_use_alternative_server = 1;
	} else {
		$uaf_use_alternative_server = '';
	}
	
	update_option('uaf_disbale_editor_font_list', $uaf_disbale_editor_font_list);
	update_option('uaf_use_curl_uploader', $uaf_use_curl_uploader);
	update_option('uaf_use_absolute_font_path', $uaf_use_absolute_font_path);	
	update_option('uaf_use_alternative_server', $uaf_use_alternative_server);	
	$settings_message = 'Settings Saved';
	
	uaf_write_css(); // Need to rewrite css for uaf_use_relative_font_path setting change 
}


add_action('admin_menu', 'uaf_create_menu');
add_action("admin_print_scripts", 'adminjslibs');
add_action("admin_print_styles", 'adminCsslibs');
add_action('wp_enqueue_scripts', 'uaf_client_css');
add_action('plugins_loaded', 'uaf_update_check');
add_action('init', 'uaf_editor_setup');

$uaf_disbale_editor_font_list_value = get_option('uaf_disbale_editor_font_list');
if ($uaf_disbale_editor_font_list_value != 1):
	add_filter('mce_buttons_2', 'wp_editor_fontsize_filter');
	add_filter('tiny_mce_before_init', 'uaf_mce_before_init' );
endif;

function uaf_client_css() {
	$uaf_upload 	= wp_upload_dir();
	$uaf_upload_url = set_url_scheme($uaf_upload['baseurl']);
	$uaf_upload_url = $uaf_upload_url . '/useanyfont/';
	wp_register_style( 'uaf_client_css', $uaf_upload_url.'uaf.css', array(),get_option('uaf_css_updated_timestamp'));
	wp_enqueue_style( 'uaf_client_css' );
}

function adminjslibs(){
	wp_register_script('uaf_validate_js',plugins_url("use-any-font/js/jquery.validate.min.js"));		
	wp_enqueue_script('uaf_validate_js');
}

function adminCsslibs(){
	$uaf_upload 	= wp_upload_dir();
	$uaf_upload_url = set_url_scheme($uaf_upload['baseurl']);
	$uaf_upload_url = $uaf_upload_url . '/useanyfont/';
	wp_register_style('uaf-admin-style', plugins_url('use-any-font/css/uaf_admin.css'));
    wp_enqueue_style('uaf-admin-style');
	wp_register_style('uaf-font-style', $uaf_upload_url.'admin-uaf.css', array(), get_option('uaf_css_updated_timestamp'));
    wp_enqueue_style('uaf-font-style');
	add_editor_style($uaf_upload_url.'admin-uaf.css');
}
		
function uaf_create_menu() {
	add_menu_page( 'Use Any Font', 'Use Any Font', 'manage_options', 'uaf_settings_page', 'uaf_settings_page', 'dashicons-editor-textcolor');
}

function uaf_create_folder() {
	$uaf_upload 	= wp_upload_dir();
	$uaf_upload_dir = $uaf_upload['basedir'];
	$uaf_upload_dir = $uaf_upload_dir . '/useanyfont/';
	if (! is_dir($uaf_upload_dir)) {
       mkdir( $uaf_upload_dir, 0755 );
    }
}

function uaf_activate(){
	uaf_create_folder(); // CREATE FOLDER
	uaf_write_css(); //rewrite css when plugin is activated after update or somethingelse......
}

function uaf_update_check() { // MUST CHANGE WITH EVERY VERSION
    $uaf_version_check = get_option('uaf_current_version');
	if ($uaf_version_check != '4.9.2'):
		update_option('uaf_current_version', '4.9.2');
		if ($uaf_version_check < 4.0):
			uaf_create_folder();
			uaf_move_file_to_newPath();
		endif;
		uaf_write_css();
	endif;	
}

function uaf_settings_page() {
	$uaf_upload 	= wp_upload_dir();
	$uaf_upload_dir = $uaf_upload['basedir'];
	$uaf_upload_dir = $uaf_upload_dir . '/useanyfont/';
	$uaf_upload_url = $uaf_upload['baseurl'];
	$uaf_upload_url = $uaf_upload_url . '/useanyfont/';
	
	$uaf_disbale_editor_font_list_value = get_option('uaf_disbale_editor_font_list');
	$uaf_use_curl_uploader_value = get_option('uaf_use_curl_uploader');
	$uaf_use_absolute_font_path = get_option('uaf_use_absolute_font_path');
	$uaf_use_alternative_server = get_option('uaf_use_alternative_server');
	
	if ($uaf_use_alternative_server == 1){
		$uaf_font_convert_server_url = 'https://dnesscarkey.com';
	} else {
		$uaf_font_convert_server_url = 'https://dnesscarkey.xyz';
	}
	
	include('includes/uaf_header.php');
	if ($uaf_use_curl_uploader_value == 1){
		include('includes/uaf_font_upload_php.php');
	} else {
		include('includes/uaf_font_upload_js.php');	
	}
	include('includes/uaf_font_implement.php');
	include('includes/uaf_footer.php');
}

// MOVING OLD FONTFILE PATH TO NEW PATH 
function uaf_move_file_to_newPath(){
	$uaf_upload 	= wp_upload_dir();
	$uaf_upload_dir = $uaf_upload['basedir'];
	$uaf_upload_dir = $uaf_upload_dir . '/useanyfont/';
	$fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	if (!empty($fontsData)):
		foreach ($fontsData as $key=>$fontData):
			
			$oldFilePathInfo		= pathinfo($fontData['font_path']);			
			$parsedPath				= parse_url($fontData['font_path']);
			$relativeFilePath		= $_SERVER['DOCUMENT_ROOT'].$parsedPath['path'];			
			$oldfilename			= $oldFilePathInfo['filename'];
			
			if (file_exists($relativeFilePath.'.woff')){
				
				$woffFileContent 		= file_get_contents($relativeFilePath.'.woff');
				$eotFileContent 		= file_get_contents($relativeFilePath.'.eot');
				
				$fhWoff = fopen($uaf_upload_dir.'/'.$oldfilename.'.woff' , 'w') or die("can't open file. Make sure you have write permission to your upload folder");
				fwrite($fhWoff, $woffFileContent);
				fclose($fhWoff);
				
				$fhEot = fopen($uaf_upload_dir.'/'.$oldfilename.'.eot' , 'w') or die("can't open file. Make sure you have write permission to your upload folder");
				fwrite($fhEot, $eotFileContent);
				fclose($fhEot);
				
				$fontsData[$key]['font_path']	= $oldfilename;
			}
		endforeach;
	endif;
	
	$updateFontData	= json_encode($fontsData);
	update_option('uaf_font_data',$updateFontData);	
}

function uaf_write_css(){
	$uaf_use_absolute_font_path = get_option('uaf_use_absolute_font_path'); // Check if user want to use absolute font path.
	
	if (empty($uaf_use_absolute_font_path)){
		$uaf_use_absolute_font_path = 0;
	}
	
	$uaf_upload 	= wp_upload_dir();
	$uaf_upload_dir = $uaf_upload['basedir'];
	$uaf_upload_dir = $uaf_upload_dir . '/useanyfont/';
	$uaf_upload_url = $uaf_upload['baseurl'];
	$uaf_upload_url = $uaf_upload_url . '/useanyfont/';	
	$uaf_upload_url = preg_replace('#^https?:#', '', $uaf_upload_url);
	
	if ($uaf_use_absolute_font_path == 0){ // If user use relative path
		$url_parts = parse_url($uaf_upload_url);
		@$uaf_upload_url = "$url_parts[path]$url_parts[query]$url_parts[fragment]";
	}
	
	ob_start();
		$fontsRawData 	= get_option('uaf_font_data');
		$fontsData		= json_decode($fontsRawData, true);
		if (!empty($fontsData)):
			foreach ($fontsData as $key=>$fontData): ?>
			@font-face {
				font-family: '<?php echo $fontData['font_name'] ?>';
				font-style: normal;
				src: url('<?php echo $uaf_upload_url.$fontData['font_path'] ?>.eot');
				src: local('<?php echo $fontData['font_name'] ?>'), url('<?php echo $uaf_upload_url.$fontData['font_path'] ?>.eot') format('embedded-opentype'), url('<?php echo $uaf_upload_url.$fontData['font_path'] ?>.woff') format('woff');
			}
            
            .<?php echo $fontData['font_name'] ?>{font-family: '<?php echo $fontData['font_name'] ?>' !important;}
            
		<?php
		endforeach;
		endif;	
			
		$fontsImplementRawData 	= get_option('uaf_font_implement');
		$fontsImplementData		= json_decode($fontsImplementRawData, true);
		if (!empty($fontsImplementData)):
			foreach ($fontsImplementData as $key=>$fontImplementData): ?>
				<?php echo $fontImplementData['font_elements']; ?>{
					font-family: '<?php echo $fontsData[$fontImplementData['font_key']]['font_name']; ?>' !important;
				}
		<?php
			endforeach;
		endif;	
		$uaf_style = ob_get_contents();
		$uafStyleSheetPath	= $uaf_upload_dir.'/uaf.css';
		$fh = fopen($uafStyleSheetPath, 'w') or die("Can't open file");
		fwrite($fh, $uaf_style);
		fclose($fh);
	ob_end_clean();
	
	ob_start();
		$fontsRawData 	= get_option('uaf_font_data');
		$fontsData		= json_decode($fontsRawData, true);
		if (!empty($fontsData)):
			foreach ($fontsData as $key=>$fontData): ?>
			@font-face {
				font-family: '<?php echo $fontData['font_name'] ?>';
				font-style: normal;
				src: url('<?php echo $uaf_upload_url.$fontData['font_path'] ?>.eot');
				src: local('<?php echo $fontData['font_name'] ?>'), url('<?php echo $uaf_upload_url.$fontData['font_path'] ?>.eot') format('embedded-opentype'), url('<?php echo $uaf_upload_url.$fontData['font_path'] ?>.woff') format('woff');
			}
            
            .et_gf_<?php echo $fontData['font_name'] ?>{background:none !important;font-family:<?php echo $fontData['font_name'] ?>;text-indent:0 !important;font-size:25px;}
            
		<?php
		endforeach;
		endif;
		$uaf_style = ob_get_contents();
		$uafStyleSheetPath	= $uaf_upload_dir.'/admin-uaf.css';
		$fh = fopen($uafStyleSheetPath, 'w') or die("Can't open file");
		fwrite($fh, $uaf_style);
		fclose($fh);
		
		$uafStyleSheetPath	= $uaf_upload_dir.'/admin-uaf-rtl.css';
		$fh = fopen($uafStyleSheetPath, 'w') or die("Can't open file");
		fwrite($fh, $uaf_style);
		fclose($fh);
	ob_end_clean();
	update_option('uaf_css_updated_timestamp', time()); // Time entry for stylesheet version
}

function uaf_editor_setup(){
	include('includes/uaf_editor_setup.php');	
}