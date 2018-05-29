<?php
if (!defined('ABSPATH')) exit('No direct script access allowed');

/**
 * Netflixtech Wordpress Autosearch Suggest
 * Content Ajax Results
 *  
 * @package    	Wordpress  Autosearch
 * @author     	Netflixtech <support@netflixtech.com>
 * @license     #
 * @link		#
 * @version    	1.0.0
 */


/********************************
*Remove wp-content paramter in search Query
*Search by Title
* 
* ********************************/
function wi_autosearch_scape_content( $search, $wp_query ) {
    
    if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
        global $wpdb;
        $q = $wp_query->query_vars;
        $n = ! empty( $q['exact'] ) ? '' : '%';
        $search = array();
        foreach ( ( array ) $q['search_terms'] as $term )
            $search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
        if ( ! is_user_logged_in() )
            $search[] = "$wpdb->posts.post_password = ''";

        $search = ' AND ' . implode( ' AND ', $search );
    }
   // echo $search;
    return $search;
}

/********************************************
 * Call AJax functions 
 * @return search content
 ******************************************/

function wi_get_search_results() {
     
	global $wizardinfosys_autosearch,$wpdb;
        if($wizardinfosys_autosearch->options->search_content != 'true'){
           add_filter( 'posts_search', 'wi_autosearch_scape_content', 100, 2 );
        }
        
	if($wizardinfosys_autosearch->options->enable_token =='true'){
		check_ajax_referer($wizardinfosys_autosearch->security, 'security');
	}
	$q = $wizardinfosys_autosearch->helper->wi_prepare_parameter($_REQUEST['q']);
	if(strlen($q)<$wizardinfosys_autosearch->options->min_chars){
		die();
	}
        
        /****************************************
         * Search by Taxonomy
         * 
         ***************************************/
        $tags = str_replace(" ", ",", $q);
        $q = apply_filters('get_search_query', $q);  
        $taxonomy_count=count((array)$wizardinfosys_autosearch->options->autocomplete_taxonomies );
        $post=count((array)$wizardinfosys_autosearch->options->post_types);
        $taxonomy_array=(array)$wizardinfosys_autosearch->options->autocomplete_taxonomies;
        $resultsTerms = array();
        if ( $taxonomy_count > 0 ) {
			$taxonomyTypes         = "AND ( tax.taxonomy = '" . implode( "' OR tax.taxonomy = '", $taxonomy_array ) . "') ";
			$queryStringTaxonomies = 'SELECT term.term_id as id, term.name as post_title, term.slug as guid, tax.taxonomy, 0 AS content_frequency, 0 AS title_frequency FROM ' . $wpdb->term_taxonomy . ' tax ' .
			                         'LEFT JOIN ' . $wpdb->terms . ' term ON term.term_id = tax.term_id WHERE 1 = 1 ' .
			                         'AND term.name LIKE "%' . $q . '%" ' .
			                         $taxonomyTypes .
			                         'ORDER BY tax.count DESC ' .
			                         'LIMIT 0, ' . $wizardinfosys_autosearch->options->no_of_results;
			
                        
                        $tempTerms             = $wpdb->get_results( $queryStringTaxonomies );
			
                        $term_count=0;
                        $this_post_type_title_term = __('Term Results', $wizardinfosys_autosearch->text_domain);
                        foreach ( $tempTerms as $term ) {
				$tempObject = array(
					'id'       => $term->id,
					'type'     => 'taxonomy',
					'taxonomy' => $term->taxonomy,
					'postType' => null
				);
				$linkTitle  = $term->post_title;
				$linkTitle  = apply_filters( 'search_autocomplete_modify_title', $linkTitle, $tempObject );
                                        $linkURL = get_term_link( $term->guid, $term->taxonomy );
					$linkURL = apply_filters( 'search_autocomplete_modify_url', $linkURL, $tempObject );
				
				$resultsTerms[$this_post_type_title_term][$term_count]['title']=$linkTitle;
                                $resultsTerms[$this_post_type_title_term][$term_count]['URL']=$linkURL;
                               
                                
                                $term_count++;
			}
		}
                
		

	 // apply filters to search query
	$post_types = (isset($wizardinfosys_autosearch->options->post_types) && count((array)$wizardinfosys_autosearch->options->post_types)>0) ? (array) $wizardinfosys_autosearch->options->post_types : array('post');
	
        $args = array(
		's' => $q,
		
		'orderby' => $wizardinfosys_autosearch->options->order_by,
		'order' => $wizardinfosys_autosearch->options->order,
		'post_type' => $post_types, // Array of selected post types
		'post_status' => 'publish',
                'posts_per_page' => $wizardinfosys_autosearch->options->no_of_results, 
                //'numberposts' => $wizardinfosys_autosearch->options->no_of_results,
		'post__not_in' => (array) $wizardinfosys_autosearch->options->excluded_ids, // Array of excluded ids
		'category__not_in' => (array) $wizardinfosys_autosearch->options->excluded_cats, // Array of excluded cat ids
           
	);
	
       
        
	$q = urlencode($q); //endcoded search string
	$query_results = new WP_Query($args); //Set paramter in WP_Query 
        
      
        if(!$query_results || count($query_results)==0){
		?>
		<a href="<?php echo esc_url(add_query_arg('s', $q, home_url('/'))); ?>" class="wp_autosearch_more"><?php echo $wizardinfosys_autosearch->options->no_results_try_full_search_text; ?></a>|||
		<?php
		die();
	 }
   	
        
	/**************************
         *  Save results to array
         ****************************/
	$resultsPosts = array();
	global $post;
	$counter = 0;
	$description_limit = $wizardinfosys_autosearch->options->description_limit;
	$title_limit = $wizardinfosys_autosearch->options->title_limit;
	$this_post_type_title = __('Post Results', $wizardinfosys_autosearch->text_domain);
	foreach($query_results->get_posts() as $post):	setup_postdata($post);
		if($wizardinfosys_autosearch->options->split_results_by_type =='true'){
			$this_post_type_name = get_post_type();
			$this_post_type_title = $wizardinfosys_autosearch->options->title->$this_post_type_name;
		}
		$resultsPosts[$this_post_type_title][$counter] ['URL'] =  get_permalink();
		$item_title = "";

		/*************************************************************
                 * Some languages use special characters 
                 * and shouldn't be converted in HTML Entities
                 *******************************************************/
		$item_title = html_entity_decode($item_title, ENT_QUOTES, 'UTF-8');
		if($title_limit>0){
			$item_title = $wizardinfosys_autosearch->helper->wi_limit_str(get_the_title(), $title_limit);
			$item_title = apply_filters('wp_result_title', $item_title, get_the_id());
		}
		$resultsPosts[$this_post_type_title][$counter] ['title'] = $item_title;
		$description = "";
		if($description_limit>0){
			$description = get_the_excerpt();
			if(empty($description)){
				$description = get_the_content();
			}

			// Execute shortcodes and apply shortcodes
			$description = apply_filters('the_content', $description);

			// Execute all shortcodes in contetns
			$description = do_shortcode($description);

			// Remove any possible remaning shortcode
			$description = strip_shortcodes($description);

			/***************************
                        *  Remove hidden text 
                        *  Remove unwanted html tags
                         *************************/
			$description = wi_autosearch_strip_html_tags($description);

			/*************************
                         *  Apply custom filters
                         **************************/
			$description = apply_filters('wp_autosearch_result_description', $description, get_the_id());
		}
                /************************************
                 * Set post date in search results
                 ************************************/
                $resultsPosts[$this_post_type_title][$counter] ['date']=  get_the_date();
                
                /**********************************
                 * Set Post author name in search results
                 *********************************/
                $resultsPosts[$this_post_type_title][$counter] ['author']=  get_the_author();
                
		$resultsPosts[$this_post_type_title][$counter] ['text'] = $wizardinfosys_autosearch->helper->wi_limit_str($description, $description_limit);
		if($wizardinfosys_autosearch->options->thumb_image_display =='true'){
			$resultsPosts[$this_post_type_title][$counter] ['image'] = $wizardinfosys_autosearch->helper->wi_post_image();
		}
		
                $counter++;
                
	endforeach;
	?>
	
<?php 

   /*******************************************
    *  Sort Results order by  posts and terms
    *****************************************/
if ( $taxonomy_count > 0 ) {
    if ($wizardinfosys_autosearch->options->autocomplete_sortorder == 'posts' ) {
			$results = array_merge( $resultsPosts, $resultsTerms );
		} else {
			$results = array_merge( $resultsTerms, $resultsPosts );
	}
    }else{
            $results = $resultsPosts;
    }    

     
	//$builtin_query_url = esc_url(add_query_arg('s%', $q, home_url('/')));
	$builtin_query_url = str_replace("%q%", $q, $wizardinfosys_autosearch->options->full_search_url);
        foreach ($results as $post_type => $result) {
		$post_type_name = array_search($post_type, (array) $wizardinfosys_autosearch->options->title);
		if($post_type_name){
			$post_type_query_url = esc_url(add_query_arg('post_type', $post_type_name, $builtin_query_url));
			?>
			<a class="wp_autosearch_category wp_autosearch_clickable" href="<?php echo $post_type_query_url; ?>" data-q="<?php echo $q; ?>"><?php echo $post_type; ?></a>|||
			<?php
		}elseif($wizardinfosys_autosearch->options->display_result_title == 'true'){
			?>
			<span class="wp_autosearch_category" data-q="<?php echo $q; ?>"><?php echo $post_type; ?></span>|||
			<?php
		}

		foreach ($result as $item){
			?>
			<a href="<?php echo $item['URL']; ?>" class="wp_autosearch_result">
			<?php
				if($wizardinfosys_autosearch->options->thumb_image_display == 'true' && !empty($item['image'])){ ?>
				   <img alt="" width="<?php echo $wizardinfosys_autosearch->options->thumb_image_width; ?>" height="<?php echo $wizardinfosys_autosearch->options->thumb_image_height; ?>" class="wp_autosearch_image" src="<?php echo $item['image']; ?>" />
			<?php } ?>
                                
				<span class="searchheading"><?php echo $wizardinfosys_autosearch->helper->wi_remove_illegal($item['title']); ?></span>
				 <?php  if($wizardinfosys_autosearch->options->description_result == 'true'){ ?>
                                    <span class="wp_autosearch_item_description"><?php if(empty($item['text'])) echo "&nbsp;"; else echo $wizardinfosys_autosearch->helper->wi_remove_illegal($item['text']); ?></span>
                                 <?php }?>   
                        
                             <?php  if($wizardinfosys_autosearch->options->show_author == 'true'){ ?>
                                <span class="autour-name"><?php if(!empty($item['author'])){ $item['author'];} ?> </span>
                              <?php }?>
                               
                               <?php  if($wizardinfosys_autosearch->options->show_date == 'true'){ ?>
                                <span class="date-name"><?php if(!empty($item['date'])){ echo $item['date'];} ?> </span>
                              <?php }?>
			</a>|||
			<?php
		}
	}
if($wizardinfosys_autosearch->options->display_more_bar == 'true'){
	?>
      <a href="<?php echo $builtin_query_url; ?>" class="wp_autosearch_more"><?php echo $wizardinfosys_autosearch->options->try_full_search_text; ?></a>|||
     
	<?php 
}else{
	?>
	
	<?php 
}
die();
}


?>