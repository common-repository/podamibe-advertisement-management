<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
/*
  Plugin name: Podamibe Advertisement Management
  Plugin URI: http://podamibenepal.com/wordpress-plugins/
  Description: A plugin to manage advertise to a site with dynamic configuration options.
  Version: 1.0.4
  Author: Podamibe Nepal
  Author URI: http://podamibenepal.com
  Text Domain: podamibe-add-mgmt
  Domain Path: /languages/
  License: GPLv2 or later
 */
 
 //Decleration of the necessary constants for plugin
!defined( 'PAM_PLUGIN_DIR' ) ? define( 'PAM_PLUGIN_DIR', plugin_dir_url( __FILE__ ) ):null;
!defined( 'PAM_IMAGE_DIR' ) ? define( 'PAM_IMAGE_DIR', plugin_dir_url( __FILE__ ) . 'images/' ): null;
!defined( 'PAM_CSS_DIR' ) ? define( 'PAM_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css/' ): null;
!defined( 'PAM_JS_DIR' ) ? define( 'PAM_JS_DIR', plugin_dir_url( __FILE__ ) . 'js/' ): null;
!defined( 'PAM_INC_BAC_DIR' ) ? define( 'PAM_INC_BAC_DIR', plugin_dir_path( __FILE__ ) . 'inc/backend/' ):null;
!defined( 'PAM_INC_FRN_DIR' ) ? define( 'PAM_INC_FRN_DIR', plugin_dir_path( __FILE__ ) . 'inc/frontend/' ):null;
!defined( 'PAM_LANG_DIR' ) ? define( 'PAM_LANG_DIR', basename( dirname( __FILE__ ) ) . '/languages/' ):null;

!defined('PAM_VERSION') ? define( 'PAM_VERSION', '1.0.3' ) : null;
!defined('PAM_SLICK_VERSION') ? define( 'PAM_SLICK_VERSION', '1.6.0' ) : null;

!defined('PAM_TEXT_DOMAIN') ? define( 'PAM_TEXT_DOMAIN', 'podamibe-add-mgmt' ) : null;
!defined('PAM_SETTING_NAME') ? define( 'PAM_SETTING_NAME', 'pam_setting' ) : null;

/**
 * 
 * Decleration of the class for necessary configuration of a plugin
 * @package     Podamibe Advertisement Management
 * @subpackage  admin
 * @copyright   Copyright (c) 2016, Podamibe
 * @author      Prakash Sunuwar
 * @since       1.0  
 */
if ( !class_exists( 'PAM_Class' ) ) {
         
    class PAM_Class {
        
        /**
    	 * default settings for Podamibe Advertisement Management.
    	 *
    	 * @since 1.0
    	 * @var string
    	 */
        public $_pam_settings = '';
        
        function __construct(){
            $this->_pam_settings = get_option( PAM_SETTING_NAME ); //get the plugin variable contents from the options table.
            register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) ); //load the default setting for the plugin while activating
            //add_action( 'init', array( $this, 'plugin_text_domain' ) ); //load the plugin text domain
			add_action( 'init', array( $this, 'session_init' ) ); //start the session if not started yet.
            add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) ); //registers all the assets required for wp-admin
            add_action( 'wp_enqueue_scripts', array($this,'register_front_assets') ); //registers all the assets required for Frontend
            $this->_includes();
            
            $pam_single_shortcode = new PAM_Single_Shortcode();
            add_shortcode( 'pam', array( $pam_single_shortcode, 'pads_shortcode_callback' ) );
            
            $pam_bulk_shortcode = new PAM_Bulk_Shortcode();
            add_shortcode( 'pam_bulk', array( $pam_bulk_shortcode, 'pads_bulk_shortcode_callback' ) );
            
            add_action('admin_init', array($this, 'shortcode_button'));
            add_action('admin_footer', array($this, 'get_shortcodes'));
            
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'action_links') );

            add_filter("plugin_row_meta", array($this, 'get_extra_meta_links'), 10, 4);
        }
        
        //called when plugin is activated
		function plugin_activation() {

			global $wpdb;
            if ( is_multisite() ) {
                $current_blog = $wpdb->blogid;
                // Get all blogs in the network and activate plugin on each one
                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    if ( !get_option( PAM_SETTING_NAME ) ) {
						require( 'inc/backend/activation.php' );
					}
                }
            }else{
                if ( !get_option( PAM_SETTING_NAME ) ) {
					require( 'inc/backend/activation.php' );
				}
            }           
		}
        
        //loads the text domain for translation
		//function plugin_text_domain() {
