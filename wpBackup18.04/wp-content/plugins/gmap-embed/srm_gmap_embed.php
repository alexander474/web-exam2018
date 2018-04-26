<?php
/*
  Plugin Name: Google Map Embed
  Plugin URI: http://www.srmilon.com
  Description: The plugin will help to embed Google Map in post and pages also in sidebar as widget.
  Author: SRMILON
  Text Domain: gmap-embed
  Domain Path: /languages
  Author URI: http://www.srmilon.com
  Version: 1.3.0
 */

if (!defined('ABSPATH')) exit;
load_plugin_textdomain('gmap-embed', false, dirname(plugin_basename(__FILE__, '/languages/')));
if (!class_exists('srm_gmap_embed_main')) {

    class srm_gmap_embed_main
    {

        private $plugin_name = 'Google Map SRM';
        public $wpgmap_api_key = 'AIzaSyBcVcz5OZ6eNBi5d7CFYHIdtsEI5BQlm68';


        /**
         * constructor function
         */
        function __construct()
        {
            $this->wpgmap_api_key = get_option('wpgmap_api_key');
            add_action('activated_plugin', array($this, 'wpgmap_do_after_activation'), 10, 2);
            add_action('wp_enqueue_scripts', array($this, 'gmap_enqueue_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_gmap_scripts'));
            add_action('admin_menu', array($this, 'gmap_create_menu'));
            add_action('admin_init', array($this, 'gmap_register_fields'));
            add_action('wp_ajax_wpgmapembed_save_map_data', array($this, 'save_wpgmapembed_data'));
            add_action('wp_ajax_wpgmapembed_load_map_data', array($this, 'load_wpgmapembed_list'));
            add_action('wp_ajax_wpgmapembed_popup_load_map_data', array($this, 'load_popup_wpgmapembed_list'));
            add_action('wp_ajax_wpgmapembed_get_wpgmap_data', array($this, 'get_wpgmapembed_data'));
            add_action('wp_ajax_wpgmapembed_remove_wpgmap', array($this, 'remove_wpgmapembed_data'));
        }

        /**
         * To set options values
         */


        /**
         * To enqueue scripts for front-end
         */
        public function gmap_enqueue_scripts()
        {
            //including map library
            wp_enqueue_script('srm_gmap_api', 'https://maps.googleapis.com/maps/api/js?key=' . $this->wpgmap_api_key . '&libraries=places', array('jquery'));
        }

        function enqueue_admin_gmap_scripts()
        {
            global $pagenow;
            if ($pagenow == 'post.php' || $pagenow == 'post-new.php' || @$_GET['page'] == 'wpgmapembed') {
                wp_enqueue_script('wp-gmap-api', 'https://maps.google.com/maps/api/js?key=' . $this->wpgmap_api_key . '&libraries=places', array('jquery'), '20161019', true);
                wp_enqueue_script('wp-gmap-custom-js', plugins_url('assets/js/custom.js', __FILE__), array('wp-gmap-api'), '20161019', false);
                wp_enqueue_style('wp-gmap-embed-css', plugins_url('assets/css/wp-gmap-style.css', __FILE__));
            }
        }


        /**
         * To create menu in admin panel
         */
        public function gmap_create_menu()
        {

            //create new top-level menu
            add_menu_page($this->plugin_name, $this->plugin_name, 'administrator', 'wpgmapembed', array($this, 'srm_gmap_main'), 'dashicons-location',11);

            //to create sub menu
            add_submenu_page('wpgmapembed', __("Add new Map","gmap-embed"), __("Add New", "gmap-embed"), 'administrator', 'wpgmapembed&tag=new', array($this, 'srm_gmap_new'), 'dashicons-location');
        }

        public function gmap_register_fields()
        {
            //register fields
            register_setting('gmap_settings_group', 'gmap_title');
            register_setting('gmap_settings_group', 'wpgmap_heading_class');
            register_setting('gmap_settings_group', 'gmap_lat');
            register_setting('gmap_settings_group', 'gmap_long');
            register_setting('gmap_settings_group', 'gmap_width');
            register_setting('gmap_settings_group', 'gmap_height');
            register_setting('gmap_settings_group', 'gmap_zoom');
            register_setting('gmap_settings_group', 'gmap_disable_zoom_scroll');
            register_setting('gmap_settings_group', 'gmap_type');
        }

        /**
         * Google Map Embed Mail Page
         */
        public function srm_gmap_main()
        {
            require plugin_dir_path(__FILE__) . '/includes/gmap.php';
        }

        /*
         * To update post meta data
         */

        public function __update_post_meta($post_id, $field_name, $value = '')
        {
            if (!get_post_meta($post_id, $field_name)) {
                add_post_meta($post_id, $field_name, $value);
            } else {
                update_post_meta($post_id, $field_name, $value);
            }
        }

        /**
         * To save New Map Data
         */

        public function save_wpgmapembed_data()
        {
            $error = '';
            // Getting ajax fileds value
            $meta_data = array(
                'wpgmap_title' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_title'])),
                'wpgmap_heading_class' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_heading_class'])),
                'wpgmap_show_heading' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_show_heading'])),
                'wpgmap_latlng' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_latlng'])),
                'wpgmap_map_zoom' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_map_zoom'])),
                'wpgmap_disable_zoom_scroll' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_disable_zoom_scroll'])),
                'wpgmap_map_width' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_map_width'])),
                'wpgmap_map_height' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_map_height'])),
                'wpgmap_map_type' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_map_type'])),
                'wpgmap_map_address' => sanitize_text_field(esc_html($_POST['map_data']['wpgmap_map_address'])),
                'wpgmap_show_infowindow' => sanitize_text_field($_POST['map_data']['wpgmap_show_infowindow']),
                'wpgmap_enable_direction' => sanitize_text_field($_POST['map_data']['wpgmap_enable_direction'])
            );
            $action_type = sanitize_text_field(esc_html($_POST['map_data']['action_type']));
            if ($meta_data['wpgmap_latlng'] == '') {
                $error = "Please input Latitude and Longitude";
            }
            if (strlen($error) > 0) {
                echo json_encode(array(
                    'responseCode' => 0,
                    'message' => $error
                ));
                exit;
            } else {

                if ($action_type == 'save') {
                    // saving post array
                    $post_array = array(
                        'post_type' => 'wpgmapembed'
                    );
                    $post_id = wp_insert_post($post_array);

                } elseif ($action_type == 'update') {
                    $post_id = intval($_POST['map_data']['post_id']);
                }

                // Updating post meta
                foreach ($meta_data as $key => $value) {
                    $this->__update_post_meta($post_id, $key, $value);
                }
                $returnArray = array(
                    'responseCode' => 1,
                    'post_id' => $post_id
                );
                if ($action_type == 'save') {
                    $returnArray['message'] = 'Created Successfully.';
                } elseif ($action_type == 'update') {
                    $returnArray['message'] = 'Updated Successfully.';
                }
                echo json_encode($returnArray);
                exit;
            }
        }

        public function load_wpgmapembed_list()
        {
            $content = '';
            $args = array(
                'post_type' => 'wpgmapembed',
				'posts_per_page'=>-1
            );
            $mapsList = new WP_Query($args);

            if ($mapsList->have_posts()) {
                while ($mapsList->have_posts()) {
                    $mapsList->the_post();
                    $title = get_post_meta(get_the_ID(), 'wpgmap_title', true);
                    $content .= '<div class="wp-gmap-single">
                                        <div class="wp-gmap-single-left">
                                            <div class="wp-gmap-single-title">
                                                ' . $title . '
                                            </div>
                                            <div class="wp-gmap-single-shortcode">
                                                <input class="wpgmap-shortcode regular-text" type="text" value="' . esc_attr('[gmap-embed id=&quot;' . get_the_ID() . '&quot;]') . '"
                                                       onclick="this.select()"/>
                                            </div>
                                        </div>
                                        <div class="wp-gmap-single-action">
                                            <a href="?page=wpgmapembed&tag=edit&id=' . get_the_ID() . '" class="button media-button button-primary button-large wpgmap-edit" data-id="' . get_the_ID() . '">
                                                '.__('Change','gmap-embed').'
                                            </a>
                                            <button type="button"
                                                    class="button media-button button-danger button-large wpgmap-insert-delete" data-id="' . get_the_ID() . '" style="background-color: red;color: white;opacity:0.7;">
                                                X
                                            </button>
                                        </div>
                                    </div>';
                }
            } else {
                $content = __("You have not created any Map yet. ","gmap-embed");
                $content .= '<a href="' . esc_url(admin_url()) . 'admin.php?page=wpgmapembed&amp;tag=new"
                                           data-id="wp-gmap-new" class="media-menu-item">'.__("Create New Map","gmap-embed").'</a>';
            }

            echo $content;


        }

        public function load_popup_wpgmapembed_list()
        {
            $content = '';
            $args = array(
                'post_type' => 'wpgmapembed'
            );
            $mapsList = new WP_Query($args);

            while ($mapsList->have_posts()) {
                $mapsList->the_post();
                $title = get_post_meta(get_the_ID(), 'wpgmap_title', true);
                $content .= '<div class="wp-gmap-single">
                                        <div class="wp-gmap-single-left">
                                            <div class="wp-gmap-single-title">
                                                ' . $title . '
                                            </div>
                                            <div class="wp-gmap-single-shortcode">
                                                <input class="wpgmap-shortcode regular-text" type="text" value="[gmap-embed id=&quot;' . get_the_ID() . '&quot;]"
                                                       onclick="this.select()"/>
                                            </div>
                                        </div>
                                        <div class="wp-gmap-single-action">
                                            <button type="button"
                                                    class="button media-button button-primary button-large wpgmap-insert-shortcode">
                                                Insert
                                            </button>                                            
                                        </div>
                                    </div>';
            }
            echo $content;
            exit;


        }

        public function get_wpgmapembed_data($gmap_id = '')
        {
            if ($gmap_id == '') {
                $gmap_id = intval($_POST['wpgmap_id']);
            }

            $gmap_data = array(
                'wpgmap_title' => get_post_meta($gmap_id, 'wpgmap_title', true),
                'wpgmap_heading_class' => get_post_meta($gmap_id, 'wpgmap_heading_class', true),
                'wpgmap_show_heading' => get_post_meta($gmap_id, 'wpgmap_show_heading', true),
                'wpgmap_latlng' => get_post_meta($gmap_id, 'wpgmap_latlng', true),
                'wpgmap_map_zoom' => get_post_meta($gmap_id, 'wpgmap_map_zoom', true),
                'wpgmap_disable_zoom_scroll' => get_post_meta($gmap_id, 'wpgmap_disable_zoom_scroll', true),
                'wpgmap_map_width' => get_post_meta($gmap_id, 'wpgmap_map_width', true),
                'wpgmap_map_height' => get_post_meta($gmap_id, 'wpgmap_map_height', true),
                'wpgmap_map_type' => get_post_meta($gmap_id, 'wpgmap_map_type', true),
                'wpgmap_map_address' => get_post_meta($gmap_id, 'wpgmap_map_address', true),
                'wpgmap_show_infowindow' => get_post_meta($gmap_id, 'wpgmap_show_infowindow', true),
                'wpgmap_enable_direction' => get_post_meta($gmap_id, 'wpgmap_enable_direction', true)
            );

            return json_encode($gmap_data);
        }

        public function remove_wpgmapembed_data()
        {

            $meta_data = array(
                'wpgmap_title',
                'wpgmap_heading_class',
                'wpgmap_show_heading',
                'wpgmap_latlng',
                'wpgmap_map_zoom',
                'wpgmap_disable_zoom_scroll',
                'wpgmap_map_width',
                'wpgmap_map_height',
                'wpgmap_map_type',
                'wpgmap_map_address',
                'wpgmap_show_infowindow',
                'wpgmap_enable_direction'
            );

            $post_id = intval($_POST['post_id']);
            wp_delete_post($post_id);
            foreach ($meta_data as $field_name => $value) {
                delete_post_meta($post_id, $field_name, $value);
            }
            $returnArray = array(
                'responseCode' => 1,
                'message' => "Deleted Successfully."
            );
            echo json_encode($returnArray);
            exit;
        }

        /**
         * Works on when plugin is activated successfully
         */

        function wpgmap_do_after_activation($plugin, $network_activation)
        {
            // do stuff
            if ($plugin == 'gmap-embed/srm_gmap_embed.php') {
                wp_redirect(admin_url('admin.php?page=wpgmapembed'));
                exit;
            }
        }

    }


}
new srm_gmap_embed_main();
// including requird files
require_once plugin_dir_path(__FILE__) . '/includes/widget.php';
require_once plugin_dir_path(__FILE__) . '/includes/shortcodes.php';

if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
    require_once plugin_dir_path(__FILE__) . '/includes/wpgmap_popup_content.php';
}

load_plugin_textdomain('gmap-embed', false, dirname(plugin_basename(__FILE__)) . '/languages');