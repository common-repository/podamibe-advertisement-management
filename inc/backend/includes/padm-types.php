<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    die('No script kiddies please!');
}

add_action( 'init', 'register_pam_post_types');
add_action( 'init', 'add_pads_mgmt_taxonomies', 0 );
add_action( 'add_meta_boxes', 'pads_add_meta_box' );
/*add_action( 'add_meta_boxes', 'pads_featured_image_meta_box' );*/
add_filter( 'enter_title_here', 'pam_wpb_change_title_text' );
add_action( 'save_post', 'pads_save_meta_box_data',1,2 );
function register_pam_post_types() {

	$labels = array(

		'name'               => esc_html__( 'Advertisements', PAM_TEXT_DOMAIN ),
		'singular_name'      => esc_html__( 'Advertisement', PAM_TEXT_DOMAIN ),
		'menu_name'          => esc_html__( 'Advertisement', PAM_TEXT_DOMAIN ),
		'name_admin_bar'     => esc_html__( 'Advertisement', PAM_TEXT_DOMAIN ),
		'add_new'            => esc_attr_x( 'Add New', 'Advertise', PAM_TEXT_DOMAIN ),
		'add_new_item'       => esc_html__( 'Add New Advertise', PAM_TEXT_DOMAIN ),
		'new_item'           => esc_html__( 'New Advertise', PAM_TEXT_DOMAIN ),
		'edit_item'          => esc_html__( 'Edit Advertise', PAM_TEXT_DOMAIN ),
		'view_item'          => esc_html__( 'View Advertise', PAM_TEXT_DOMAIN ),
		'all_items'          => esc_html__( 'All Advertise', PAM_TEXT_DOMAIN ),
		'search_items'       => esc_html__( 'Search Advertise', PAM_TEXT_DOMAIN ),
		'parent_item_colon'  => esc_html__( 'Parent Advertise:', PAM_TEXT_DOMAIN ),
		'not_found'          => esc_html__( 'No Advertise found.', PAM_TEXT_DOMAIN ),
		'not_found_in_trash' => esc_html__( 'No Advertise found in Trash.', PAM_TEXT_DOMAIN )
        
		);

	$args = array(
    
		'labels'             => $labels,
		'public'             => true,
		'menu_icon'  		 => PAM_IMAGE_DIR . 'pam-icons.png',
		'publicly_queryable' => true,
		'show_ui'            => true,		
		'show_in_nav_menus'	 => false,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'pads-mgmt' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions' )
        
		);

	register_post_type( 'pads-mgmt', $args );
}

/**
 *
 *	This method is used to add custom taxonomy
 * 	@access public
 * 	@author PS (psquitu@gmail.com)
 * 	@since  1.0
 * 	@return void
 * 	
 */

function add_pads_mgmt_taxonomies() {
	
	register_taxonomy('padscat', 'pads-mgmt', array(
		
		'hierarchical' => true,		
		'labels' => array(
        
			'name'              => esc_attr__( 'Category', PAM_TEXT_DOMAIN ),
			'singular_name'     => esc_attr__( 'Category', PAM_TEXT_DOMAIN ),
			'search_items'      => esc_html__( 'Search Category', PAM_TEXT_DOMAIN ),
			'all_items'         => esc_html__( 'All Category', PAM_TEXT_DOMAIN ),
			'parent_item'       => esc_html__( 'Parent Category', PAM_TEXT_DOMAIN ),
			'parent_item_colon' => esc_html__( 'Parent Category:', PAM_TEXT_DOMAIN ),
			'edit_item'         => esc_html__( 'Edit Category', PAM_TEXT_DOMAIN ),
			'update_item'       => esc_html__( 'Update Category', PAM_TEXT_DOMAIN ),
			'add_new_item'      => esc_html__( 'Add New Category', PAM_TEXT_DOMAIN ),
			'new_item_name'     => esc_html__( 'New Category Name', PAM_TEXT_DOMAIN ),
			'menu_name'         => esc_html__( 'Categories', PAM_TEXT_DOMAIN ),
            
            ),            		
		'rewrite'        => array(
			'slug'         => 'padscat', 
			'with_front'   => false, 
			'hierarchical' => true 
			),
		)
	);
    
}

/**
 *
 *	add the custom fields
 * 	
 *  @param WP_Post $post The object for the current post/page.
 * 	
 */	
 
function pads_add_meta_box() {
    //this will add the metabox for the Advertise post type
    $screens = array( 'pads-mgmt' );
    
    foreach ( $screens as $screen ) {
    
        add_meta_box(
            'pads_sectionid',
            __( '<u>Advertise Details</u>', PAM_TEXT_DOMAIN ),
            'pads_meta_box_callback',
            $screen
        );
    }
}

