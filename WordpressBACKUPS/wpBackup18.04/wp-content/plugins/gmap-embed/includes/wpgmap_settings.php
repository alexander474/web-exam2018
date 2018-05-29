<?php
if (!defined('ABSPATH')) exit;
?>
<div data-columns="8">
    <!--    to save api key-->
    <div class="wpgmapembed_get_api_key">
        <div class="error">
            <p style="font-size:17px;"><strong><?php _e('Notice: The plugin requires following API key.','gmap-embed');?></strong></p>
            <form method="post" action="<?php echo admin_url(); ?>admin.php?page=wpgmapembed&message=3">
                <p><?php _e('Enter API Key','gmap-embed');?> <input type="text" name="wpgmapembed_key"
                                        value="<?php echo esc_html(get_option('wpgmap_api_key')); ?>" size="45">
                    <button class="wd-btn wd-btn-primary button media-button button-primary"><?php _e('Save','gmap-embed');?></button>

                    <a target="_blank"
                       href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,places_backend,geolocation,geocoding_backend,directions_backend&amp;keyType=CLIENT_SIDE&amp;reusekey=true"
                       class="button media-button button-default button-large"><?php _e('GET FREE API KEY','gmap-embed');?></a>
					   <br/><?php _e('The API key may take up to 5 minutes to take effect','gmap-embed');?>
                </p>
            </form>

        </div>
    </div>
</div>