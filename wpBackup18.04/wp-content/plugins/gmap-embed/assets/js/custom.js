(function ($) {
    $(function () {

        //******* On Open Button Click *******
        //$('body #srm_gmap_embed,body #srm_gmap_embed1').on('click', openSrmGmapPopup);

        //******* On Close Button Click*******
        $(document.body).on('click', '.wp_gmap_close_btn', removeSrmGmapPopup);

        //******* On Escape Button Click *******
        $(document).keyup(function (e) {
            if (e.which == 27) {
                removeSrmGmapPopup();
            }
        });

        //******* To Open Gmap Popup *******
        function openSrmGmapPopup() {
            $("body #cboxOverlay,#wp_gmap_popup_container").css('display', 'block');
            readyGmapPopupWindow();
        }

        //******* Removing Popup Box *******
        function removeSrmGmapPopup() {
            self.parent.tb_remove();
        }

        //******* To load Maps List *******
        function loadSrmGmapsList() {
            $("#wp-gmap-all").find(".spinner").addClass('is-active');
            $("#wpgmapembed_list").html('');
            var data = {
                'action': 'wpgmapembed_popup_load_map_data',
                'data': ''
            };
            jQuery.post(ajaxurl, data, function (response) {
                $("#wp-gmap-all").find(".spinner").removeClass('is-active');
                $("#wpgmapembed_list").html(response);
            });
        }

        //******* Initiate Google Map/List *******
        function readyGmapPopupWindow() {
            loadSrmGmapsList();
        }
        window.onload = readyGmapPopupWindow;

        //******* To insert ShortCode From List *******
        $(document.body).on('click', ".wpgmap-insert-shortcode", function () {

            var shortcode = $(this).parent().parent().find('.wpgmap-shortcode').val();
            if (!tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
                $('textarea#content').val(shortcode);
            } else {
                tinyMCE.execCommand('mceInsertContent', false, shortcode);
            }
            removeSrmGmapPopup();
        });

        //******* To Edit/Update Map Data *******
        $(document.body).on('click', ".wpgmap-insert-delete", function () {
            if (!confirm("Are you sure to Delete")) {
                return false;
            }
            $("#wp-gmap-nav").find('.spinner').addClass('is-active');
            var btn_class = $(this);
            btn_class.prop('disabled', true);
            var post_id = $(this).data('id');
            var data = {
                'action': 'wpgmapembed_remove_wpgmap',
                'post_id': post_id
            };

            jQuery.post(ajaxurl, data, function (response) {
                response = JSON.parse(response);
                btn_class.prop('disabled', false);
                window.location.reload();
            });
        });

        //************** On Save, Update and Insert Button on Click
        $(document.body).on('click', "#wp-gmap-embed-save,#wp-gmap-embed-save-and-insert,#wp-gmap-embed-update", function () {

            $(this).prop('disabled', true);

            var btn_id = $(this).attr('id');

            if (btn_id == 'wp-gmap-embed-save') {
                var parent = $("body #wp-gmap-new");
            } else if (btn_id == 'wp-gmap-embed-update') {
                var parent = $("body #wp-gmap-edit");
            }


            $(this).parent().find(".spinner").addClass('is-active');

            // getting checkbox value
            if (parent.find("#wpgmap_show_heading").is(':checked') === true) {
                var wpgmap_show_heading = 1;
            } else {
                var wpgmap_show_heading = 0;
            }

            if (parent.find("#wpgmap_show_infowindow").is(':checked') === true) {
                var wpgmap_show_infowindow = 1;
            } else {
                var wpgmap_show_infowindow = 0;
            }
            if (parent.find("#wpgmap_disable_zoom_scroll").is(':checked') === true) {
                var wpgmap_disable_zoom_scroll = 1;
            } else {
                var wpgmap_disable_zoom_scroll = 0;
            }
            if (parent.find("#wpgmap_enable_direction").is(':checked') === true) {
                var wpgmap_enable_direction = 1;
            } else {
                var wpgmap_enable_direction = 0;
            }

            var wpgmap_title = parent.find("#wpgmap_title").val();
            var wpgmap_heading_class = parent.find("#wpgmap_heading_class").val();
            var wpgmap_latlng = parent.find("#wpgmap_latlng").val();
            var wpgmap_map_zoom = parent.find("#wpgmap_map_zoom").val();
            var wpgmap_map_width = parent.find("#wpgmap_map_width").val();
            var wpgmap_map_height = parent.find("#wpgmap_map_height").val();
            var wpgmap_map_type = parent.find("#wpgmap_map_type").val();
            var wpgmap_map_address = parent.find("#wpgmap_map_address").val();

            var map_data = {
                wpgmap_title: wpgmap_title,
                wpgmap_heading_class: wpgmap_heading_class,
                wpgmap_show_heading: wpgmap_show_heading,
                wpgmap_latlng: wpgmap_latlng,
                wpgmap_map_zoom: wpgmap_map_zoom,
                wpgmap_disable_zoom_scroll: wpgmap_disable_zoom_scroll,
                wpgmap_map_width: wpgmap_map_width,
                wpgmap_map_height: wpgmap_map_height,
                wpgmap_map_type: wpgmap_map_type,
                wpgmap_map_address: wpgmap_map_address,
                wpgmap_show_infowindow: wpgmap_show_infowindow,
                wpgmap_enable_direction: wpgmap_enable_direction
            };

            if (btn_id == 'wp-gmap-embed-save') {
                map_data.action_type = 'save';
            } else if (btn_id == 'wp-gmap-embed-update') {
                map_data.action_type = 'update';
                map_data.post_id = parent.find("#wpgmap_map_id").val();
            }

            var data = {
                'action': 'wpgmapembed_save_map_data',
                'map_data': map_data
            };


            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function (response) {
                $("#" + btn_id).parent().find(".spinner").removeClass('is-active');
                response = JSON.parse(response);
                if (response.responseCode == 0) {
                    $("#" + btn_id).prop('disabled', false);
                    parent.find('.wpgmap_msg_error').html('<div class="error bellow-h2 notice notice-error is-dismissible"><p>' + response.message + '</p></div>');
                } else {

                    if (btn_id == 'wp-gmap-embed-save' || btn_id == 'wp-gmap-embed-update') {

                        $("#" + btn_id).prop('disabled', false);
                        var redirectTo = (btn_id == 'wp-gmap-embed-save')?1:2;
                        window.location.href = '?page=wpgmapembed&message='+redirectTo;
                        $('body #wp-gmap-all .wpgmap_msg_error').html('<div class="success bellow-h2 notice notice-success is-dismissible"><p>' + response.message + '</p></div>');
                    }
                    $("#wpgmap_title,#wpgmap_latlng, #wpgmap_map_address").val("");
                    $("#wpgmap_show_heading,#wpgmap_show_infowindow").prop("checked", false);

                }
            });
        });

    });
})(jQuery);