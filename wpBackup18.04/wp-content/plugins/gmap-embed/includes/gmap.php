<?php
if (!defined('ABSPATH')) exit;
if (isset($_GET['page'])) {
    if (isset($_POST['wpgmapembed_key'])) {
        $api_key = trim($_POST['wpgmapembed_key']);
        if ($api_key != '') {
            if (get_option('wpgmap_api_key') !== false) {
                update_option('wpgmap_api_key', $api_key, '', 'yes');
            } else {
                add_option('wpgmap_api_key', $api_key, '', 'yes');
            }

        }
    }

    $wpgmap_page = esc_html($_GET['page']);
    $wpgmap_tag = '';
    if (isset($_GET['tag'])) {
        $wpgmap_tag = esc_html($_GET['tag']);
    }
    ?>
    <div class="wrap">
        <script type="text/javascript"
                src="<?php echo esc_url(plugins_url("../assets/js/srm_gmap_loader.js", __FILE__)); ?>"></script>
        <div id="gmap_container_inner">
            <!--modal contents-->
            <div class="error">
                <p style="font-size:17px;"><strong><?php _e('Notice: If it seems Direction option is not working properly, Please take api key again from settings tab.','gmap-embed');?></strong></p>
            </div>
            <ul id="wp-gmap-nav">
                <li class="<?php echo ($wpgmap_page == 'wpgmapembed' && $wpgmap_tag == '') ? 'active' : ''; ?>">
                    <a href="<?php echo admin_url(); ?>admin.php?page=wpgmapembed" data-id="wp-gmap-all"
                       class="media-menu-item"><?php _e('All Maps','gmap-embed');?></a>
                </li>
                <li class="<?php echo $wpgmap_tag == 'new' ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(admin_url() . 'admin.php?page=wpgmapembed&tag=new'); ?>"
                       data-id="wp-gmap-new"
                       class="media-menu-item"><?php _e('Create New Map','gmap-embed');?></a>
                </li>
                <li class="<?php echo $wpgmap_tag == 'settings' ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url(admin_url() . 'admin.php?page=wpgmapembed&tag=settings'); ?>"
                       data-id="wp-gmap-settings"
                       class="media-menu-item"><?php _e('Settings','gmap-embed');?></a>
                </li>
                <li>
                    <a target="_blank" href="https://www.youtube.com/watch?v=Lak-tJjGjl8" class="media-menu-item">
                        <?php _e('Need Help ?','gmap-embed');?></a>
                </li>
                <span class="spinner" style="margin-right: 20px !important;float:right"></span>
            </ul>

            <div id="wp-gmap-tabs">
                <?php
                if (isset($_GET['message'])) {
                    ?>
                    <div class="message">
                        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
                            <p>
                                <strong>
                                    <?php
                                    $message_status = $_GET['message'];
                                    switch ($message_status) {
                                        case 1:
                                            echo __('Map has been created Successfully.','gmap-embed');
                                            break;
                                        case 2:
                                            echo __('Map Updated Successfully.','gmap-embed');
                                            break;
                                        case 3:
                                            echo __('Settings updated Successfully.','gmap-embed');
                                            break;
                                        case -1:
                                            echo __('Map Deleted Successfully.','gmap-embed');
                                            break;
                                    }
                                    ?>
                                </strong>
                            </p>
                            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
                            </button>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <?php
                if (get_option('wpgmap_api_key') == false) {
                    require_once(plugin_dir_path(__FILE__) . '/wpgmap_settings.php');
                }
                ?>
                <!---------------------------Maps List-------------->
                <?php
                if ($wpgmap_page == 'wpgmapembed' && $wpgmap_tag == '') {
                    ?>
                    <div class="wp-gmap-tab-content active" id="wp-gmap-all">
                        <?php
                        require_once(plugin_dir_path(__FILE__) . '/wpgmap_list.php');
                        ?>
                    </div>
                    <?php
                }
                ?>
                <!---------------------------Create New Map-------------->

                <div
                    class="wp-gmap-tab-content <?php echo ($_GET['page'] == 'wpgmapembed' && $_GET['tag'] == 'new') ? 'active' : ''; ?>"
                    id="wp-gmap-new">
                    <?php
                    if ($wpgmap_page == 'wpgmapembed' && $wpgmap_tag == 'new') {
                        require_once(plugin_dir_path(__FILE__) . '/wpgmap_create.php');
                    }
                    ?>
                </div>

                <!---------------------------Existing map update-------------->

                <div
                    class="wp-gmap-tab-content <?php echo ($wpgmap_page == 'wpgmapembed' && $wpgmap_tag == 'edit') ? 'active' : ''; ?>"
                    id="wp-gmap-edit">
                    <?php
                    if ($wpgmap_page == 'wpgmapembed' && $wpgmap_tag == 'edit') {
                        require_once(plugin_dir_path(__FILE__) . '/wpgmap_edit.php');
                    }
                    ?>
                </div>

                <!---------------------------Plugin Settings-------------->

                <div
                    class="wp-gmap-tab-content <?php echo ($wpgmap_page == 'wpgmapembed' && $wpgmap_tag == 'settings') ? 'active' : ''; ?>"
                    id="wp-gmap-settings">
                    <?php
                    if ($wpgmap_page == 'wpgmapembed' && $wpgmap_tag == 'settings') {
                        require_once(plugin_dir_path(__FILE__) . '/wpgmap_settings.php');
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
    <?php
}
?>