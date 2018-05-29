<?php
if (!defined('ABSPATH')) exit('No direct script access allowed');

 /**
 * Netflixtech wordPress auto search Suggest widget
 * 
 * Adds widget to used in sidebar in widget sections
 * Use reference source code  https://codex.wordpress.org/Widgets_API
 * @package    	WP Autosearch
 * @author      Netflixtech <support@netflixtech.com>
 * @license     #
 * @link	#
 * @version    	1.0
 */
 
class Wizardinfosys_search_widget extends WP_Widget{
    
        /**
	 * Sets up the widgets name and description
	 */
	function __construct(){
		global $wizardinfosys_autosearch;
		parent::__construct(
			'wizardinfosys_autosearch',
			__('WP AutoSearch', $wizardinfosys_autosearch->text_domain),
			array('description' => __('WP Autosearch Form', $wizardinfosys_autosearch->text_domain))
		);
	}
        /***************************************
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 *****************************************/
        
	public function form($instance){ 
                
		global $wizardinfosys_autosearch;
		$defaults = array(
			'title'		  => __('Search', $wizardinfosys_autosearch->text_domain),
			'placeholder' => __('Enter your search term...', $wizardinfosys_autosearch->text_domain),
			'max_width' => __('300', $wizardinfosys_autosearch->text_domain)
		);
		$instance = wi_parse_args($instance, $defaults);
		extract($instance); // Extract array to multiple variables
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>" ><?php _e('Title', $wizardinfosys_autosearch->text_domain); ?>:</label>
			<input class="widefat"
				id = "<?php echo $this->get_field_id('title'); ?>"
				name = "<?php echo $this->get_field_name('title'); ?>"
				value = "<?php if(isset($title)) echo $title; ?>" 
			/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('placeholder'); ?>" ><?php _e('Placeholder', $wizardinfosys_autosearch->text_domain); ?>:</label>
			<input class="widefat"
				id = "<?php echo $this->get_field_id('placeholder'); ?>"
				name = "<?php echo $this->get_field_name('placeholder'); ?>"
				value = "<?php if(isset($placeholder)) echo $placeholder; ?>" 
			/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('max_width'); ?>" ><?php _e('Max width(px)', $wizardinfosys_autosearch->text_domain); ?>:</label>
			<input class="widefat"
				id = "<?php echo $this->get_field_id('max_width'); ?>"
				name = "<?php echo $this->get_field_name('max_width'); ?>"
				value = "<?php if(isset($max_width)) echo $max_width; ?>" 
			/>
		</p>
		<?php 
	}

        /**************************
         *  Frontend widget form
         * 
         *************************/
	public function widget($args, $instance){ 
		global $wizardinfosys_autosearch;
		$defaults = array(
			'title'		  => __('Search', $wizardinfosys_autosearch->text_domain),
			'placeholder' => __('Type Keyword...', $wizardinfosys_autosearch->text_domain),
			'max_width' => '350'
		);
		$instance = wi_parse_args($instance, $defaults);
	
		extract($instance);
		extract($args);
		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;
		$value = '';
		if(get_search_query()){
			$value = get_search_query();
		}
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
		echo $after_widget;
	}
}

function wpauto_search_widget_register(){
	register_widget('Wizardinfosys_search_widget');
}

add_action('widgets_init', 'wpauto_search_widget_register');
?>