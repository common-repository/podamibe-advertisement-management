<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    die('No script kiddies please!');
}
if( !class_exists( 'PAM_Bulk_Shortcode' ) ){
    class PAM_Bulk_Shortcode {
        
        public function pads_bulk_shortcode_callback($atts, $content = ""){
            
            $settings = get_option( PAM_SETTING_NAME );
            
            $current_date = date('Y-m-d H:i');
            $current_date = strtotime($current_date);
            $access_flag = 0;
            if( isset( $settings['bulk_start_time'] ) && isset( $settings['bulk_end_time'] ) ){
                $start_date = $settings['bulk_start_time'];
                $start_date = strtotime($start_date);
                $end_date = $settings['bulk_end_time'];
                $end_date = strtotime($end_date);
                if($current_date >= $start_date && $current_date <= $end_date){
                    $access_flag = 1;
                }
            }else if(isset( $settings['bulk_start_time'] ) && !isset($settings['bulk_end_time'])){
                $start_date = $settings['bulk_start_time'];
                $start_date = strtotime($start_date);
                if($current_date >= $start_date){
                    $access_flag = 1;
                }
            }else if(!isset( $settings['bulk_start_time'] ) && !isset($settings['bulk_end_time'])){
                $access_flag = 1;
            }
            
            if( ( isset($settings['enable_disable']) && $settings['enable_disable'] == 1 ) && ( isset($settings['bulk_shortcode_en_dis']) && $settings['bulk_shortcode_en_dis'] == 1 ) && $settings['bulk_shortcode'] !='' && $access_flag==1){
                $target = '';
                if(isset($settings['pads_open_tab']) && $settings['pads_open_tab']==1){
                    $target = 'target="_blank"';
                }
                $atts = shortcode_atts( array(
            		'pad_id' => 'no post id',
                    'title' => 'no',
            		'baz' => 'default baz'
            	), $atts, 'pam' );
                //get_post_type( $post->ID );
                $bulk_id = $atts['pad_id'];
                $page_id_array = explode(',', $bulk_id);
                $html = '';
                
                if( $bulk_id != 'no post id' ){
                    $page_id_array = $page_id_array;
                }elseif($content){
                    $page_id_array = explode(',', $content);
                }else{
                    $page_id_array = array();
                }
                $slider_class = '';
                $slider_caption = 'pads-title';
                if( isset($settings['enable_slider']) && $settings['enable_slider']==1 ){
                    $slider_class = ' pads-bulk-slider';
                    $slider_caption = 'pads-caption';
                }
                $html .= '<div class="pads-bulk-ads-wrap">';
                $html .= '<div class="pads-bulk-ads-inner-wrap'.$slider_class.'">';
                foreach($page_id_array as $page_id){
                    if(is_numeric($page_id)){
                        $pads_args = array(
                                        'post_type' => 'pads-mgmt',
                                        'post_status' => 'publish',
                                        'page_id' => $page_id
                                     );
                        $pads_query = new WP_Query($pads_args);
                        if($pads_query->have_posts()){
                            while($pads_query->have_posts()):$pads_query->the_post();
                                
                                $image_thumbnail_id = get_post_thumbnail_id();
                                $image_thumbnail_url = wp_get_attachment_image_src( $image_thumbnail_id, 'full', true );
                                $pads_img_link = get_post_meta(get_the_ID(),'pads_link_field',true);
                                $pads_content = get_the_content();
                                $start_time = get_post_meta(get_the_ID(),'pads_start_time_field', true);
                                $end_time = get_post_meta(get_the_ID(),'pads_end_time_field', true);
                                $access_flags = 0;
                                if( ($start_time != '' && $end_time != '' ) ){
                                    $start_time = strtotime($start_time);
                                    $end_time = strtotime($end_time);
                                    if($current_date >= $start_time && $current_date <= $end_time){
                                        $access_flags = 1;
                                    }
                                }
                                if( ($start_time != '' && $end_time == '' ) ){
                                    $start_time = strtotime($start_time);
                                    $end_time = strtotime($end_time);
                                    if( $current_date >= $start_time ){
                                        $access_flags = 1;
                                    }                        
                                }
                                if( ($start_time == '' && $end_time == '' ) ){
                                    $access_flags = 1;                      
                                }
                                if( $access_flags == 1 ){
                                    $html .= '<div class="pads-front-wrap">';
                                        
                                        if($atts['title']=='yes'){
                                            $html .= '<div class="'.$slider_caption.'">'.get_the_title().'</div>';
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
                                }
                            endwhile;
                        }/*else{
                            $html .= 'No result found.';
                        }*/
                    }else{
                        if($page_id != 'no post id'){
                            $html .= '<p class="pads-error"><span>'. $page_id . '</span>' .esc_html__(' is invalid argument.', PAM_TEXT_DOMAIN).'</p>';
                        }else{
                            $html .= '<p class="pads-error">'.esc_html__($page_id, PAM_TEXT_DOMAIN).'</p>';
                        }
                    }
                }
                $html .= '</div>';
                $html .= '</div>';
                
                return $html;
            }
        }
        
        
    }
}