/**
 *
 *	add the custom fields
 * 	
 *  @param WP_Post $post The object for the current post/page.
 * 	
 */	
/*function pads_featured_image_meta_box() {
    //this will add the metabox for the Advertise post type
    $screens = array( 'pads-mgmt' );
    
    foreach ( $screens as $screen ) {
    
        add_meta_box(
            'pads_featured_image_sectionid',
            __( '<u>Featured image size</u>', PAM_TEXT_DOMAIN ),
            'pads_featured_image_meta_box_callback',
            $screen,
            'side'
        );
    }
}*/

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function PAM_metaFields($post = null){
    $shortcode = get_post_meta($post->ID,'pads_shortcode_field', true);
    if($shortcode){
        $shortcode = esc_html($shortcode);
    }else{
        $shortcode = '[pam post_id='.$post->ID.']';
    }
    $metaBoxes = array(
                    array(
                        'label' => __('Advertise Link: ', PAM_TEXT_DOMAIN),
                        'type'  => 'text',
                        'name'	=> 'pads_link_field',
                        'for'   => 'pads_link_field',
                        'id'    => 'pads-link-field',
                        'wrapper_id' => '',
                        'class' => 'widefat',
                        'size'  => '100',
                        "style" => "background-color: #fff;",
                        'attribute'    => '',
                        'value' => esc_url(get_post_meta($post->ID,'pads_link_field', true))
                    ),
                    array(
                        'label' => __('Start time: ', PAM_TEXT_DOMAIN),
                        'type'  => 'text',
                        'name'	=> 'pads_start_time_field',
                        'for'   => 'pads_start_time_field',
                        'id'    => 'pads-start-time-field',
                        'wrapper_id' => '',
                        'class' => 'widefat',
                        'size'  => '100',
                        "style" => "background-color: #fff;",
                        'attribute'    => '',
                        'value' => esc_html(get_post_meta($post->ID,'pads_start_time_field', true))
                    ),
                    array(
                        'label' => __('End time: ', PAM_TEXT_DOMAIN),
                        'type'  => 'text',
                        'name'	=> 'pads_end_time_field',
                        'for'   => 'pads_end_time_field',
                        'id'    => 'pads-end-time-field',
                        'wrapper_id' => '',
                        'class' => 'widefat',
                        'size'  => '100',
                        "style" => "background-color: #fff;",
                        'attribute'    => '',
                        'value' => esc_html(get_post_meta($post->ID,'pads_end_time_field', true))
                    ),
                    array(
                        'label' => __('Advertise Shortcode: ', PAM_TEXT_DOMAIN),
                        'type'  => 'text',
                        'name'	=> 'pads_shortcode_field',
                        'for'   => 'pads_shortcode_field',
                        'id'    => 'pads-shortcode-field',
                        'wrapper_id' => '',
                        'class' => 'widefat',
                        'size'  => '100',
                        "style" => "background-color: #fff;",
                        'attribute' => 'readonly',
                        'value' => $shortcode
                    )
                );
    
    return $metaBoxes;
}

