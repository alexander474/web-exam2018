<?php
if (!defined('ABSPATH')) exit;
//this button will show a popup that contains inline content
add_action('media_buttons_context', 'add_srm_gmap_embed_custom_button');

//This will be shown in the inline modal
add_action('admin_footer', 'srm_add_inline_popup_content');

//action to add a custom button to the content editor
function add_srm_gmap_embed_custom_button($context)
{

    //path to my icon
    $img = plugins_url('../gmap_icon_18.png', __FILE__);

    //the id of the container I want to show in the popup
    $container_id = 'wp_gmap_popup_container';

    //our popup's title
    $title = 'Select your map properties to insert into post';

    //append the icon
    $context .= "<a class='button  thickbox' title='{$title}'
    href='#TB_inline?width=700&height=450&inlineId={$container_id}'>
    " . '<span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>' . "Embed Google Map</a>";

    return $context;
}

function srm_add_inline_popup_content()
{
    ?>

    <div id="wp_gmap_popup_container" style="display:none;">
        <!--modal contents-->

        <div id="wp-gmap-tabs">

            <!---------------------------new map tab-------------->
            <div class="wp-gmap-tab-content active" id="wp-gmap-all">



                                <span class="wpgmap_msg_error" style="width:80%;">

                                </span>
                <!--all map tab-->
                <div class="wp-gmap-list">

                    <a href="<?php echo esc_url(admin_url() . 'admin.php?page=wpgmapembed&amp;tag=new'); ?>"
                       data-id="wp-gmap-new" class="media-menu-item" style="float:right;">Create New
                        Map</a>
                    <span class="spinner is-active"
                          style="margin: 0px !important;float:left;"></span>

                    <div id="wpgmapembed_list"></div>

                </div>
            </div>
        </div>

    </div>
    <?php
}

?>
