 /**
 * Wordpress Autosearch Suggest scripts 
 *
 * @package    	WP Autosearch
 * @license     #
 * @link	#
 * @version    	1.0
 */
jQuery(document).ready(function($) {
	var excluded_ids = jQuery("#excluded_ids");
       
    if(excluded_ids.val()=="0"){
		excluded_ids.val("");
	}
	
	jQuery(".integer").numeric({ decimal: false, negative: false });
	postboxes.add_postbox_toggles(pagenow);
});