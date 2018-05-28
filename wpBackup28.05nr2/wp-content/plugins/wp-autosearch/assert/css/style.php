<?php

 /**
 * Netflixtech Wordpress Autosearch Suggest scripts 
 *
 * Generates custom CSS stylesheet based on plugin options
 *
 * @package    	WP Autosearch
 * @author     	Netflixtech <support@netflixtech.com>
 * @license     #
 * @link        #
 * @version    	1.0
 */

global $wizardinfosys_autosearch;
?>
.wp_autosearch_suggestions {
	border-width: 1px;
	border-color: #<?php echo $wizardinfosys_autosearch->options->color->box_border; ?> !important;
	border-style: solid;
	width: 190px;
	background-color: #a0a0a0;
	font-size: 10px;
	line-height: 14px;
	border: none !important;
}
.wp_autosearch_suggestions a {
	display: block; 
	clear: left;
	text-decoration: none;
}
.wp_autosearch_suggestions a img {
	float: left;
	padding: 3px 5px;
}
.wp_autosearch_suggestions a .searchheading {
	display: block;
	font-weight: bold;
	padding-top: 5px;
}
.wp_autosearch_suggestions .wps_odd a {
	color: #<?php echo $wizardinfosys_autosearch->options->color->results_odd_text; ?>;
}
.wp_autosearch_suggestions .wps_even a {
	color: #<?php echo $wizardinfosys_autosearch->options->color->results_even_text; ?>;
}
.wp_autosearch_suggestions .wp_autosearch_category {
	font-size: 12px;
	padding: 5px;
	display: block;
	background-color: #<?php echo $wizardinfosys_autosearch->options->color->more_bar; ?> !important;
	color: #<?php echo $wizardinfosys_autosearch->options->color->seperator_text; ?> !important;
}

.wps_over a.wp_autosearch_category{
	color: #<?php echo $wizardinfosys_autosearch->options->color->seperator_hover_text; ?> !important;
	background-color: <?php echo $wizardinfosys_autosearch->options->color->seperator_hover_bar; ?> !important;
}

.wp_autosearch_suggestions .wp_autosearch_more {
	padding: 5px;
	display: block;
	background-color: #<?php echo $wizardinfosys_autosearch->options->color->more_bar; ?> !important;
	color: #<?php echo $wizardinfosys_autosearch->options->color->more_text; ?> !important;
	background-image: url(<?php echo $wizardinfosys_autosearch->url ?>/assert/image/arrow.png);
	background-repeat: no-repeat;
	background-position: 99% 50%;
	cursor: pointer;
}
.wps_over a.wp_autosearch_more{
	color: #<?php echo $wizardinfosys_autosearch->options->color->more_hover_text; ?> !important;
	background-color: #<?php echo $wizardinfosys_autosearch->options->color->more_hover_bar; ?> !important;
}
.wp_autosearch_suggestions .wp_autosearch_more a {
	height: auto;
	color: #<?php echo $wizardinfosys_autosearch->options->color->more_text; ?> !important;
}
.wp_autosearch_image {
	margin: 2px;
}
.wp_autosearch_result {
	padding-left: 5px;
}
.wp_autosearch_indicator {
	background: url('<?php echo $wizardinfosys_autosearch->url ?>/assert/image/indicator.gif') no-repeat scroll 100% 50% #FFF !important;
}
.wp_autosearch_suggestions {
	padding: 0px;
	background-color: white;
	overflow: hidden;
	z-index: 99999;
}
.wp_autosearch_suggestions ul {
	width: 100%;
	list-style-position: outside;
	list-style: none;
	padding: 0;
	margin: 0;
}
<?php
if(false && $wizardinfosys_autosearch->options->display_more_bar != 'true'){ // disabled for now
?>
.wp_autosearch_suggestions li:last-child {
	padding-bottom: 1px;
}
<?php
}
?>
.wp_autosearch_suggestions li {
	margin: 0px;
	cursor: pointer;
	display: block;
	font: menu;
	font-size: 12px;
	line-height: 16px;
	overflow: hidden;
}
.wps_odd {
	background-color: #<?php echo $wizardinfosys_autosearch->options->color->results_odd_bar; ?>;
}
.wps_even {
	background-color: #<?php echo $wizardinfosys_autosearch->options->color->results_even_bar; ?>;
}
.ac_over {
	background-color: #<?php echo $wizardinfosys_autosearch->options->color->results_hover_bar; ?>;
	color: #<?php echo $wizardinfosys_autosearch->options->color->results_hover_text; ?> !important;
}
.ac_over a, .ac_over a span {
	color: #<?php echo $wizardinfosys_autosearch->options->color->results_hover_text; ?> !important;
}
.wp_autosearch_input{
	width: 88% !important;
	height: 50px !important;
	border: none !important;
	background-color: #<?php echo $wizardinfosys_autosearch->options->color->box_background; ?> !important;
	outline: none;
	box-shadow: 0px 0px 0px #FFF !important;
	-moz-box-shadow: 0px 0px 0px #FFF !important;
	-webkit-box-shadow: 0px 0px 0px #FFF !important;
	text-indent: 5px !important;
	margin: 0 !important;
	padding: 0 !important;
	overflow: hidden;
	float: left;
	line-height: 29px;
	vertical-align: middle;
	color: #<?php echo $wizardinfosys_autosearch->options->color->box_text; ?> !important;
}
.wp_autosearch_wrapper{
	width: 100%;
}

.wp_autosearch_suggestions{
	box-shadow: #888888 5px 10px 10px;
	-webkit-box-shadow: #888888 5px 10px 10px;
}
.wp_autosearch_submit, .wp_autosearch_submit:hover, .wp_autosearch_submit:active, .wp_autosearch_submit:visited{
	cursor: pointer;
	height: 50px;
    width: 54px;
	overflow: hidden;
	background: transparent url('<?php echo $wizardinfosys_autosearch->options->search_image; ?>') no-repeat scroll !important;
	float: right;
	font-size: 100%;
	-webkit-appearance: none;
	outline: none;
	position: absolute;
	right: 0px;
	top: 0px;
	background-color: transparent;
	border: none ;
	border-radius: 0 !important;
	padding: 0 !important;
	margin: 0 !important;
	display: block !important;
}
.wp_autosearch_form_wrapper{
	width: 100%;
	border: 1px solid #<?php echo $wizardinfosys_autosearch->options->color->box_border; ?> !important;
	height: 52px !important;
	background-color: #<?php echo $wizardinfosys_autosearch->options->color->box_background; ?> !important;
	position: relative;
}
.wp_autosearch_item_description{
	padding-right: 2px;
	padding-left: 2px;
}

.wp_autosearch_form_label{
	display: none;
}


