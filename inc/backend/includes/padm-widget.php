<?php
$settings = get_option( PAM_SETTING_NAME );
if(!empty($settings) && isset($settings['enable_disable']) && $settings['enable_disable']==1){
    add_action( 'widgets_init', 'pam_bulk_widget_init');
}

/**
* Function to register the widget areas(sidebar) and widgets.
*/
function pam_bulk_widget_init() {

	register_widget("pam_bulk_widget");

}

class pam_bulk_widget extends WP_Widget {
    
    public function __construct() {
		$widget_ops = array( 
			'classname' => 'pads_bulk_widget',
			'description' => esc_html__( 'Widget to display ads on sidebar', PAM_TEXT_DOMAIN ),
		);
		parent::__construct( 'pam_bulk_widget', esc_html__('Advertisement Management', PAM_TEXT_DOMAIN), $widget_ops );
	}

	// Widget form
	function form( $instance ) {
        
		$instance = wp_parse_args( (array) $instance, array( 'bulk_shortcode_en_dis' => '') );
		$bulk_shortcode_en_dis  = esc_html( $instance[ 'bulk_shortcode_en_dis' ]);
        
        $instance = wp_parse_args( (array) $instance, array( 'selected_ads' => '') );
		$selected_ads  = ( $instance[ 'selected_ads' ]);
        
        $instance = wp_parse_args( (array) $instance, array( 'ads_title' => '') );
		$ads_title  = ( $instance[ 'ads_title' ]);
        
        $instance = wp_parse_args( (array) $instance, array( 'link_new_wind' => '') );
		$link_new_wind  = ( $instance[ 'link_new_wind' ]);
        
        $instance = wp_parse_args( (array) $instance, array( 'enable_widget_slider' => '') );
		$enable_widget_slider  = ( $instance[ 'enable_widget_slider' ]);
		?>
            <div class="pam-widget-wrap">
    			<p>
    				<label for="<?php echo $this->get_field_id('bulk_shortcode_en_dis'); ?>">
                        <input class="widefat pam_widget_control" id="<?php echo $this->get_field_id('bulk_shortcode_en_dis'); ?>" name="<?php echo $this->get_field_name('bulk_shortcode_en_dis'); ?>" type="checkbox" value="1" <?php if($bulk_shortcode_en_dis==1){echo 'checked="checked"';}?> />
    					<span><?php esc_html_e( 'Widget Enable/Disable', PAM_TEXT_DOMAIN ); ?></span>
    				</label>
    			</p>
                <div class="pam_widget_content">
                    <p>
                        <label><span><?php esc_html_e('Select Ads to Display', PAM_TEXT_DOMAIN);?></span> : </label>
                        <?php
                            $args = array(
                                            'post_type' => 'pads-mgmt',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                        );
        
                            $query_posts = new WP_Query( $args );
                        ?>
                        <select name="<?php echo $this->get_field_name('selected_ads[]'); ?>" multiple="multiple">
                            <option value=""><?php esc_html_e('Select', PAM_TEXT_DOMAIN);?></option>
                            <?php
                                if($query_posts->have_posts()):
                                    while ( $query_posts->have_posts() ) : $query_posts->the_post();
                                        $start_time = get_post_meta(get_the_ID(),'pads_start_time_field', true);
                                        $end_time = get_post_meta(get_the_ID(),'pads_end_time_field', true);
                                        $current_date = date('Y-m-d H:i');
                                        $current_date = strtotime($current_date);
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
                                            ?>
                                            	<option value="<?php echo get_the_ID();?>" <?php if(!empty($selected_ads) && in_array(get_the_ID(), $selected_ads)){ echo 'selected="selected"';}?> >
                                            		<?php the_title(); ?>
                                            	</option>
                                            <?php  
                                        }
                                    endwhile; // end of the loop.
                                endif;
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="<?php echo $this->get_field_id('ads_title'); ?>">
                            <input class="widefat" id="<?php echo $this->get_field_id('ads_title'); ?>" name="<?php echo $this->get_field_name('ads_title'); ?>" type="checkbox" value="yes" <?php if($ads_title=='yes'){echo 'checked="checked"';}?> />
        					<span><?php esc_html_e( 'Show Ads Title', PAM_TEXT_DOMAIN ); ?></span>
        				</label>
                        <label for="<?php echo $this->get_field_id('link_new_wind'); ?>">
                            <input class="widefat" id="<?php echo $this->get_field_id('link_new_wind'); ?>" name="<?php echo $this->get_field_name('link_new_wind'); ?>" type="checkbox" value="1" <?php if($link_new_wind=='1'){echo 'checked="checked"';}?> />
        					<span><?php esc_html_e( 'Ads Link Open in new window', PAM_TEXT_DOMAIN ); ?></span>
        				</label><br /><br />
                        <label for="<?php echo $this->get_field_id('enable_widget_slider'); ?>">
                            <input class="widefat" id="<?php echo $this->get_field_id('enable_widget_slider'); ?>" name="<?php echo $this->get_field_name('enable_widget_slider'); ?>" type="checkbox" value="1" <?php if($enable_widget_slider=='1'){echo 'checked="checked"';}?> />
        					<span><?php esc_html_e( 'Enable Slider', PAM_TEXT_DOMAIN ); ?></span>
        				</label>
                    </p>
                </div>
            </div>
		<?php 
    }

	// Widget update
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
        
		$instance[ 'bulk_shortcode_en_dis' ]  = sanitize_text_field( $new_instance[ 'bulk_shortcode_en_dis' ] );
        
        foreach ($new_instance[ 'selected_ads' ] as $selected_val){
            $selected_ads[] = sanitize_text_field($selected_val);
        }
        $instance[ 'selected_ads' ]  = $selected_ads;
        
        $instance[ 'ads_title' ]  = sanitize_text_field( $new_instance[ 'ads_title' ] );
        
        $instance[ 'link_new_wind' ]  = sanitize_text_field( $new_instance[ 'link_new_wind' ] );
        
        $instance[ 'enable_widget_slider' ]  = sanitize_text_field( $new_instance[ 'enable_widget_slider' ] );
        
		return $instance;
	}

	//Widget display
    function widget( $args, $instance ) {
        extract($args);
        
        $bulk_shortcode_en_dis = isset( $instance[ 'bulk_shortcode_en_dis' ] ) ? $instance[ 'bulk_shortcode_en_dis' ] : '';
        
        $selected_ads = isset( $instance[ 'selected_ads' ] ) ? $instance[ 'selected_ads' ] : array();
        
        $ads_title = isset( $instance[ 'ads_title' ] ) ? $instance[ 'ads_title' ] : '';
        
        $link_new_wind = isset( $instance[ 'link_new_wind' ] ) ? $instance[ 'link_new_wind' ] : '';
        
        $enable_widget_slider = isset( $instance[ 'enable_widget_slider' ] ) ? $instance[ 'enable_widget_slider' ] : '';
        
        $target = '';
        if(isset($link_new_wind) && $link_new_wind==1){
            $target = 'target="_blank"';
        }
        
		echo $before_widget;

		if( $bulk_shortcode_en_dis==1 ) {
            $html = '';
            $slider_class = '';
            $slider_caption = 'pads-title';
            if( !empty($enable_widget_slider) && $enable_widget_slider==1 ){
                $slider_class = ' pads-bulk-slider';
                $slider_caption = 'pads-caption';
            }
            $html .= '<div class="pads-bulk-widget-ads-wrap">';
            $html .= '<div class="pads-bulk-widget-ads-inner-wrap'.$slider_class.'">';
            foreach($selected_ads as $page_id){
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
                                    
                                    if($ads_title=='yes'){
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
                    $html .= '<p class="pads-error"><span>'. $page_id . '</span>' .esc_html__(' is invalid argument.', PAM_TEXT_DOMAIN).'</p>';
                }
            }
            $html .= '</div>';
            $html .= '</div>';
            echo $html;
        }
		echo $after_widget;
	}

}