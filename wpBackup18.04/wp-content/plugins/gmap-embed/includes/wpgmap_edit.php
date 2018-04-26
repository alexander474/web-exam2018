<?php
if (!defined('ABSPATH')) exit;
$gmap_data = $this->get_wpgmapembed_data(intval($_GET['id']));
$wpgmap_single = json_decode($gmap_data);
list($wpgmap_lat, $wpgmap_lng) = explode(',', esc_html($wpgmap_single->wpgmap_latlng));
?>
<div data-columns="8">
    <!--    getting hidden id-->
    <input id="wpgmap_map_id" name="wpgmap_map_id" value="<?php echo intval($_GET['id']); ?>" type="hidden"/>

    <span class="wpgmap_msg_error" style="width:80%;">
<!--        error will goes here-->
    </span>

    <div class="wp-gmap-properties">
        <table style="width: 100% !important;" class="gmap_properties">
            <tr>
                <td>
                    <label for="wpgmap_title"><b><?php _e('Map Title','gmap-embed');?></b></label><br/>
                    <input id="wpgmap_title" name="wpgmap_title" value="<?php echo esc_attr($wpgmap_single->wpgmap_title); ?>"
                           type="text"
                           class="regular-text">
                    <br/>

                    <input type="checkbox" value="1" name="wpgmap_show_heading"
                           id="wpgmap_show_heading" <?php echo ($wpgmap_single->wpgmap_show_heading == 1) ? 'checked' : ''; ?>>
                    <label for="wpgmap_show_heading"><?php _e('Show as map title','gmap-embed');?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wpgmap_heading_class"><b><?php _e('Heading Custom Class','gmap-embed');?></b></label><br/>
                    <input id="wpgmap_heading_class" name="wpgmap_heading_class" value="<?php echo $wpgmap_single->wpgmap_heading_class;?>" type="text"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="wpgmap_latlng"><b><?php _e('Latitude, Longitude','gmap-embed');?></b></label><br/>
                    <input id="wpgmap_latlng" name="wpgmap_latlng" value="<?php echo esc_attr($wpgmap_single->wpgmap_latlng); ?>"
                           type="text"
                           class="regular-text">
                </td>
            </tr>

            <tr>
                <td>
                    <label for="wpgmap_map_zoom"><b><?php _e('Zoom','gmap-embed');?></b></label><br/>
                    <input id="wpgmap_map_zoom" name="wpgmap_map_zoom"
                           value="<?php echo esc_attr($wpgmap_single->wpgmap_map_zoom); ?>" type="text"
                           class="regular-text">

                    <label for="wpgmap_disable_zoom_scroll"><input type="checkbox" value="1"
                                                               name="wpgmap_disable_zoom_scroll"
                                                               id="wpgmap_disable_zoom_scroll" <?php echo ($wpgmap_single->wpgmap_disable_zoom_scroll == 1) ? 'checked' : ''; ?>>
                        <?php _e('Disable zoom on mouse scroll','gmap-embed');?>
                    </label>

                </td>
            </tr>

            <tr>
                <td>
                    <label for="wpgmap_map_width"><b><?php _e('Width (%)','gmap-embed');?></b></label><br/>
                    <input id="wpgmap_map_width" name="wpgmap_map_width"
                           value="<?php echo esc_attr($wpgmap_single->wpgmap_map_width); ?>"
                           type="text" class="regular-text">
                </td>
            </tr>

            <tr>
                <td>
                    <label for="wpgmap_map_height"><b><?php _e('Height (px)','gmap-embed');?></b></label><br/>
                    <input id="wpgmap_map_height" name="wpgmap_map_height"
                           value="<?php echo esc_attr($wpgmap_single->wpgmap_map_height); ?>"
                           type="text" class="regular-text">
                </td>
            </tr>

            <tr>
                <td>
                    <label><b><?php _e('Map Type','gmap-embed');?></b></label><br/>
                    <select id="wpgmap_map_type" class="regular-text" style="width:25em;">
                        <option <?php echo $wpgmap_single->wpgmap_map_type == 'ROADMAP' ? 'selected' : ''; ?>>ROADMAP
                        </option>
                        <option <?php echo $wpgmap_single->wpgmap_map_type == 'SATELLITE' ? 'selected' : ''; ?>>
                            SATELLITE
                        </option>
                        <option <?php echo $wpgmap_single->wpgmap_map_type == 'HYBRID' ? 'selected' : ''; ?>>HYBRID
                        </option>
                        <option <?php echo $wpgmap_single->wpgmap_map_type == 'TERRAIN' ? 'selected' : ''; ?>>TERRAIN
                        </option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    <label for="wpgmap_map_address"><b><?php _e('Location Address','gmap-embed');?></b></label><br/>
                    <textarea id="wpgmap_map_address" style="width:25em;" name="wpgmap_map_address" class="regular-text"
                              rows="3"><?php echo esc_attr(trim($wpgmap_single->wpgmap_map_address)); ?></textarea>

                    <br/>

                    <label for="wpgmap_show_infowindow"><input type="checkbox" value="1"
                                                               name="wpgmap_show_infowindow"
                                                               id="wpgmap_show_infowindow" <?php echo ($wpgmap_single->wpgmap_show_infowindow == 1) ? 'checked' : ''; ?>>
                    <?php _e('Show in marker infowindow','gmap-embed');?>
                    </label>

                    <br/>

                    <label for="wpgmap_enable_direction"><input type="checkbox" value="1"
                                                               name="wpgmap_enable_direction"
                                                               id="wpgmap_enable_direction" <?php echo ($wpgmap_single->wpgmap_enable_direction == 1) ? 'checked' : ''; ?>>
                        <?php _e('Enable Direction in Map','gmap-embed');?>
                    </label>
                </td>
            </tr>

        </table>
    </div>

    <div class="wp-gmap-preview">
        <input id="pac-input" class="controls" type="text" placeholder="<?php _e('Search by Address, Zip Code','gmap-embed');?>"/>
        <div id="map" style="height: 415px;"></div>
    </div>
    <script>
        (function ($) {
            $(function () {
                google.maps.event.addDomListener(window, 'load',
                    initAutocomplete('map', 'pac-input',<?php echo $wpgmap_lat;?>,<?php echo $wpgmap_lng;?>, 'roadmap',<?php echo $wpgmap_single->wpgmap_map_zoom;?>)
                );
            });
        })(jQuery);
    </script>

</div>

<div class="media-frame-toolbar">
    <div class="media-toolbar">
        <div class="media-toolbar-secondary"
             style="text-align: right;float: right;margin-top:10px;">
            <span class="spinner" style="margin: 0px !important;float:left;"></span>
            <button class="button button-primary" id="wp-gmap-embed-update"><?php _e('Update','gmap-embed');?></button>
        </div>
    </div>
</div>