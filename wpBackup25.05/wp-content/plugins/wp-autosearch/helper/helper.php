<?php
if (!defined('ABSPATH')) exit('No direct script access allowed');

 
 /**
 * Netflixtech wordPress helper class
 *
 * Contains some Wordpress functions to help building plugin customizations
 *
 * @package    	Wordpress Auto search Suggest
 * @author     	Netflixtech <support@netflixtech.com>
 * @license     #
 * @link        #
 * @version    	1.0
 */
class Wizardinfosys_autosearch_helper{
	
	function __construct(){
		 
	}
	
	/***************************************************
	* Converts string value to int value and check string input value
	* @param string,int,array $input as user input value
	* @param boolean $is_int, force convert to int 
	* @return string,int safe parameter value
	***************************************************/
	
	public function wi_prepare_parameter($input, $is_int=false){
	
		if(is_array($input)){
			foreach($input as $key=>$value){
				if($is_int){
					$input[$key] = intval($value);
				}else{
					$input[$key] = trim(stripslashes(strip_tags($value)));
				}
			}
		}else{
			if($is_int){
				$input = intval($input);
			}else{
				$input = trim(stripslashes(strip_tags($input)));
			}
		}
                
		return $input;
	}
	
	/*************************************
	* Add theme "featured image" to current theme support
	****************************************/
	
	public function wi_activate_thumbnail(){
		 add_theme_support('post-thumbnails');
	}
	
	/***********************************************
	* Return limit of a length Wordpress post
	* @param int $limit, return number of maximum characters 
	* @return string limited character
	*************************************************/
	
	public function wi_limit_str($str, $limit=100) {
        $str = trim(strip_tags($str));
		$str = strip_shortcodes($str);
		$excerpt = mb_substr($str,0,$limit);
		if (strlen($excerpt)<strlen($str)) {
			$excerpt .= '...';
		}
		return $excerpt;
	}
	
	/*********************************************************************
	* Finds image post thumbnail image in current post content of wordpresss
	* @return image url
	******************************************************************/
	
