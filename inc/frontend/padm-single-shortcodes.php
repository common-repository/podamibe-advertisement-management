<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    die('No script kiddies please!');
}

if ( !class_exists( 'PAM_Single_Shortcode' ) ) {
    class PAM_Single_Shortcode {
        
        public function pads_shortcode_callback($atts, $content = ""){
            
            $settings = get_option( PAM_SETTING_NAME );
            
            if( ( isset($settings['enable_disable']) && $settings['enable_disable'] == 1 ) ){
                $target = '';
                if(isset($settings['pads_open_tab']) && $settings['pads_open_tab']==1){
                    $target = 'target="_blank"';
                }
                $atts = shortcode_atts( array(
            		'post_id' => 'no post id',
                    'title' => 'no',
            		'baz' => 'default baz'
            	), $atts, 'pam' );
                //get_post_type( $post->ID );
                $post_id = $atts['post_id'];
                if(is_numeric($post_id)){
                    $post_id = $post_id;
                }elseif($content){
                    if(is_numeric($content)){
                        $post_id = $content;
                    }else{
                        $error_msg = '<p class="pads-error"><span>'. $content . '</span>' .esc_html__(' is invalid argument.', PAM_TEXT_DOMAIN).'</p>';
                        $post_id = '';
                    }
                }else{
                    $error_msg = '<p class="pads-error"><span>'. $post_id . '</span>' .esc_html__(' is invalid argument.', PAM_TEXT_DOMAIN).'</p>';
                    $post_id = '';
                }
                if( is_numeric($post_id) ){
                    $pads_args = array(
                                    'post_type' => 'pads-mgmt',
                                    'post_status' => 'publish',
                                    'page_id' => $post_id
                                 );
                    $pads_query = new WP_Query($pads_args);
                    $html = '';
                    if($pads_query->have_posts()){
                        while($pads_query->have_posts()):$pads_query->the_post();
                            
                            $image_thumbnail_id = get_post_thumbnail_id();
                            $image_thumbnail_url = wp_get_attachment_image_src( $image_thumbnail_id, 'full', true );
                            $pads_img_link = get_post_meta(get_the_ID(),'pads_link_field',true);
                            $pads_content = get_the_content();
                            $start_time = get_post_meta(get_the_ID(),'pads_start_time_field', true);
                            $end_time = get_post_meta(get_the_ID(),'pads_end_time_field', true);
                            $current_date = date('Y-m-d H:i');
                            $current_time = strtotime($current_date);
                            $access_flag = 0;
                            if( ($start_time != '' && $end_time != '' ) ){
                                $start_time = strtotime($start_time);
                                $end_time = strtotime($end_time);
                                if($current_time >= $start_time && $current_time <= $end_time){
                                    $access_flag = 1;
                                }
                            }
                            if( ($start_time != '' && $end_time == '' ) ){
                                $start_time = strtotime($start_time);
                                $end_time = strtotime($end_time);
                                if( $current_time >= $start_time ){
                                    $access_flag = 1;
                                }                        
                            }
                            if( ($start_time == '' && $end_time == '' ) ){
                                $access_flag = 1;                      
                            }
                            if( $access_flag == 1 ){
                                $html .= '<div class="pads-front-wrap">';
                                    
                                    if($atts['title']=='yes'){
                                        $html .= '<div class="pads-title">'.get_the_title().'</div>';
                                    }
                                    if($pads_content){
                                        $html .= '<div class="pads-google-adds">'. $pads_content .'</div>';
                                    }
                                    if(has_post_thumbnail()){
                                        $html .= '<div class="pads-image-adds">';
                                            $html .= '<a href="'. $pads_img_link .'" '. $target .'>';
                                                $html .= '<img src = "'. $image_thumbnail_url[0] .'" alt="'.get_the_title().'">';
                                            $html .= '</a>';
                                        $html .= '</div>';
                                    }
                                    
                                $html .= '</div>';
                                
                                return $html;
                            }
                            
                        endwhile;
                    }else{
                        return '<p class="pads-error">' . esc_html__('No result found.', PAM_TEXT_DOMAIN) . '</p>';
                    }
                    wp_reset_query();
                }else{
                    if($post_id==''){
                        return $error_msg;
                    }else{
                        return '<p class="pads-error">' . esc_html__($atts['post_id'], PAM_TEXT_DOMAIN) . '</p>';
                    }
                }
            }
        }
        
    }
}