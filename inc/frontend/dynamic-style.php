<?php
    add_action('wp_head', 'custom_style');
    function custom_style(){
        $settings_style = get_option('pam_setting');
        $margin = $settings_style['margin'];
        $padding = $settings_style['padding'];
        $border = $settings_style['border'];
        $border_style = $settings_style['border_style'];
        ?>
            <style type="text/css">
                .pads-front-wrap{
                    margin-top: <?php echo !empty($margin['footer-margin-top'])? esc_html($margin['footer-margin-top']).'px':'0'?>;
                    margin-right: <?php echo !empty($margin['footer-margin-right'])? esc_html($margin['footer-margin-right']).'px':'0'?>;
                    margin-left: <?php echo !empty($margin['footer-margin-left'])? esc_html($margin['footer-margin-left']).'px':'0'?>;
                    margin-bottom: <?php echo !empty($margin['footer-margin-bottom'])? esc_html($margin['footer-margin-bottom']).'px':'0'?>;
                    
                    padding-top: <?php echo !empty($padding['footer-padding-top'])? esc_html($padding['footer-padding-top']).'px':'0'?>;
                    padding-right: <?php echo !empty($padding['footer-padding-right'])? esc_html($padding['footer-padding-right']).'px':'0'?>;
                    padding-left: <?php echo !empty($padding['footer-padding-left'])? esc_html($padding['footer-padding-left']).'px':'0'?>;
                    padding-bottom: <?php echo !empty($padding['footer-padding-bottom'])? esc_html($padding['footer-padding-bottom']).'px':'0'?>;
                    
                    border-top: <?php echo !empty($border['footer-border-top'])? esc_html($border['footer-border-top']).'px':'0'?>;
                    border-right: <?php echo !empty($border['footer-border-right'])? esc_html($border['footer-border-right']).'px':'0'?>;
                    border-left: <?php echo !empty($border['footer-border-left'])? esc_html($border['footer-border-left']).'px':'0'?>;
                    border-bottom: <?php echo !empty($border['footer-border-bottom'])? esc_html($border['footer-border-bottom']).'px':'0'?>;
                    border-color: <?php echo '#'.!empty($border_style['border_color'])? esc_html($border_style['border_color']):'000'?>;
                    border-style: <?php echo !empty($border_style['line_style'])? esc_html($border_style['line_style']):'solid'?>;
                }
            </style>
        <?php
    }     
    