	public function wi_post_image(){
		global $post, $wizardinfosys_autosearch;
		if(has_post_thumbnail()) {
			$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
			$thumbnail_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'search-thumbnail', false );
			return $thumbnail_attributes[0];
		}else{
			$post_image = '';
			if($wizardinfosys_autosearch->options->get_first_image =='true'){
				global $post, $posts;
				ob_start();
				ob_end_clean();
				preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
				$post_image = (isset($matches[1][0]))? $matches[1][0]: "";
				if($wizardinfosys_autosearch->options->force_resize_first_image =='true' && !empty($post_image)){ // Force resize
						$url = $post_image;
						$width = $wizardinfosys_autosearch->options->thumb_image_width;
						$height = $wizardinfosys_autosearch->options->thumb_image_height;
						$crop = $wizardinfosys_autosearch->options->thumb_image_crop;
						$retina = false;
						$image = matthewruddy_image_resize($url, $width, $height, $crop, $retina);
						if (is_wp_error($image)){
							$post_image = '';
						} else {
							$post_image = $image['url'];
						}
				}
			}
			if(empty($post_image)) {
				$post_image = $wizardinfosys_autosearch->options->default_image;
			}
			return $post_image;
		}
	}
	
	/**************************************************************
	* Add new size Wordpress post thumbnail images
        * Use wordpress functions add_image_size
	***************************************************************/
	
	public function wi_add_image_size(){
		global $wizardinfosys_autosearch;
		add_image_size( 'search-thumbnail', $wizardinfosys_autosearch->options->thumb_image_width, $wizardinfosys_autosearch->options->thumb_image_height,  (boolean) ($wizardinfosys_autosearch->options->thumb_image_crop=='true') );
	}
	
	/**********************************************************
	* Removes illegal characters that cause problem in result compiling
	*********************************************************/
	
	public function wi_remove_illegal($input){
		$illegal = array("|||");
		return str_replace($illegal, "", $input);
	}
	
	/*******************************************
	* Finds  Wordpress post and custom types
	* @return array of queryable post types/custom post type
	********************************************/
	
	public function wi_get_post_types(){
		$args=array(
		  'public'   => true,
		  'publicly_queryable' => true,
		  'exclude_from_search' => false,
		  'publicly_queryable' => true,
		  '_builtin' => false,
		); 
		$output = 'names';
		$operator = 'and';
		$post_types = get_post_types($args,$output,$operator); ////user https://codex.wordpress.org/Function_Reference/get_post_types
		
		// Add builtin post types
		$post_types['page'] = 'page';
		$post_types['post'] = 'post';
		ksort($post_types);
		return $post_types;
	}
        
        /*******************************************
	* Finds  Wordpress taxonomy type
	* @return array of queryable taxonomy types/custom taxonomy  type
	********************************************/
        function wi_get_post_taxonomy(){
               
                $args = array(
                                'public' => true,
                                
                            );
                $output             = 'names';
                $taxonomies         = get_taxonomies( $args, $output );
                return $taxonomies;      
            
        }




        /************************************************
	* Registers a sidebar for using widget
        * You can call widget area anywhere
        *  use register_sidebar functions form  https://codex.wordpress.org/Function_Reference/register_sidebar 
	***********************************************/
	
	public function register_sidebar(){
		global $wizardinfosys_autosearch;
		if ( function_exists('register_sidebar') )
			register_sidebar(array(
				'name'=>__('WP AutoSearch', $wizardinfosys_autosearch->text_domain),
				'id' => 'wi_autosearch_suggest_seidebar',
				'description' => __('You can Add "WP AutoSearch Suggest widget" here and use shortcode [wi_autosearch_suggest_form] or function  <?php wi_autosearch_suggest_form(); ?>.', $wizardinfosys_autosearch->text_domain),
				'before_widget' => '<div id="wp_search_widget">',
				'after_widget' => "</div>",
				'before_title' => '<h3 id="wp_search_seidebar_title">',
				'after_title' => "</h3>"
			));
	}
	
	/****************************************************
	* Registers a shortcode using widget Area anywhere
        * shortcode funtions add widget in shortcode method
	*****************************************************/
	
	public function wp_auto_search_shortcode(){
		global $wizardinfosys_autosearch;
		ob_start();
		?>
		<div class="wizardinfosys_autosearch_wrapper">
				<form id="wizardinfosys_autosearch_form" full_search_url="<?php echo $wizardinfosys_autosearch->options->full_search_url; ?>" action="<?php echo esc_url(home_url('/')); ?>" method="get">
					<div class="wp_autosearch_form_wrapper" style="max-width: <?php echo $max_width; ?>px;">
						<label class="wp_autosearch_form_label"><?php echo $title; ?></label>
						<input name="s" class="wp_autosearch_input" type="text"  value="<?php echo $value; ?>" style="width: 95%;" placeholder="<?php echo $placeholder; ?>" autocomplete="off" />
						<button style="display: none;" class="wp_autosearch_submit"></button>
					</div>
				</form>
			</div>
		<?php
		//dynamic_sidebar("wi_autosearch_suggest_seidebar");
		return ob_get_clean();
	}
	
	/********************************************
	* Registers a shortcode for using widget anywhere
        * 
	**********************************************/
	
	public function wi_register_shortcode(){
		add_shortcode("wi_autosearch_suggest_form", array(&$this, 'wp_auto_search_shortcode'));
	}
	
	/**********************************************
	* Converts stdClass to array
        * @param pass string input 
	* @return array of input 
	************************************************/
	
	public function object_to_array($input){
		if (is_object($input)) {
			$input = get_object_vars($input);
		}
		if (is_array($input)) {
			return array_map(array(&$this, 'object_to_array'), $input);
		}
		else {
			return $input;
		}
	}
	
	/********************************************
	* Converts array to stdClass
        * @param pass string input
	* @return stdClass of input
	*******************************************/
	
	public function array_to_object($input){
		if (is_array($input)) {
			return (object) array_map(array(&$this, 'array_to_object'), $input);
		}
		else {
			return $input;
		}
	}
	
	/*********************************************************************
	* By default pages are not queryable, this method make pages queryable
        *  
	**********************************************************************/
	
	public function make_pages_publicly_queryable(){
		global $wp_post_types;
		$wp_post_types['page']->publicly_queryable = true;
	}
	
	/*************************************************
    	* HTML code needed for wordpress native uploader
        * 
	*************************************************/
	
	function wi_image_upload_field($value='', $name='') {
		global $wizardinfosys_autosearch;
               
	?>
		
		<input id="<?php echo $name; ?>" type="text" size="90" disabled="disabled" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
                <input id="<?php echo $name; ?>_button" type="button" disabled="disabled" value="<?php _e('Upload Image', $wizardinfosys_autosearch->text_domain); ?>" />
		
		
		
	<?php
	}
	
	/********************************************
	* Wordpress  AutoSearch Suggest default options
	* @return array of default options
	*******************************************/
	
	public function Wi_default_autosearch_options(){
		$defaults = array(
                        'post_types' => array_values($this->wi_get_post_types()),
                        'autocomplete_taxonomies'=>array_values(array('category')),
                        'split_results_by_type' => 'true',
                        'search_title'=>'true',
                        'search_content'=>'true',
                        'search_terms'=>'false',
                        'search_exactonly'=>'true',
                        'order_by' => 'title',
                        'order' => 'DESC',
                        'search_comments' => 'false',
                        'search_tags' => 'false',
                        'no_of_results' =>20,
						'description_limit' => 100,
						'title_limit' => 50,
						'excluded_ids' => array(),
						'excluded_cats' => array(),
						'full_search_url' => esc_url(add_query_arg('s', '%q%', home_url('/'))),
						'min_chars' => 3,
						'ajax_delay' => 400,
						'cache_length' => 200,
                        'autocomplete_sortorder'=>'posts',
                        'thumb_image_display' => 'true',
                        'thumb_image_width' => 50,
						'thumb_image_height' => 50,
                        'get_first_image' => 'true',
						'force_resize_first_image' => 'true',
                        'thumb_image_crop' => 'true',
                        'default_image' => WP_PLUGIN_URL . "/wp-autosearch/assert/image/default.png",
                        'search_image' => WP_PLUGIN_URL . "/wp-autosearch/assert/image/search-icon.png",
						'display_more_bar' => 'true',
						'display_result_title' => 'true',
						'enable_token' => 'true',
                        'custom_css' => '',
                        'custom_js' => '',
                        'try_full_search_text' => 'Search more...',
                        'no_results_try_full_search_text' => 'No Results!',
                        'show_author'=>'false',
                        'show_date'=>'false',
                        'description_result'=>'false',
                        'color'=>array(
									'results_even_bar'=> 'E8E8E8',
									'results_odd_bar'=> 'FFFFFF',
									'results_even_text'=> '000000',
									'results_odd_text'=> '000000',
									'results_hover_bar'=> '5CCCB2',
									'results_hover_text'=> 'FFFFFF',
									'seperator_bar'=> '2D8DA0',
									'seperator_hover_bar'=> '6A81A0',
									'seperator_text'=> 'FFFFFF',
									'seperator_hover_text'=> 'FFFFFF',
									'more_bar'=> '5286A0',
									'more_hover_bar'=> '4682A0',
									'more_text'=> 'FFFFFF',
									'more_hover_text'=> 'FFFFFF',
									'box_border'=> '57C297',
									'box_background'=> 'FFFFFF',
									'box_text'=> '000000',
					),
			'title'=> $this->default_post_types()
		);
		return $defaults;
	}
	
	/*******************************************************
	* post and custom types with their human title
	* @return array of installed post types
	*************************************************/
	
	public function default_post_types(){
		$post_types = $this->wi_get_post_types();
		global $wp_post_types;
		foreach($post_types as $name=>$title){
			if(isset($wp_post_types[$name]->labels->menu_name)){
				$post_types[$name] = $wp_post_types[$name]->labels->menu_name;
			}else{
				$post_types[$name] = ucfirst($name);
			}
		}
		return $post_types;
	}
	
        /********************************
        * Add css for front search section
        *  
         *********************************/
	public function add_css(){
		global $wizardinfosys_autosearch;
		ob_start();
		include $wizardinfosys_autosearch->path . 'assert/css/style.php';
		$css = ob_get_clean();
		$css = str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$css)))));
		echo '<style type="text/css">' . $css .'</style>';
	}
        
        
        
        
}


?>