<?php
if (!defined('ABSPATH')) exit;
// ************* Google Map SRM Shortcode***************
if (!function_exists('srm_gmap_embed_shortcode')) {

    function srm_gmap_embed_shortcode($atts, $content)
    {
        static $count;
        if (!$count) {
            $count = 0;
        }
        $count++;

        $wpgmap_title = esc_html(get_post_meta($atts['id'], 'wpgmap_title', true));
        $wpgmap_show_heading = esc_html(get_post_meta($atts['id'], 'wpgmap_show_heading', true));
        $wpgmap_heading_class = esc_html(get_post_meta($atts['id'], 'wpgmap_heading_class', true));
        $wpgmap_latlng = esc_html(get_post_meta($atts['id'], 'wpgmap_latlng', true));
        $wpgmap_disable_zoom_scroll = esc_html(get_post_meta($atts['id'], 'wpgmap_disable_zoom_scroll', true));
        $wpgmap_map_zoom = esc_html(get_post_meta($atts['id'], 'wpgmap_map_zoom', true));
        $wpgmap_map_width = esc_html(get_post_meta($atts['id'], 'wpgmap_map_width', true));
        $wpgmap_map_height = esc_html(get_post_meta($atts['id'], 'wpgmap_map_height', true));
        $wpgmap_map_type = esc_html(get_post_meta($atts['id'], 'wpgmap_map_type', true));
        $wpgmap_map_address = esc_html(get_post_meta($atts['id'], 'wpgmap_map_address', true));
        $wpgmap_show_infowindow = get_post_meta($atts['id'], 'wpgmap_show_infowindow', true);
        $wpgmap_enable_direction = get_post_meta($atts['id'], 'wpgmap_enable_direction', true);

        ob_start();

        if (isset($wpgmap_show_heading) && $wpgmap_show_heading == 1) {
            echo "<h1 class='$wpgmap_heading_class'>" . $wpgmap_title . "</h1>";
        }
        ?>
        <script type="text/javascript">
            google.maps.event.addDomListener(window, 'load', function () {
                var map = new google.maps.Map(document.getElementById("srm_gmp_embed_<?php echo $count; ?>"), {
                    center: new google.maps.LatLng(<?php echo $wpgmap_latlng;?>),
                    zoom:<?php echo $wpgmap_map_zoom;?>,
                    mapTypeId: google.maps.MapTypeId.<?php echo $wpgmap_map_type;?>,
                    scrollwheel: '<?php echo $wpgmap_disable_zoom_scroll == 1 ? false : true;?>'
                });

                // To view directions form and data
                <?php if($wpgmap_enable_direction){ ?>
                var directionsDisplay = new google.maps.DirectionsRenderer();

                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById("wp_gmap_directions_<?php echo $count; ?>"));

                var btn = document.getElementById('wp_gmap_submit_<?php echo $count; ?>');
                btn.addEventListener('click', function () {
                    var selectedMode = document.getElementById("mode").value,
                        start = document.getElementById("from_<?php echo $count; ?>").value,
                        end = document.getElementById("to_<?php echo $count; ?>").value;
                    if (start == '' || end == '') {
                        // cannot calculate route
                        document.getElementById("wp_gmap_results_<?php echo $count; ?>").style.display = 'none';
                        return false;
                    }
                    else {


                        document.getElementById('wp_gmap_loading_<?php echo $count; ?>').style.display = 'block';

                        var request = {
                            origin: start,
                            destination: end,
                            travelMode: google.maps.DirectionsTravelMode[selectedMode]
                        };
                        var directionsService = new google.maps.DirectionsService();
                        directionsService.route(request, function (response, status) {
                            document.getElementById('wp_gmap_loading_<?php echo $count; ?>').style.display = 'none';
                            if (status == google.maps.DirectionsStatus.OK) {
                                directionsDisplay.setDirections(response);
                                document.getElementById("wp_gmap_results_<?php echo $count; ?>").style.display = 'block';
                            }
                            else {
                                document.getElementById("wp_gmap_results_<?php echo $count; ?>").style.display = 'none';
                            }
                        });

                    }
                });
                <?php }?>
                //=========Direction view end

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(<?php echo $wpgmap_latlng;?>),
                    map: map,
                    animation: google.maps.Animation.DROP
                });
                marker.setMap(map);
                <?php
                if($wpgmap_show_infowindow){
                ?>
                var infowindow = new google.maps.InfoWindow({
                    content: "<?php echo $wpgmap_map_address;?>"
                });
                infowindow.open(map, marker);
                google.maps.event.addListener(marker, 'click', function () {
                    infowindow.open(map, marker);
                });
                <?php
                }
                ?>

            });


        </script>


        <div id="srm_gmp_embed_<?php echo $count; ?>"
             style="width:<?php echo $wpgmap_map_width . ' !important'; ?>;height:<?php echo $wpgmap_map_height; ?>  !important;margin:5px 0; ">

        </div>
        <?php
        if ($wpgmap_enable_direction == 1) { ?>
            <style type="text/css">
                .wp_gmap_direction_box {
                    width: 100%;
                    height: auto;
                    float: left;
                }

                .wp_gmap_direction_box .fieldcontain {
                    margin: 8px 0;
                }

                #wp_gmap_submit {
                    background-color: #333;
                    border: 0;
                    color: #fff;
                    cursor: pointer;
                    font-family: "Noto Sans", sans-serif;
                    font-size: 12px;
                    font-weight: 700;
                    padding: 13px 24px;
                    text-transform: uppercase;
                }

                #wp_gmap_directions {
                    border: 1px #ddd solid;
                }
            </style>
            <div class="wp_gmap_direction_box">
                <div class="ui-bar-c ui-corner-all ui-shadow" style="padding:1em;">
                    <div data-role="fieldcontain" class="fieldcontain">
                        <label for="from"><?php _e('From','gmap-embed') ?></label>
                        <input type="text" id="from_<?php echo $count; ?>" value="<?php echo $wpgmap_map_address; ?>"/>
                    </div>
                    <div data-role="fieldcontain" class="fieldcontain">
                        <label for="to"><?php _e('To','gmap-embed') ?></label>
                        <input type="text" id="to_<?php echo $count; ?>" value="<?php echo $wpgmap_map_address; ?>"/>
                    </div>
                    <div data-role="fieldcontain" class="fieldcontain">
                        <label for="mode" class="select"><?php _e('Transportation method','gmap-embed') ?>:</label>
                        <select name="select-choice-0" id="mode" style="padding: 5px;">
                            <option value="DRIVING"><?php _e('Driving','gmap-embed') ?></option>
                            <option value="WALKING"><?php _e('Walking','gmap-embed') ?></option>
                            <option value="BICYCLING"><?php _e('Bicycling','gmap-embed') ?></option>
                        </select>
                    </div>
                    <button type="button" data-icon="search" data-role="button" href="#"
                            id="wp_gmap_submit_<?php echo $count; ?>"><?php _e('Get Directions','gmap-embed') ?>
                    </button>
                    <span id="wp_gmap_loading_<?php echo $count; ?>" style="display: none;"><?php _e('Loading','gmap-embed') ?>...</span>
                </div>

                <!-- Directions will be listed here-->
                <div id="wp_gmap_results_<?php echo $count; ?>"
                     style="display:none;max-height: 300px;overflow-y: scroll;">
                    <div id="wp_gmap_directions_<?php echo $count; ?>"></div>
                </div>

            </div>
        <?php } ?>
        <?php
        return ob_get_clean();
    }

}

//******* Defining Shortcode for Google Map SRM
add_shortcode('gmap-embed', 'srm_gmap_embed_shortcode');