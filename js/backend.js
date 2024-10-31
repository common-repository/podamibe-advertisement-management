(function ($) {
    $(function () {
        
        /**
    	 * Scripts for tab view
    	 * Preview the leaved tab if browser is not closed
    	 */
    	jQuery('ul.pamtabs li').click(function(){
    		var tab_id = jQuery(this).attr('data-tab');
    		sessionStorage.setItem("activeTab",tab_id);
    		jQuery('ul.pamtabs li').removeClass('active');
    		jQuery('.pam-tab-content').removeClass('active');
    
    		jQuery(this).addClass('active');
    		jQuery("#"+tab_id).addClass('active');
    	});
    
    	var active_tab = sessionStorage.getItem("activeTab");
    
    	if(!active_tab){
    		jQuery('ul.pamtabs li[data-tab="pamtabs-1"]').trigger('click');
    	}
    
    	if(active_tab){
    		jQuery('ul.pamtabs li[data-tab='+active_tab+']').trigger("click");
    	}
        
        $('body').on("click", "#menu-posts-pads-mgmt li:last-child", function(){
            if(sessionStorage.removeItem("activeTab")){
                $('ul.pamtabs li:first-child').addClass('active');
            }
        });
        
        $('body').on( "change", ".pads-adds-select", function() {
            var adds_data = $(this).val();
            $('#pads-bulk-shortcode').val('[pam_bulk pad_id="'+adds_data+'"]');
        });
        
        $('.jquery-color-picker').wpColorPicker();
        
        /*$('.jquery-datepicker').datetimepicker({
            format:'Y-m-d H:i',
            step:5
        });*/
        
        $('#pads-start-time').datetimepicker({
            format:'Y-m-d H:i',
            minDate: new Date,
            onShow: function(dateText, inst){
                this.setOptions({
                    maxDate:jQuery('#pads-end-time').val()?jQuery('#pads-end-time').val():false
                })
            },
            step:5
        });
        
        $('#pads-end-time').datetimepicker({
            format:'Y-m-d H:i',
            minDate: new Date,
            onShow: function(dateText, inst){
                this.setOptions({
                    minDate:jQuery('#pads-start-time').val()?jQuery('#pads-start-time').val():false
                })
            },
            step:5,
        });
        
        $('#pads-start-time-field').datetimepicker({
            format:'Y-m-d H:i',
            minDate: new Date,
            onShow: function(dateText, inst){
                this.setOptions({
                    maxDate:jQuery('#pads-end-time-field').val()?jQuery('#pads-end-time-field').val():false
                })
            },
            step:5
        });
        $('#pads-end-time-field').datetimepicker({
            format:'Y-m-d H:i',
            minDate: new Date,
            onShow: function(dateText, inst){
                this.setOptions({
                    minDate:jQuery('#pads-start-time-field').val()?jQuery('#pads-start-time-field').val():false
                })
            },
            step:5
        });
        
        /**
         * Duration Setting show hide
         */
        $('.pads-date-duration :input').attr("disabled", true);
        $('#pads-set-duration').change( function () {
            if ($(this).is(':checked')) {
                $('.pads-date-duration :input').attr("disabled", false);
                $('.pads-date-duration').slideDown();
            }else{
                $('.pads-date-duration').slideUp();
                $('.pads-date-duration :input').attr("disabled", true);
            }
        });
        if ( $('#pads-set-duration').is(':checked') ) {
            $('.pads-date-duration').slideDown();
            $('.pads-date-duration :input').attr("disabled", false);
        }else{
            $('.pads-date-duration').slideUp();
            $('.pads-date-duration :input').attr("disabled", true);
        }//End social network options
        
        $('body').on("change", ".pam_widget_control" , function(){
            if ($(this).is(':checked')) {
                $('.pam_widget_content').show();
            }else{
                $('.pam_widget_content').hide();
            }
        });
        if ($(".pam_widget_control").is(':checked')) {
            $('.pam_widget_content').show();
        }else{
            $('.pam_widget_content').hide();
        }
        
        $('body').on("change", ".pam_other_setting_control" , function(){
            if ($(this).is(':checked')) {
                $('.pam-other-section-wrap').show();
            }else{
                $('.pam-other-section-wrap').hide();
            }
        });
        if ($(".pam_other_setting_control").is(':checked')) {
            $('.pam-other-section-wrap').show();
        }else{
            $('.pam-other-section-wrap').hide();
        }
        
    });//document.ready close
}(jQuery));