function pads_meta_box_callback( $post ) {
    
    // Add a nonce field so we can check for it later.
    wp_nonce_field( plugin_basename( __FILE__ ), 'pads_meta_box_nonce' );
    
    //echo '<pre>';
//    print_r($value);
//    echo '</pre>';
    
    $metaBoxes = PAM_metaFields($post);
    
    $sn = 0;
    foreach($metaBoxes as $metaBox){
        
	    $class = !empty($metaBox['class']) ? esc_attr( ($metaBox['class'])) : '';
        $style = isset($metaBox['style'])?"style='".$metaBox['style']."'":'';
        $attribute = isset($metaBox['attribute']) && $metaBox['attribute'] != '' ? $metaBox['attribute'].'="'.$metaBox['attribute'].'"':'';
        if($metaBox['name']){
            $check_name = 'name="'. $metaBox['name'] . ']"';
        }else{
            $check_name = '';
        }
        
		$html  = '';   
		switch ($metaBox['type']) {
			case 'text':
				$html .= '<p><label for="'. $metaBox['for'] .'"><strong>' . __( $metaBox['label'] ) . '</strong></label>';
				$html .= isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '<div id="'. $metaBox['wrapper_id'] .'">' : '';
				$html .= '<input type="' . $metaBox['type'] . '" name="' . $metaBox['name'] . '" id="'. $metaBox['id'] .'" value="' . esc_attr( $metaBox['value'] ) . '" class="' . $class . '" size="'. $metaBox['size'] .'" ' . $style . $attribute .'>';
				$html .=  isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '</div></p>' : '</p>';
                
				break;
			case 'checkbox':
				$checked =  esc_attr( $metaBox['value'] ) == 1 ? 'checked' : '';
				$html .= '<p class="checkbox"><label for="'. $metaBox['for'] .'" class=""><strong>' . __( $metaBox['label'] ) . '</strong>
				<input type="' . $metaBox['type'] . '" '. $check_name .' id="'. $metaBox['id'] .'" class = "'. $metaBox['class'] .'" value= "'. esc_attr( $metaBox['value'] ) .'" '. $checked .' '. $attribute .'></label></p>';
				
				break;

			case 'textarea':
                if($metaBox['method']=='wp'){
                    $settings = array( 
                    	'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ), // note that spaces in this list seem to cause an issue
                        //'editor_height' => 100, // In pixels, takes precedence and has no default value
                        'textarea_rows' => 10,  // Has no visible effect if editor_height is set, default is 20
                        'wpautop' => false
                    );
                    $content = ( $metaBox['value'] );
                    wp_editor( $content, $metaBox['id'], $settings );
                }else{
				    $html .= '<p><label for="'. $metaBox['for'] .'"><strong>' . __( $metaBox['label'] ) . '</strong></label>
					  	<textarea name="' . $metaBox['name'] . '" id="'. $metaBox['id'] .'" class="' . $class . '" cols="'. $metaBox['row'] .'" cols="'. $metaBox['col'] .'" rows="3">' .  esc_attr( $metaBox['value'] ) . '</textarea></p>';
                }
					  
				break;
			case 'select':
				$html .= '<p><label for="'. $metaBox['for'] .'"><strong>' . __( $metaBox['label'] ) . '</strong></label>';
				$html .= isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '<div id="'. $metaBox['wrapper_id'] .'">' : '';
				$html .= '<select class="'. $metaBox['class'] .'" name="'. $metaBox['name'] . '"  id="'. $metaBox['id'] .'" ' . $event . $attribute . '>';
				$html .= '<option value="">'. __('Select' , PS_TEXT_DOMAIN) .'</option>';
				foreach ($metaBox['value'] as $key => $value) {
				   $key = $metaBox['insert_not_key'] == true ? $value : $key ;
				   $selected = ($key == $metaBox['selected_value']) ? 'selected="selected"' : '';
				   $html .= '<option value= "' . $key . '"  '. $selected .'>' . $value . '</option>';
				}
				$html .= '</select>';
				$html .=  isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '</div>' : '';
                $html .= '</p>';
                
                break;
			case 'file':
                if($metaBox['method']=='wp'){
                    $html .= '<p><label for="'. $metaBox['for'] .'"><strong>' . __( $metaBox['label'] ) . '</strong></label>';
    				$html .= isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '<div id="'. $metaBox['wrapper_id'] .'">' : '';
    				$html .= '<input type="text" name="' . $metaBox['name'] . '" id="'. $metaBox['id'] .'" value="' . esc_attr( $metaBox['value'] ) . '" class="' . $class . '"' . $style . $attribute .'>';
                    $html .= '<input type="button" class="'. $metaBox['button_class'] .'" id="'. $metaBox['button_id'] .'" value="'. $metaBox['button_text'] .'" style="margin-top:5px;"/>';
    				$html .=  isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '</div></p>' : '</p>';
                }else{
                    $html .= '<p><label for="'. $metaBox['for'] .'"><strong>' . __( $metaBox['label'] ) . '</strong></label>';
    				$html .= isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '<div id="'. $metaBox['wrapper_id'] .'">' : '';
    				$html .= '<input type="' . $metaBox['type'] . '" name="' . $metaBox['name'] . '" id="'. $metaBox['id'] .'" value="' . esc_attr( $metaBox['value'] ) . '" class="' . $class . '"' . $style . $attribute .'>';
    				$html .=  isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '</div></p>' : '</p>';
                }
                
				break;
			case 'repeater':
                $html .= '<div class=".misc-pub-section">';
                $html .= '<label for=""><strong>' . __( $metaBox['label'] ) . '</strong></label>';
				$html .= isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '<div id="'. $metaBox['wrapper_id'] .'">' : '';
                $r_name_array = explode('[',$metaBox['name']);
                $r_name = $r_name_array[0];
                
                $html .= '<div class="pn-text-append">';
                if($value[$r_name]){
                    $r_image = unserialize($metaBox['value']);
                    $sn = 0;
                    foreach($r_image as $image_url){
                        $html .= '<div>';
                        $html .= '<input type="text" name="' . $metaBox['name'] . '" id="'. $metaBox['id'] .'" value="' . esc_url( $image_url ) . '" class="' . $class . '" size="'. $metaBox['size'] .'"' . $style . $attribute .'>';
                        $html .= '<input type="button" class="'.$metaBox['button_image_class'].'" value="'. $metaBox['button_image_text'] .'">';
                        if($sn != 0){
                            $html .= '<input type="button" class="'. $metaBox['button_remove_class'] .'" value="'. $metaBox['button_remove'] .'" style="margin-left: 3px;">';
                        }
                        $html .= '</div>';
                        $sn++;
                    }
                }else{
                    $html .= '<input type="text" name="' . $metaBox['name'] . '" id="'. $metaBox['id'] .'" value="' . esc_attr( $metaBox['value'] ) . '" class="' . $class . '" size="'. $metaBox['size'] .'"' . $style . $attribute .'>';
                    $html .= '<input type="button" class="'.$metaBox['button_image_class'].'" value="'. $metaBox['button_image_text'] .'">';
                    //$html .= '<input type="button" class="'. $metaBox['button_remove_class'] .'" value="'. $metaBox['button_remove'] .'" style="margin-left: 3px;">';
                }    				
                $html .= '</div>';
                $attributes_of_repeater = array(
                    'r_class' => $class,
                    'r_id' => $metaBox['id'],
                    'r_name' => $metaBox['name'],
                    'r_size' => $metaBox['size'],
                    'r_style' => $style,
                    'r_attribute' => $attribute ,
                    'r_button_image_class' => $metaBox['button_image_class'],
                    'r_button_image_text' => $metaBox['button_image_text'],
                    'r_button_remove_class' => $metaBox['button_remove_class'],
                    'button_remove' => $metaBox['button_remove']
                );
                $html .= '<input type="button" class="'. $metaBox['button_class'] .'" id="'. $metaBox['button_id'] .'" value="'. $metaBox['button_text'] .'" style="margin-top:5px;" />';
				$html .=  isset($metaBox['wrapper']) && $metaBox['wrapper'] ? '</div>' : '';
                $html .= '</div>';
                
				break;
			default:
				$html .= '<label for="'. $metaBox['for'] .'"><strong>' . __( $metaBox['label'] ) . '</strong></label>
					  	<input type="' . $metaBox['type'] . '" name="' . $metaBox['name'] . '" id="'. $metaBox['id'] .'" value="" class="regular-text" >';
				break;
		}
        $sn++;
		echo $html;
    }    
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
 function pads_save_meta_box_data( $post_id, $post ) {
    $metaSaveRequest = array('pads-mgmt');
    if(in_array($post->post_type, $metaSaveRequest)){
        
        if ( ! isset( $_POST['pads_meta_box_nonce'] ) ) {
            return;
        }
        
        if ( ! wp_verify_nonce( $_POST['pads_meta_box_nonce'], plugin_basename( __FILE__ ) ) ) {
            return;
        }
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        
        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        
        } else {
        
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
        
        
        $metaFields = PAM_metaFields();
        
        //echo '<pre>';
//        print_r(PAM_metaFields);
//        echo '</pre>';exit;
        
        foreach($metaFields as $metaField){
            $name = $metaField['name'];
            //if($name == 'screenshots[]'){
//                $name = 'screenshots';
//            }
            if ( ! isset( $_POST[$name] ) ) {
                return;
            }
            $my_data = sanitize_text_field($_POST[$name]);     
                     
            update_post_meta( $post_id, $name, $my_data );
        }
    }
    
}

/**
 * 
 * change the default placeholder of title
 * @param null
 * 
 */
function pam_wpb_change_title_text( $title ){
     $screen = get_current_screen();
 
     if  ( 'pads-mgmt' == $screen->post_type ) {
          $title = 'Enter Advertisement Title';
     }
 
     return $title;
}

/*function pads_featured_image_meta_box_callback( $post ) {
    
    // Add a nonce field so we can check for it later.
    wp_nonce_field( plugin_basename( __FILE__ ), 'pads_meta_box_nonce' );
    //echo '<pre>';
//    print_r($post);
//    echo '</pre>';
    ?>
        <label for="featured_img_width"> Width: </label>
        <input type="text" size="5" name="featured_img_width" id="featured_img_width" value="" />
        <label for="featured_img_height"> Height: </label>
        <input type="text" size="5" name="featured_img_height" id="featured_img_height" value="" />
        <p><strong>Note: </strong><i>Numeric value in px.</i></p>
    <?php
}*/