<?php
/**
 * This is custom class reponsible for register admin menu
 * for Podamibe chat
 * @package Podamibe chat
 * @subpackage admin/class
 * @since 1.0
 */
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    die('No script kiddies please!');
}

if( !class_exists( 'PAM_Dashboard' ) ){
    class PAM_Dashboard {
        
        private $_adminMenus = array();
        public $adminUrl = '';
        private $mainPage;
        
        function __construct() {
            $this->mainPage = new PAM_Main_Page();
       	    $this->adminUrl = admin_url('admin.php');
            add_action('admin_menu', array($this, 'pam_admin_menu'));
    	}
        
        /**
         *
         * 	This function is used to create admin menus 
         * 	for auto login plugin
         * 	@access public
         * 	@author 
         * 	@since  1.0
         * 	@return void
         * 	
         */
        public function pam_admin_menu() {
            //$mainMenu = __('Advertisement Mgmt', PAM_TEXT_DOMAIN);//ADVERTISEMENT MANAGEMENT
    		//add_menu_page($mainMenu, $mainMenu, 'manage_options', 'pam-admin-main-page', array( $this, 'pam_main_page' ), PAM_IMAGE_DIR . 'pam-set-icons.png');
            $menuTitle = esc_html__('Advertisement Settings', PAM_TEXT_DOMAIN);
            $menuName = esc_html__('Settings', PAM_TEXT_DOMAIN);
            add_submenu_page('edit.php?post_type=pads-mgmt', $menuTitle, $menuName, 'edit_posts', 'ads-mgmt-settings', array( $this, 'pam_main_page' ));
        }
        
        /**
         *
         * 	This function is used for manage the settings
         * 	for auto login form
         * 	@access public
         * 	@author 
         * 	@since  1.0
         * 	@return void
         * 	
         */
        public function pam_main_page() {
            $this->mainPage->main_view();
            //require_once('pam-main-page.php');
        }
        
    }
    //PAM_Dashboard termination
    $pam_dashboard_object = new PAM_Dashboard();
}
 