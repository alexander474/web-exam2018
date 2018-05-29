<?php
if (!defined('ABSPATH'))
    exit('No direct script access allowed');

/**
 * 
 * Netflixtech Wordpress Autosearch Suggest options page
 *
 * Generates options page
 *
 * @package    	WP Autosearch
 * @author     	Netflixtech <support@netflixtech.com>
 * @license     #
 * @link	#
 * @version    	1.0
 */


global $wizardinfosys_autosearch;

$is_secure = false;


if (isset($_POST['wp_autosearch_submit'])) {
    $is_secure = wp_verify_nonce($_REQUEST['security'], $wizardinfosys_autosearch->security);
}

if(isset($_POST['wp_autosearch_default'])){
    $defaults_options = $wizardinfosys_autosearch->helper->Wi_default_autosearch_options();
    update_option('wp-autosearch-suggest', $defaults_options);
}
if (isset($_POST['wp_autosearch_submit']) && $is_secure) {
    $wp_autosearch = array();
   
    $_POST['post_types'] = (isset($_POST['post_types']) && count((array) $_POST['post_types']) > 0) ? $_POST['post_types'] : array('post');
   
    $wp_autosearch['no_of_results'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['no_of_results'], true);
    $wp_autosearch['description_limit'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['description_limit'], true);
    $wp_autosearch['excluded_cats'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter(explode(',', $_POST['excluded_cats']), true);
    $wp_autosearch['full_search_url'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['full_search_url'], false);
    $wp_autosearch['min_chars'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['min_chars'], false);
    $wp_autosearch['ajax_delay'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['ajax_delay'], false);
    $wp_autosearch['cache_length'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['cache_length'], false);
    $wp_autosearch['post_types'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['post_types'], false);
    $wp_autosearch['autocomplete_taxonomies'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['autocomplete_taxonomies'], false);
    $wp_autosearch['order_by'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['order_by'], false);
    $wp_autosearch['order'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['order'], false);
    $wp_autosearch['split_results_by_type'] = (isset($_POST['split_results_by_type']) && $_POST['split_results_by_type'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['search_tags'] = (isset($_POST['search_tags']) && $_POST['search_tags'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['search_terms'] = (isset($_POST['search_terms']) && $_POST['search_terms'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['search_title'] = (isset($_POST['search_title']) && $_POST['search_title'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['search_content'] = (isset($_POST['search_content']) && $_POST['search_content'] == 'checked') ? 'true' : 'false';

    
    

    /************************
     * Autocomplete_sortorder
     **************************/
    $wp_autosearch['try_full_search_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['try_full_search_text'], false);
    $wp_autosearch['no_results_try_full_search_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['no_results_try_full_search_text'], false);
    $wp_autosearch['thumb_image_display'] = (isset($_POST['thumb_image_display']) && $_POST['thumb_image_display'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['thumb_image_width'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['thumb_image_width'], false);
    $wp_autosearch['thumb_image_height'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['thumb_image_height'], false);
    $wp_autosearch['thumb_image_crop'] = (isset($_POST['thumb_image_crop']) && $_POST['thumb_image_crop'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['display_more_bar'] = (isset($_POST['display_more_bar']) && $_POST['display_more_bar'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['display_result_title'] = (isset($_POST['display_result_title']) && $_POST['display_result_title'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['enable_token'] = (isset($_POST['enable_token']) && $_POST['enable_token'] == 'checked') ? 'true' : 'false';
  
    
    $wp_autosearch['search_comments'] = (isset($_POST['search_comments']) && $_POST['search_comments'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['get_first_image'] = (isset($_POST['get_first_image']) && $_POST['get_first_image'] == 'checked') ? 'true' : 'false';
    $wp_autosearch['force_resize_first_image'] = (isset($_POST['force_resize_first_image']) && $_POST['force_resize_first_image'] == 'checked') ? 'true' : 'false';
  
    $wp_autosearch['search_image'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['search_image'], false);
    
   




    /*******************
     *  Color settings
     ********************/
    $wp_autosearch['color']['results_even_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['results_even_text'], false);
    $wp_autosearch['color']['results_odd_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['results_odd_text'], false);
    $wp_autosearch['color']['results_hover_bar'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['results_hover_bar'], false);
    $wp_autosearch['color']['results_hover_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['results_hover_text'], false);
    $wp_autosearch['color']['seperator_bar'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['seperator_bar'], false);
    $wp_autosearch['color']['seperator_hover_bar'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['seperator_hover_bar'], false);
    $wp_autosearch['color']['seperator_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['seperator_text'], false);
    $wp_autosearch['color']['seperator_hover_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['seperator_hover_text'], false);
    $wp_autosearch['color']['more_bar'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['more_bar'], false);
    $wp_autosearch['color']['more_hover_bar'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['more_hover_bar'], false);
    $wp_autosearch['color']['more_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['more_text'], false);
    $wp_autosearch['color']['more_hover_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['more_hover_text'], false);
    $wp_autosearch['color']['box_border'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['box_border'], false);
    $wp_autosearch['color']['results_even_bar'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['results_even_bar'], false);
    $wp_autosearch['color']['results_odd_bar'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['results_odd_bar'], false);
    $wp_autosearch['color']['box_background'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['box_background'], false);
    $wp_autosearch['color']['box_text'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['box_text'], false);

    $wp_autosearch['custom_css'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['custom_css'], false);
    $wp_autosearch['custom_js'] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_POST['custom_js'], false);

    // Title settings
    foreach ($_POST['post_type_text'] as $name => $title) {
        $wp_autosearch['title'][$name] = $wizardinfosys_autosearch->helper->wi_prepare_parameter($title, false);
    }

    // Store array of settings to database
    update_option('wp-autosearch-suggest', $wp_autosearch);
    
    
    
    ?>
    <div class="updated"><p><?php _e("changes has been Saved.", $wizardinfosys_autosearch->text_domain); ?></p></div>
    <?php
}

if (isset($_POST['wp_autosearch_submit']) && !$is_secure) {
    ?>
    <div class="error"><p><?php _e('Security check failed.', $wizardinfosys_autosearch->text_domain); ?></p></div>
    <?php
}

$wp_autosearch_suggest_options = get_option('wp-autosearch-suggest');
$defaults = $wizardinfosys_autosearch->helper->Wi_default_autosearch_options();

if (isset($wp_autosearch_suggest_options['post_types']) && count((array) $wp_autosearch_suggest_options['post_types']) > 0) {
    unset($defaults['post_types']); // removes default post types because user selected its own
}
$wp_autosearch_suggest_options = wi_parse_args($wp_autosearch_suggest_options, $defaults);



?>

    
<div id="wpsearch" class="wpsearch wrap">
    
    <div class="wpsearch-box">
	 <div class="shortcode-label">
      <label><?php _e('Search shortcode:',$wizardinfosys_autosearch->text_domain);?></label>       
      <div class="seee"><?php _e("Use shortcode [wi_autosearch_suggest_form] or php shortcode wi_autosearch_suggest_form();",$wizardinfosys_autosearch->text_domain); ?> </div>
		<div class="donate"> <a href="https://www.paypal.me/netflixtech"> <img alt="Donate Button with Credit Cards" src=" https://www.paypalobjects.com/webstatic/en_US/btn/btn_donate_cc_147x47.png" /> </a> </div>	
	</div>
	</div>
    <div class="wpsearch-box">
        
        <form action="" method="POST" name="wpsearch_data">
            <ul id="tabs" class="tabs">
                <li><a tabid="1" class="general current"><?php _e('General Options', $wizardinfosys_autosearch->text_domain); ?></a></li>
                <li><a tabid="2" class="multisite"><?php _e('Image Options', $wizardinfosys_autosearch->text_domain); ?> </a></li>
                <li><a tabid="3" class="frontend"> <?php _e('Frontend Options', $wizardinfosys_autosearch->text_domain); ?></a></li>
                <li><a tabid="4" class="layout"><?php _e('Layout options', $wizardinfosys_autosearch->text_domain); ?></a></li>
                <li><a tabid="7" class="advanced"><?php _e('Advanced', $wizardinfosys_autosearch->text_domain); ?></a></li>
            </ul>
            <div id="content" class="tabscontent">
                <div tabid="1" style="display: block;">
                    <fieldset>
                       <legend><?php _e('Genearal Options', $wizardinfosys_autosearch->text_domain); ?></legend>

                        <div class="inside"> 
                            <fieldset>
                                <legend><span><?php _e('Search in post types', $wizardinfosys_autosearch->text_domain); ?></legend>
                            <div class="post-types-container">
                                <?php
                                $post_types = $wizardinfosys_autosearch->helper->wi_get_post_types();

                                foreach ($post_types as $post_type) {
                                    $checked = (in_array($post_type, (array) $wp_autosearch_suggest_options['post_types'])) ? 'checked="checked"' : "";
                                    $active = (in_array($post_type, (array) $wp_autosearch_suggest_options['post_types'])) ? 'active"' : "";
                                    ?>
                                    <div class="postname" id="posttype-<?php echo $post_type; ?>">
                                        <label for="wpsearchtext_2">
                                                <?php _e('Search in ', $wizardinfosys_autosearch->text_domain); ?><?php _e($post_type,$wizardinfosys_autosearch->text_domain); ?>
                                            </label>
                                            <?php echo '<input type="checkbox"   class="" id="postname-' . $post_type . '"  name="post_types[]" value="' . $post_type . '" ' . $checked . ' /> '; ?>
                                            
                                    </div>    
                                    <?php
                                }
                                ?>
                            </div>   
                            </fieldset>

                            <br />
                            <?php
                            if ($wp_autosearch_suggest_options['split_results_by_type'] == 'true') {
                                $active_class = 'active';
                            } else {
                                $active_class = '';
                            }
                            ?>
                        <fieldset>
                             <legend><?php _e('Search in Taxonomy type', $wizardinfosys_autosearch->text_domain); ?></legend>
                                    <?php
                                    $taxonomy_types = $wizardinfosys_autosearch->helper->wi_get_post_taxonomy();
                                    ?>
                            <p>
                                <?php
                                $taxonomy='';
                                
                                if((array) $wp_autosearch_suggest_options['autocomplete_taxonomies']){
                                    $autocomplet_taxonomy = (array) $wp_autosearch_suggest_options['autocomplete_taxonomies'];
                                }else{
                                    $autocomplet_taxonomy='';
                                }
                                
                                
                                foreach ($taxonomy_types as $taxonomy) {
                                    $checked_taxonomy = (in_array($taxonomy,$autocomplet_taxonomy)) ? 'checked="checked"' : "";
                                    $active = (in_array($taxonomy, $autocomplet_taxonomy)) ? 'active"' : "";
                                    ?>
                                    <label for="wpsearch-terms"><?php _e($taxonomy, $wizardinfosys_autosearch->text_domain); ?>:</label>
                                    <input name="autocomplete_taxonomies[]"  class="autocomplete_taxo"  id="autocomplete_taxonomies-<?php echo $taxonomy; ?>" value="<?php echo $taxonomy; ?>" type="checkbox"  <?php echo $checked_taxonomy; ?>>

                                   
                                <br/>
                                <br />
                            <?php } ?></p>

    
                            </fieldset>
                            <fieldset>
                                <legend><?php _e('Other Options', $wizardinfosys_autosearch->text_domain); ?> </legend>
                            
                            <div class="Other_option">
                                <label for="split_results_by_type"><?php _e('Split results by post type', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="split_results_by_type" id="split_results_by_type" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['split_results_by_type'] == 'true') echo 'checked="checked"'; ?>>
                            </div>
                        
                            <br />
                            <br />
                            <div id="search_tagss">
                                <label for="search_tags"><?php _e('Search in terms?(tags)', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="search_tags"   id="search_tags" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['search_tags'] == 'true') echo 'checked="checked"'; ?>>
                            </div>

                            <br/>
                            <br/>
                       

                            <div>
                                <label for="wpsearch-terms"><?php _e('Search in terms?(categories)', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="search_terms" id="search_terms" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['search_terms'] == 'true') echo 'checked="checked"'; ?>>
                            </div>

                            <br/>
                            <br />
                            <br/>
                            
                            <div>
                                <label for="search_comments"><?php _e('Search comments', $wizardinfosys_autosearch->text_domain); ?></label>
                                <input name="search_comments"  id="search_comments" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['search_comments'] == 'true') echo 'checked="checked"'; ?>>
                            </div>
                             <br />
                            <br/> 
                            <br />
                            <br/>
                           
                       
                            <div>
                                <label for="search-title"><?php _e('Search in title?', $wizardinfosys_autosearch->text_domain); ?></label>
                                <input name="search_title" id="search_title" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['search_title'] == 'true') echo 'checked="checked"'; ?>>
                                
                            </div>
                            <br/>
                            <br />
                            <br/>

                            <div>
                                <label for="wpsearch-content"><?php _e('Search in content?', $wizardinfosys_autosearch->text_domain); ?></label>
                                <input name="search_content" id="search_content" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['search_title'] == 'true') echo 'checked="checked"'; ?>>
                               
                            </div>
                            <br/>
                            <br />
                            <br />
                               
                            <div class="disabledbutton">
                                <label for="wpsearch-exact"><?php _e('Show exact matches only?', $wizardinfosys_autosearch->text_domain); ?></label>
                                <input name="search_exactonly"  id="search_exactonly"  type="checkbox">
                                <span class="wpauto_pro_version">Pro</span>
                            </div>
                            <br />
                            <br />
                            <br />
                            <label for="order_by"><?php _e('Order by', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <select name="order_by">
                                <option  value="ID" <?php if ($wp_autosearch_suggest_options['order_by'] == 'ID'): ?> selected="selected"<?php endif; ?>><?php _e('ID', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="author" <?php if ($wp_autosearch_suggest_options['order_by'] == 'author'): ?> selected="selected"<?php endif; ?>><?php _e('Author', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="title" <?php if ($wp_autosearch_suggest_options['order_by'] == 'title'): ?> selected="selected"<?php endif; ?>><?php _e('Title', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="name" <?php if ($wp_autosearch_suggest_options['order_by'] == 'name'): ?> selected="selected"<?php endif; ?>><?php _e('Name', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="date" <?php if ($wp_autosearch_suggest_options['order_by'] == 'date'): ?> selected="selected"<?php endif; ?>><?php _e('Date', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="modified" <?php if ($wp_autosearch_suggest_options['order_by'] == 'modified'): ?> selected="selected"<?php endif; ?>><?php _e('Modified Date', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="rand" <?php if ($wp_autosearch_suggest_options['order_by'] == 'rand'): ?> selected="selected"<?php endif; ?>><?php _e('Random', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="comment_count" <?php if ($wp_autosearch_suggest_options['order_by'] == 'comment_count'): ?> selected="selected"<?php endif; ?>><?php _e('Comment Count', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="none" <?php if ($wp_autosearch_suggest_options['order_by'] == 'none'): ?> selected="selected"<?php endif; ?>><?php _e('None', $wizardinfosys_autosearch->text_domain); ?></option>
                            </select>
                            <select name="order">
                                <option  value="ASC" <?php if ($wp_autosearch_suggest_options['order'] == 'ASC'): ?> selected="selected"<?php endif; ?>><?php _e('Ascending', $wizardinfosys_autosearch->text_domain); ?></option>
                                <option  value="DESC" <?php if ($wp_autosearch_suggest_options['order'] == 'DESC'): ?> selected="selected"<?php endif; ?>><?php _e('Descending', $wizardinfosys_autosearch->text_domain); ?></option>
                            </select>
                            <br />
                            <br />
                            <label for="no_of_results"><?php _e('Max number of results', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="no_of_results" name="no_of_results" type="text" class="integer small-text" size="3" value="<?php echo $wp_autosearch_suggest_options['no_of_results']; ?>" />
                            <br />
                            <br />
                            <label for="min_chars"><?php _e('Minimum characters', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="min_chars" name="min_chars" type="text" class="integer small-text" size="3" value="<?php echo $wp_autosearch_suggest_options['min_chars']; ?>" />
                            <br />
                            <br />
                            <label for="ajax_delay"><?php _e('Ajax delay', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="ajax_delay" name="ajax_delay" type="text" class="integer small-text" size="3" value="<?php echo $wp_autosearch_suggest_options['ajax_delay']; ?>" /><span class="padleft"><?php _e('Milliseconds', $wizardinfosys_autosearch->text_domain); ?></span>
                            <br />
                            <br />
                            <label for="cache_length"><?php _e('Cache length', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="cache_length" name="cache_length" type="text" class="integer small-text" size="3" value="<?php echo $wp_autosearch_suggest_options['cache_length']; ?>" />
                            <br />
                            <br />
                            <label for="description_limit"><?php _e('Result description limit', $wizardinfosys_autosearch->text_domain); ?><span id="form_label"></span></label>
                            <input id="description_limit" name="description_limit" type="text" class="integer small-text" size="3" value="<?php echo $wp_autosearch_suggest_options['description_limit']; ?>" /><span class="padleft"><?php _e('Characters', $wizardinfosys_autosearch->text_domain); ?></span>
                            <br />
                            <br />
                            <label for="title_limit"><?php _e('Result title limit', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="title_limit" name="title_limit" type="text" class="integer small-text" size="3" value="<?php echo $wp_autosearch_suggest_options['title_limit']; ?>" /><span class="padleft"><?php _e('Characters', $wizardinfosys_autosearch->text_domain); ?></span>
                            <br />
                            <br />
							<div class="disabledbutton">
								<label style="vertical-align:top;" for="wpextarea">
								<?php _e('Exclude Posts by ID (comma separated post ID-s eg: 2, 18, 300 ) ', $wizardinfosys_autosearch->text_domain); ?>:<span class="wpauto_pro_version">Pro</span> </label>     
								<textarea id="excluded_ids" class="ProVersion" name="excluded_ids" disabled><?php echo implode(', ', $wp_autosearch_suggest_options['excluded_ids']);?></textarea>
								<br/>
								<br/> 
								<label style="vertical-align:top;" for="wptextarea">
								<?php _e('Exclude Terms/Category by ID (comma separated term ID-s eg: 2, 18, 300 ) ', $wizardinfosys_autosearch->text_domain); ?>:<span class="wpauto_pro_version">Pro</span></label> 
								<textarea id="excluded_cats" class="ProVersion" name="excluded_cats" disabled ><?php echo implode(', ', $wp_autosearch_suggest_options['excluded_cats']); ?></textarea>
								<br/>
								<br/>
							</div>
							
                            <label for="full_search_url"><?php _e('Full Search URL', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="full_search_url" name="full_search_url" class="regular-text" type="text" size="55" value="<?php echo $wp_autosearch_suggest_options['full_search_url']; ?>" /><span class="padleft"><?php _e('%q% will be replaced by search keyword.', $wizardinfosys_autosearch->text_domain); ?></span>
                            <br />
							<br />
                            <br />
                            <br />
                            <div class="disabledbutton">
                            <label for="sortorder"><?php _e('Order of Types', $wizardinfosys_autosearch->text_domain); ?>: <span class="wpauto_pro_version">Pro</span></label> 
                            <select name="autocomplete_sortorder" class="ProVersion" id="autocomplete_sortorder" disabled="disabled">
				<option value="" selected="selected"><?php _e('Select type',$wizardinfosys_autosearch->text_domain) ?></option>
                                <option value="posts"><?php _e('Posts First', $wizardinfosys_autosearch->text_domain) ?></option>
                                <option value="terms"><?php _e('Taxonomies First', $wizardinfosys_autosearch->text_domain) ?></option>
                            </select> 
							</div>
                            <p><?php _e('When using multiple types (posts or taxonomies) this controls what order they are sorted in within the Auto serach complete drop down.', $wizardinfosys_autosearch->text_domain); ?></p>  
                       </div> 
                           </fieldset>
                          
                       
                    </fieldset>
                </div>
                <div tabid="2" style="display: none;">
                    <fieldset>
                        <legend><?php _e('Image Options', $wizardinfosys_autosearch->text_domain); ?></legend>
                       
                        <div class="inside" >
                            <?php
                            if ($wp_autosearch_suggest_options['thumb_image_display'] == 'true') {
                                $active_class = 'active';
                            } else {
                                $active_class = '';
                            }
                            ?>
                            <div class="">
                                <label for="thumb_image_display"><?php _e('Show Thumbnail images in results?', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="thumb_image_display"  id="thumb_image_display" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['thumb_image_display'] == 'true') echo 'checked="checked"'; ?>>
                            </div>

                            <br />
                            <br />

                            <label for="thumb_image_width"><?php _e('Image width', $wizardinfosys_autosearch->text_domain); ?>:</label>
                            <input name="thumb_image_width" id="thumb_image_width" class="small-text integer" type="text" size="3" value="<?php echo $wp_autosearch_suggest_options['thumb_image_width']; ?>"><span class="padleft"><?php _e('px', $wizardinfosys_autosearch->text_domain); ?></span>
                            <br/>
                            <br/>
                            <label for="thumb_image_height"><?php _e('Image Height', $wizardinfosys_autosearch->text_domain); ?>:</label>
                            <input name="thumb_image_height" id="thumb_image_height" class="small-text integer" type="text" size="3" value="<?php echo $wp_autosearch_suggest_options['thumb_image_height']; ?>"><span class="padleft"><?php _e('px', $wizardinfosys_autosearch->text_domain); ?></span>
                            <br/>
                            <br/>
					
                            <div>

                                <label for="get_first_image"><?php _e('Get first post image', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="get_first_image" hidd="1" id="get_first_image" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['get_first_image'] == 'true') echo 'checked="checked"'; ?>>
                            </div>

                            <br />
                            <br />
                            <div>

                                <label for="force_resize_first_image"><?php _e('Force resize first post image', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="force_resize_first_image" hidd="1"  id="force_resize_first_image" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['force_resize_first_image'] == 'true') echo 'checked="checked"'; ?>>
                            </div>


                            <br/>
                            <br/>

                               
                            <div>
                                <label for="thumb_image_crop"><?php _e('Crop', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="thumb_image_crop" hidd="1" id="thumb_image_crop" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['thumb_image_crop'] == 'true') echo 'checked="checked"'; ?>>
                                
                            </div>
                            <br />
                            <br />
							<div class="disabledbutton">
                            <label for="default_image"><?php _e('Default image', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                             <?php
                                $wizardinfosys_autosearch->helper->wi_image_upload_field($wp_autosearch_suggest_options['default_image'], 'default_image');
                                ?>
								</div>

                            <br/>
                        </div>
                    </fieldset>
                </div>
                <div tabid="3" style="display: none;">
                    <fieldset>
                        <legend><?php _e('Frontend Search Settings', $wizardinfosys_autosearch->text_domain); ?></legend>
                        <div class="inside" >
                            <div>
                                <label for="display_more_bar"><?php _e('Show More results.. text in the bottom of the search box?', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="display_more_bar" hidd="1" id="display_more_bar" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['display_more_bar'] == 'true') echo 'checked="checked"'; ?>>
                            </div> 
                            
                            <br/>
                            <br/>
                            <div>
                                <label for="display_result_title"><?php _e('Display result title', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="display_result_title" hidd="1" id="display_result_title" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['display_result_title'] == 'true') echo 'checked="checked"'; ?>>
                               
                            </div>
                            <br />
                            <br />
                           
                            <div>
                                <label for="enable_token"><?php _e('Enable token', $wizardinfosys_autosearch->text_domain); ?>:</label>
                                <input name="enable_token" hidd="1" id="enable_token" value="checked" type="checkbox" <?php if ($wp_autosearch_suggest_options['enable_token'] == 'true') echo 'checked="checked"'; ?>>
                                
                            </div>


                            <br/>
                            <br/>
						<div class="disabledbutton">
                            <label for="search_image"><?php _e('Default Search image icon', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
            <?php
					$wizardinfosys_autosearch->helper->wi_image_upload_field($wp_autosearch_suggest_options['search_image'], 'search_image');
            ?>
                        </div>
						</div>

                        <div class="inside search-more" >

                            <label for="try_full_search_text"><?php _e('Show more results...', $wizardinfosys_autosearch->text_domain); ?><span id="form_label"></span></label>
                            <input id="try_full_search_text" class="regular-text" name="try_full_search_text" type="text" size="30" value="<?php echo $wp_autosearch_suggest_options['try_full_search_text']; ?>">
                            <br><br>
                            <label for="no_results_try_full_search_text"><?php _e('No Results! Try Full Search...', $wizardinfosys_autosearch->text_domain); ?><span id="form_label"></span></label>
                            <input id="no_results_try_full_search_text" class="regular-text" name="no_results_try_full_search_text" type="text" size="30" value="<?php echo $wp_autosearch_suggest_options['no_results_try_full_search_text']; ?>">
<?php
$post_types = $wizardinfosys_autosearch->helper->wi_get_post_types();
foreach ($post_types as $name => $title) {
    ?>
                                <br />
                                <br />
                                <label for="post_type_text_<?php echo $name; ?>"><?php echo $name; ?>:<span id="form_label"></span></label>
                                <input id="post_type_text_<?php echo $name; ?>" class="regular-text" name="post_type_text[<?php echo $name; ?>]" type="text" size="30" value="<?php echo $wp_autosearch_suggest_options['title'][$name]; ?>" />
    <?php
}
?>
                        </div>   

                        <br/>

                        <br/>
                        <br/>   


                        <div disabled class="wpsearchYesNo disabledbutton">     
                            <label for="show_author"><?php _e('Show author in results?', $wizardinfosys_autosearch->text_domain); ?>:</label>
                            <input name="show_author" hidd="0" disabled id="show_author" value="checked" type="checkbox">
                           
                        </div>
                        <br/>
                        <br/>


                        <div class="wpsearchYesNo disabledbutton">     
                            <label for="show_date"><?php _e('Show date in results?', $wizardinfosys_autosearch->text_domain); ?>:</label>
                            <input name="show_date" hidd="1" id="show_date" value="checked" type="checkbox">
                            
                        </div>
                        <br/>
                        <br/>

                        <div class="wpsearchYesNo disabledbutton">     
                            <label for="description_result"><?php _e('Show description in results?', $wizardinfosys_autosearch->text_domain); ?>:</label>
                            <input name="description_result" hidd="1" id="description_result" value="checked" type="checkbox">

                           
                        </div>
                        <br/>
                        <br/>


                        
                    </fieldset>




                </div>



                <div tabid="4" style="display: none;">
                    <fieldset>
                        <legend><?php _e('Layout Options',$wizardinfosys_autosearch->text_domain); ?></legend>
                        <div class="inside" >
						   <div class="FreeOption">
                            <label for="results_even_bar"><?php _e('Search Results - even bar', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="results_even_bar" name="results_even_bar" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['results_even_bar']; ?>" />
                            <br />
                            <br />
                            <label for="results_odd_bar"><?php _e('Search Results - odd bar', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="results_odd_bar" name="results_odd_bar" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['results_odd_bar']; ?>" />
                            <br />
                            <br />
                            <label for="results_hover_bar"><?php _e('Search Results - hover bar', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="results_hover_bar" name="results_hover_bar" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['results_hover_bar']; ?>" />
                            <br />
                            <br />
                            <label for="results_even_text"><?php _e('Search Results - even text', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="results_even_text" name="results_even_text" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['results_even_text']; ?>" />
                            <br />
                            <br />
                            <label for="results_odd_text"><?php _e('Search Results - odd text', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="results_odd_text" name="results_odd_text" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['results_odd_text']; ?>" />
                            <br />
                            <br />
                            <label for="results_hover_text"><?php _e('Search Results - hover text', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="results_hover_text" name="results_hover_text" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['results_hover_text']; ?>" />
                            <br />
                            <br />
							</div>
							<div id="Paidbutton">
                            <label for="seperator_bar"><?php _e('Search Separator - bar', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="seperator_bar" name="seperator_bar" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['seperator_bar']; ?>" />
                            <br />
                            <br />
                            <label for="seperator_hover_bar"><?php _e('Search Separator - hover bar', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="seperator_hover_bar" name="seperator_hover_bar" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['seperator_hover_bar']; ?>" />
                            <br />
                            <br />
                            <label for="seperator_text"><?php _e('Search Separator - text', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="seperator_text" name="seperator_text" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['seperator_text']; ?>" />
                            <br />
                            <br />
                            <label for="seperator_hover_text"><?php _e('Search Separator - hover text', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="seperator_hover_text" name="seperator_hover_text" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['seperator_hover_text']; ?>" />
                            <br />
                            <br />
                            <label for="more_bar"><?php _e('Search More - bar', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="more_bar" name="more_bar" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['more_bar']; ?>" />
                            <br />
                            <br />
                            <label for="more_hover_bar"><?php _e('Search More - hover bar', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="more_hover_bar" name="more_hover_bar" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['more_hover_bar']; ?>" />
                            <br />
                            <br />
                            <label for="more_text"><?php _e('Search More - text', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="more_text" name="more_text" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['more_text']; ?>" />
                            <br />
                            <br />
                            <label for="more_hover_text"><?php _e('Search More - hover text', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="more_hover_text" name="more_hover_text" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['more_hover_text']; ?>" />
                            <br />
                            <br />
                            <label for="box_border"><?php _e('Search box - border', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="box_border" name="box_border" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['box_border']; ?>" />

                            <br />
                            <br />
                            <label for="box_background"><?php _e('Search box - background', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="box_background" name="box_background" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['box_background']; ?>" />

                            <br />
                            <br />
                            <label for="box_text"><?php _e('Search box - text', $wizardinfosys_autosearch->text_domain); ?>:<span id="form_label"></span></label>
                            <input id="box_text" name="box_text" type="text" size="20" class="color" value="<?php echo $wp_autosearch_suggest_options['color']['box_text']; ?>" />

<?php wp_nonce_field($wizardinfosys_autosearch->security, 'security') ?>
                            <br />
							</div>
                        </div>


                </div>
                <div tabid="7" style="display: none;">
                    <fieldset>
                        <legend><?php _e('Advanced Options', $wizardinfosys_autosearch->text_domain); ?></legend>
                        <div class="inside" >
                            <br />
                            <br />
                            <label><?php _e('Custom CSS', $wizardinfosys_autosearch->text_domain); ?>:<span id="postform"></span></label>
                            <textarea cols="90" rows="5" spellcheck='false' class="advance-options" name="custom_css" style="background-image: none; background-position: 0% 0%; background-repeat: repeat repeat;"><?php echo $wp_autosearch_suggest_options['custom_css']; ?></textarea>
                            <br />
                            <br />
                            <label><?php _e('Custom JavaScript', $wizardinfosys_autosearch->text_domain); ?>:<span id="postform"></span></label>
                            <textarea cols="90" rows="5" spellcheck='false' class="advance-options" name="custom_js" style="background-image: none; background-position: 0% 0%; background-repeat: repeat repeat;"><?php echo $wp_autosearch_suggest_options['custom_js']; ?></textarea>
                        </div>
                    </fieldset>

                    <div tabid="1" style="display: block;">
                        <fieldset>
                            <legend><?php _e('Database Compatibility', $wizardinfosys_autosearch->text_domain); ?></legend>

                            <p class="infoMsg">
               <?php _e('If you are experiencing issues with accent(diacritic) or case sensitiveness, you can force the search to try these tweaks.<br <i>The search works according to your database collation settings</i>, so please be aware that <b>this is not an effective way</b> of fixing database collation issues.<br> If you have case/diacritic issues then please read the <a href="http://dev.mysql.com/doc/refman/5.0/en/charset-syntax.html" target="_blank">MySql manual on collations</a> or consult a <b>database expert</b> - those issues should be treated on database level!', $wizardinfosys_autosearch->text_domain); ?>
                                
                            </p>

                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="item">
                <input type="hidden" name="asl_submit" value="1">
                <input type="submit" name="wp_autosearch_submit"  value="<?php _e('Save Options', $wizardinfosys_autosearch->text_domain); ?>" />
                <input name="wp_autosearch_default" type="submit" value="<?php _e('Reset Options', $wizardinfosys_autosearch->text_domain); ?>" />
            </div>
        </form>
    </div>   
		 <div class="wpsearch-box">
	 <div class="shortcode-label Profeature">
      


 <h3>Below Pro Plugin version Features:</h3>
 <div class="donate"> <a href="https://www.paypal.me/netflixtech"> <img alt="Donate Button with Credit Cards" src=" https://www.paypalobjects.com/webstatic/en_US/btn/btn_donate_cc_147x47.png" /> </a> </div>	
	<ul>
		<li>Exact matche search only.</li>
                <li>Exclude Posts by ID (comma separated post ID-s eg: 2, 18, 300 ) </li>
		<li>Exclude Terms/Category by ID (comma separated term ID-s eg: 2, 18, 300 )</li>
		<li>Order of Types</li>
		<li>Default image in search list.</li>
		<li>Default Search image icon in search list drop down results.</li>
		<li>Show author name in search list drop down results..</li>
		<li>Show post date name in search list drop down results.</li>
		<li>Show post description in search list drop down results.</li>
		<li>More Layout Features.</li>
	</ul>	  
      <h3>Support multiple languages: </h3>
	  <ul>
	    <li>Easy translate to any language</li>
		<li>All text of the plug-in are customizable</li>
		<li>Supported WPML plugin</li>
               
	</ul>
	<h3>Translations ready to </h3>
<ul>	
<li>English</li>
<li>Danish (Denmark)</li>
<li>German</li>
<li>French (France)</li>
<li>IT Italian (Italy)</li>
<li>Polish (Poland)</li>
	  </ul>
	 

	  <button class="Probutton"><a href="https://www.mojomarketplace.com/item/wordpress-live-search-and-auto-complete-search-plugin" target= "_blank"><?php _e('Pro version', $wizardinfosys_autosearch->text_domain); ?></a> </button>
	  <button class="Probutton"><a href="http://demo.netflixtech.com/" target= "_blank"><?php _e('Plugin documentation', $wizardinfosys_autosearch->text_domain); ?></a> </button>
    </div>
	</div>
</div>       