//			load_plugin_textdomain( 'podamibe-add-mgmt', false, PAM_LANG_DIR );
//		}
        
        //starts the session with the call of init hook
        function session_init() {
			if ( !session_id() && !headers_sent() ) {
				session_start();
			}
		}
        
        //functions to register admin styles and scripts
		function register_admin_assets() {
			/**
			 * Backend CSS
			 * */
			//if ( isset( $_GET['page'] ) && $_GET['page'] == 'pam-admin-main-page' ) {
                wp_enqueue_style( 'pam-datetimepicker-style', PAM_CSS_DIR . 'jquery.datetimepicker.css', false, PAM_VERSION );
				wp_enqueue_style( 'pam-admin-style', PAM_CSS_DIR . 'backend.css', false, PAM_VERSION ); //registering plugin admin css

				/**
				 * Backend JS
				 * */
                 
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'jquery-ui-datepicker' );
                wp_enqueue_script( 'pam-datetimepicker-scripts', PAM_JS_DIR . 'jquery.datetimepicker.full.js', array( 'jquery' ), PAM_VERSION );
				wp_enqueue_script( 'pam-admin-scripts', PAM_JS_DIR . 'backend.js', array( 'jquery', 'jquery-ui-datepicker', 'wp-color-picker' ), PAM_VERSION ); //registering plugin's admin js
			//}
		}
        //functions to register frontend styles and scripts
        function register_front_assets(){
            
            wp_enqueue_style( 'pam-front-style', PAM_CSS_DIR . 'frontend.css', false, PAM_VERSION ); //registering plugin frontend css
            //if(isset($this->_pam_settings['enable_slider']) && $this->_pam_settings['enable_slider']==1){
                wp_enqueue_script( 'jquery');
                wp_enqueue_style( 'pam-slick-style', PAM_CSS_DIR . 'slick.css', false, PAM_SLICK_VERSION ); //registering plugin slick slider css
                wp_enqueue_style( 'pam-slick-theme-style', PAM_CSS_DIR . 'slick-theme.css', false, PAM_SLICK_VERSION ); //registering plugin slick slider theme css
                wp_enqueue_script( 'pam-slick-scripts', PAM_JS_DIR . 'slick.js', false, PAM_SLICK_VERSION ); //registering plugin slick slider scripts
                
                wp_enqueue_script( 'pam-front-scripts', PAM_JS_DIR . 'frontend.js', false, PAM_VERSION ); //registering plugin slick slider scripts
            //}
            
        }
        
        /**
     	 * This method is called to include required file
     	 * @param null
     	 * @return void
     	 * @since 1.0
     	 * 
     	 **/
        private function _includes(){
            require_once( 'inc/frontend/dynamic-style.php' );
            require_once( 'inc/frontend/padm-bulk-shortcodes.php' ); 
            require_once( 'inc/frontend/padm-single-shortcodes.php' );
            require_once( 'inc/backend/includes/padm-widget.php' );
            require_once( 'inc/backend/includes/padm-types.php' ); 
            require_once( 'inc/backend/class-pam-settings.php' );
            require_once( 'inc/backend/includes/class-pam-main-page.php' );
            require_once( 'inc/backend/class-dashboard.php' );
        }
        
        /**
         * Create a shortcode button for tinymce
         *
         * @return [type] [description]
         */
        public function shortcode_button()
        {
            if( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
            {
                add_filter( 'mce_external_plugins', array($this, 'add_buttons' ));
                add_filter( 'mce_buttons', array($this, 'register_buttons' ));
            }
        }
        
        /**
         * Add new Javascript to the plugin scrippt array
         *
         * @param  Array $plugin_array - Array of scripts
         *
         * @return Array
         */
        public function add_buttons( $plugin_array )
        {
            $path = PAM_JS_DIR . 'shortcode.js';
            $plugin_array['rashortcodes'] = $path;
    
            return $plugin_array;
        }
        
        /**
         * Add new button to tinymce
         *
         * @param  Array $buttons - Array of buttons
         *
         * @return Array
         */
        public function register_buttons( $buttons )
        {
            array_push( $buttons, 'separator', 'rashortcodes' );
            return $buttons;
        }
        
        /**
         * Add shortcode JS to the page
         *
         * @return HTML
         */
        public function get_shortcodes()
        {
            //global $shortcode_tags;
            $shortcode_tags = array(
                                    'pam' => 'pads_shortcode_callback',
                                    'pam_bulk' => 'pads_bulk_shortcode_callback'
                                );
            echo '<script type="text/javascript">
            var shortcodes_button = new Array();';
    
            $count = 0;
    
            foreach($shortcode_tags as $tag => $code)
            {
                echo "shortcodes_button[{$count}] = '{$tag}';";
                $count++;
            }
    
            echo '</script>';
        }
        
        /**
         * this function is use to redirect setting page
         *
         * @return HTML
         */
        public function action_links( $links ) {
           $links[] = '<a href="'. esc_url( get_admin_url(null, 'edit.php?post_type=pads-mgmt&page=ads-mgmt-settings') ) .'">'.__('Settings', PAM_TEXT_DOMAIN).'</a>';
           //$links[] = '<a href="http://podamibenepal.com/wordpress-plugins/" target="_blank">'.__('More plugins by Podamibe', PAM_TEXT_DOMAIN).'</a>';
           return $links;
        }



        /**
         * Adds extra links to the plugin activation page
         */
        public function get_extra_meta_links($meta, $file, $data, $status) {

            if (plugin_basename(__FILE__) == $file) {
                $meta[] = "<a href='http://shop.podamibenepal.com/forums/forum/support/' target='_blank'>" . __('Support', 'podamibe-add-mgmt') . "</a>";
                $meta[] = "<a href='http://shop.podamibenepal.com/downloads/podamibe-advertisement-management/' target='_blank'>" . __('Documentation  ', 'podamibe-add-mgmt') . "</a>";
                $meta[] = "<a href='https://wordpress.org/support/plugin/podamibe-advertisement-management/reviews#new-post' target='_blank' title='" . __('Leave a review', 'podamibe-add-mgmt') . "'><i class='ml-stars'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg></i></a>";
            }
            return $meta;
        }

        
    }
    //PAM_Class termination

	$pam_object = new PAM_Class();   
}