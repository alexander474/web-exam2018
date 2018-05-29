/**
 * Wordpress Autosearch Suggest scripts 
 *
 * @package    	WP Autosearch
 * @license     #
 * @link	#
 * @version    	1.0
 */


    (function ($) {
        $(document).ready(function () {
			$("#Paidbutton").addClass("disabledbutton");
			$("#Paidbutton").attr("disabled", "disabled").off('click');
            $('.tabs a').click(function (e) {
                e.preventDefault();
                var tid = $(this).attr('tabid');
                var tabsContent = $(this).parent().parent().next();

                tabsContent.children().each(function () {

                    // Form nested tabs
                    if ($(this).is('form')) {
                        $(this).hide();
                        $form = $(this);
                        $(this).children().each(function () {
                           
                            if ($(this).is('[tabid]')) {
                                $(this).hide();
                                if ($(this).attr('tabid') == tid) {
                                    $form.fadeIn();
                                    $(this).fadeIn();
                                }
                            }
                        });
                        return;
                    }

                    
                    if ($(this).is('[tabid]')) {
                        $(this).hide();
                        if ($(this).attr('tabid') == tid) {
                            $(this).fadeIn();
                        }
                    }

                });

                $('a', $(this).parent().parent()).removeClass('current');
                $(this).addClass('current');
            });


            /*
             Onoff
             Msg: 
             */
            $('.wpsearchOnOff .wpsearchOnOffInner').on('click', function () {
                var hidden = $(this).prev();
                var val = $(hidden).val();
                if (val == 1) {
                    val = 0;
                    $(this).parent().removeClass("active");
                } else {
                    val = 1;
                    $(this).parent().addClass("active");
                }
                $(hidden).val(val);
                $(hidden).change();
            });
            $('.wpsearchOnOff .triggerer').on('click', function () {
                var hidden = $('input[type=hidden]', $(this).parent());
                var div = $(this).parent();
                var val = $(hidden).val();
                if (val == 0) {
                    div.removeClass("active");
                } else {
                    div.addClass("active");
                }
            });
            /*Onoff End*/


            
            /****************
             YesNo
             Msg: 
             ******************/
            $('.wpsearchYesNo .wpsearchYesNoInner').on('click', function () {
                var hidden = $(this).prev();
                var val = $(hidden).attr('hidd');
                var id = $(hidden).attr('id');
				
				
                if (val == 1) {
                    val = 0;
                    jQuery('#' + id).prop('checked', false);
					$(this).parent().removeClass("active");
                } else {
                    val = 1;
                    //jQuery('#' + id).prop('checked', true);
                 
					if(id=='search_exactonly'){
                            $(this).parent().removeClass("active");
							$(this).parent().addClass("deactive");
					}else if(id=='show_author'){
						$(this).parent().addClass("deactive");
						
					}else if(id=='show_date'){
						$(this).parent().addClass("deactive");
						
					}else if(id=='description_result'){
						$(this).parent().addClass("deactive");
						
					}else{

							$(this).parent().addClass("active");
					}
                                        
                  
                    
                }
                $(hidden).attr('hidd', val);
                $(hidden).change();
            });

            $('.wpsearchYesNo .triggerer').on('click', function () {
                
                var hidden = $('input[type=hidden]', $(this).parent());
                var div = $(this).parent();
                var val = $(hidden).val();
                if (val == 0) {
                       div.removeClass("active");
                } else {
                    div.addClass("active");
                }
                $(hidden).change();
            });
            /*YesNo End*/

        });
    })(jQuery)
