 /**
 * Netflixtech Wordpress Autosearch Suggest Ajax Script
 *
 * @package    	WP Autosearch
 * @author     	Netflixtech <support@netflixtech.com>
 * @license     #
 * @link		#
  * @version    1.0
 */
 
// JavaScript document
(function($) {
	$(document).ready(function() {
	$(function() {
		$(".wp_autosearch_input").cn_autocomplete(wp_autosearch_config.ajax_url, {
			width: $(".wp_autosearch_form_wrapper").outerWidth()-2,					
			scroll: false,
			minChars: wp_autosearch_config.min_chars,
			delay: wp_autosearch_config.ajax_delay,
			cacheLength: wp_autosearch_config.cache_length,
			highlight : false,
			matchSubset: false,
                        loadingClass: "wp_autosearch_indicator",
                        resultsClass: "wp_autosearch_suggestions",
			max: 1000,
			selectFirst: false,
			extraParams: {action: "wi_get_search_results", security: wp_autosearch_config.nonce},
			parse: function (data) {
				this.width = $(".wp_autosearch_form_wrapper").outerWidth()-2;
				var parsed = [];
				var rows = data.split("|||");
				for (var i=0; i < rows.length; i++) {
					var row = $.trim(rows[i]);
					if (row) {
						parsed[parsed.length] = {
							data: row
						};
					}
				}
				return parsed;
			},
			formatItem: function(item) {				
				return item;
			}
			}).result(function(e, item) {
				var url = $(item).filter("a").attr("href");
				var title = $(item).find(".searchheading").text();
                if(title.length == 0){
					title = $(item).data("q");
				}
                $(".wp_autosearch_input").val(title);
				if(typeof url !== "undefined"){
					location.href = $(item).filter("a").attr("href");
				}
			});						
		});
	});
})(jQuery);

(function($) {
$(document).ready(function() {
		$(function() {
			$(".wp_autosearch_submit").click(function(e){
				e.preventDefault();
				var $this = $(this);
				var full_search_url = $this.closest("#wizardinfosys_autosearch_form").attr("full_search_url");
				var keyword = $this.siblings(".wp_autosearch_input").val();
				full_search_url = full_search_url.replace("%q%", keyword);
				location.href = full_search_url;
			});
		});
	});
})(jQuery);