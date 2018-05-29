<?php
/*
 Plugin Name: WP Autosearch
 Plugin URI: 
 Description:Wordpress realtime auto search suggestions of WordPress posts, pages,custom post,taxonomies and Order of Types
 Author: Netflixtech
 Version: 1.0.3
 Author URI: http://netflixtech.com/

*/

define( 'WPAUTOSEARCH__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
class Wizardinfosys_autosearch_suggest {
	
	public $version = 'Wizardinfosys_2015';
	public $commit_version = ''; 
	public $path = '';
	public $url = '';
	public $text_domain = 'wp-autosearch';
	public $options; // PHP stdClass
	public $security = 'Wr%48?UB`hj:#"?:$gL5d'; //Security key
	public $file = '';
	public $helper;   /* Wizardinfosys_autosearch_helper object */
        public $types;  /*Set custom field in search area*/
        protected static $_instancenumber = 0;
        public $name;
        public $label;
        public $constraints;
        public $errormsg;
        public $data;
	
        
        /********************************
         *@Declare Construct Method 
         *@Call default construct 
         * ********************************/
        
	function __construct() {
		$this->file = __FILE__;
		$this->path = dirname($this->file) . '/';
		$this->url=plugin_dir_url( __FILE__ );
                require_once( WPAUTOSEARCH__PLUGIN_DIR . 'helper/helper.php' );
                self::$_instancenumber++;
		$this->helper = new Wizardinfosys_autosearch_helper();
		
	}
	
       
         /********************************
         *@Loads the plugin's 
         *@call load_plugin_textdomain multiple times for the same domain,
         *@Reference : https://developer.wordpress.org/reference/functions/load_plugin_textdomain/ 
         * plugin’s translated strings.
         * ********************************/
	
	public function wi_plugins_loaded(){
		load_plugin_textdomain($this->text_domain, false, dirname(plugin_basename($this->file)) . '/languages/');
	}
	
        /********************************
         *@Active Plugin Options
         *@Update value in wordpres options table
         * ********************************/
	public function wi_activate() {
		$options_type = get_option('wp-autosearch-suggest');
		$defaults_value = $this->helper->Wi_default_autosearch_options();
		$merged_parse = wi_parse_args($options_type, $defaults_value);
		update_option('wp-autosearch-suggest', $merged_parse);
	}
	
        /********************************
         *@Initialize 
         *@Update value in wordpres options table
         * ********************************/
	public function wi_initialize() {
		$merged = $this->wi_get_options();
		$this->options = $this->helper->array_to_object($merged);
		$this->helper->wi_activate_thumbnail();
		$this->helper->wi_add_image_size();
		$this->helper->register_sidebar();
		$this->helper->wi_register_shortcode();
		if((in_array('page', (array)$merged['post_types']))){ // If "page" type is checked so make it publicly queryable
			$this->helper->make_pages_publicly_queryable(); 
		}
	}

         /********************************
         *@Check plugin version
         *@call default varible
         * ********************************/
        
	public function version() {
		return $this->version;
	}
        
        /********************************
         *@Get value from options
         *@Set defaults post types
         * ********************************/
	public function wi_get_options(){
		$options = get_option('wp-autosearch-suggest');
		$defaults = $this->helper->Wi_default_autosearch_options();
		if(isset($options['post_types']) && count((array) $options['post_types'])>0){
			unset($defaults['post_types']); 
		}
		return $merged = wi_parse_args($options, $defaults);
		
	}
	
        /********************************
         *@Show Admin menu in backend
         * ********************************/
	public function wi_show_admin_menu(){
		include $this->path . 'helper/options.php';
	}
	
	public function wi_admin_menu(){
		add_submenu_page('options-general.php', __('WP AutoSearch', $this->text_domain), __('WP AutoSearch', $this->text_domain), 'administrator', 'wp_autosearch_options', array(&$this, 'wi_show_admin_menu'));
	}
    
          /********************************
         *@Add Custom Script in header sections
         * ********************************/
        
	public function wi_add_assert_to_header(){
            if(!empty($this->options->custom_css)){
              echo '<style type="text/css" media="screen">' . $this->options->custom_css . '</style>';
            }
            if(!empty($this->options->custom_js)){
              echo '<script type="text/javascript">' . $this->options->custom_js . '</script>';
            }
	}
        
        /********************************
        *@Register script for Admin 
        * Add admin assets in footer
        **********************************/
	public function wi_add_register_admin_assets(){
		
		wp_register_style('wp-autosearch-admin-style', $this->url . 'assert/css/admin-style.css');
                wp_register_script('wp-autosearch-jscolor', $this->url . 'assert/js/jscolor/jscolor.js', array(), false, true);
		wp_register_script('wp-autosearch-numeric', $this->url . 'assert/js/numeric.js', array('jquery'), false, true);
		wp_register_script('wp-autosearch-admin', $this->url . 'assert/js/admin.js', array(), false, true);
                wp_register_script('wp-autosearch-custom',  $this->url . 'assert/js/custom.js', array(), false, true);
	}
	
         /********************************
         *@Register script for Frontend
         *
         * ********************************/
	public function wi_add_register_frontend_assets(){
		
		wp_register_script('wp-autosearch-migrate',  $this->url . 'assert/js/migrate.js', array('jquery'), false, true);
		wp_register_script('wp-autosearch-script-core',  $this->url . 'assert/js/autocomplete.js', array('jquery'), false, true);
		wp_register_script('wp-autosearch-script',  $this->url . 'assert/js/ajax-script.js', array(), false, true);
               
	}
	
        /********************************
        *@Load Frontend asserts
        *
        *********************************/
	
	public function wi_load_frontend_assets() {
		
		wp_enqueue_script('wp-autosearch-migrate');
		wp_enqueue_script('wp-autosearch-script-core');
		wp_enqueue_script('wp-autosearch-script');
               
		$this->wi_add_script_config();
	}
	
        /********************************
        *@Add css in header part
        *********************************/
	public function wi_add_css_to_head(){
		$this->helper->add_css();
	}
	
        /********************************
        *@Add Script config
        *********************************/
        
	public function wi_add_script_config(){
		$config = (array) $this->options;
		$config['nonce'] = wp_create_nonce($this->security);
		$config['ajax_url'] = admin_url('admin-ajax.php');
                
		wp_localize_script('wp-autosearch-script', 'wp_autosearch_config', $config);
	}
	
	public function wi_load_only_admin_assets() {
		
		if (isset($_GET['page']) && $_GET['page'] == 'wp_autosearch_options') {
			wp_enqueue_script('jquery');
			wp_enqueue_script('wp-autosearch-jscolor');
			wp_enqueue_script('wp-autosearch-numeric');
			wp_enqueue_script('wp-autosearch-admin');
                        wp_enqueue_script('wp-autosearch-custom');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			wp_enqueue_script('postbox');
			wp_enqueue_script('underscore');
			wp_enqueue_style('wp-autosearch-admin-style');
		}
	}
	// Includes dependency scripts
	public function wi_add_include_dependency(){
		require_once(WPAUTOSEARCH__PLUGIN_DIR . 'widget/widget.php');
		require_once(WPAUTOSEARCH__PLUGIN_DIR . 'ajax/ajax.php');
		require_once(WPAUTOSEARCH__PLUGIN_DIR . 'functions.php');
		require_once(WPAUTOSEARCH__PLUGIN_DIR . 'media/resize.php');
	}
        
       
}

/*
 * Create Object for wordpress auto search suggest
 */
$wizardinfosys_autosearch = new Wizardinfosys_autosearch_suggest();

/*******************************
 *  Add an activation hook
 *******************************/
register_activation_hook($wizardinfosys_autosearch->file, array($wizardinfosys_autosearch, 'wi_activate'));

/*****************************
 *Register frontend/admin scripts and styles
 ****************************/
add_action('wp_enqueue_scripts', array($wizardinfosys_autosearch, 'wi_add_register_frontend_assets'));
add_action('admin_init', array($wizardinfosys_autosearch, 'wi_add_register_admin_assets'));

/*********************************
 *  Make plugin translation ready
 *********************************/
add_action('plugins_loaded', array($wizardinfosys_autosearch,'wi_plugins_loaded'));

/*********************************
 * Actions to hook Plugin to Wordpress
 ************************************/
add_action('init', array($wizardinfosys_autosearch, 'wi_initialize'));
add_action('admin_menu', array($wizardinfosys_autosearch, 'wi_admin_menu'));

/****************************************
 *  Load frontend/admin scripts and styles
 *******************************************/
add_action('wp_enqueue_scripts', array($wizardinfosys_autosearch, 'wi_load_frontend_assets'));
add_action('admin_enqueue_scripts', array($wizardinfosys_autosearch, 'wi_load_only_admin_assets'));


$wizardinfosys_autosearch->wi_add_include_dependency();

/************************************
 *  User and Admin AJAX actions
 ************************************/
add_action('wp_ajax_wi_get_search_results', 'wi_get_search_results' );
add_action('wp_ajax_nopriv_wi_get_search_results', 'wi_get_search_results');
add_action('wp_head', array($wizardinfosys_autosearch, 'wi_add_css_to_head'));

add_filter('posts_search', 'wi_posts_search_handler', 100, 2 );

/******************************************
 *  Adds custom user CSS & JS to header
 *******************************************/
add_action('wp_head', array($wizardinfosys_autosearch,'wi_add_assert_to_header'));

?>