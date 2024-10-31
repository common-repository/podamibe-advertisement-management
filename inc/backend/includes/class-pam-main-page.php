<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    die('No script kiddies please!');
}

if( !class_exists( 'PAM_Main_Page' ) ){
    class PAM_Main_Page {
        
        private $settings_objects;
        
        function __construct(){
            $this->settings_objects = new PAM_Settings();
        }        
        
        /**
         * This method is called to display setting page
     	 * @param null
     	 * @return void
     	 * @since 1.0
         */        
        public function main_view(){
            ?>
                <div class="pam-main-wrapper wrap">                                   
                	<div class="pam-main-title">
                		<?php esc_html_e('Podamibe Advertisement Management', PAM_TEXT_DOMAIN);?>
                	</div>
                    <div id="screen-meta" class="metabox-prefs" style="display: block;">
                        <div id="contextual-help-wrap" tabindex="-1" aria-label="Contextual Help Tab">
            				<div id="contextual-help-back"></div>
            				<div id="contextual-help-columns">
            					<div class="contextual-help-tabs">
                                	<ul class="pamtabs">
                                		<li class="tab-link active" data-tab="pamtabs-1">
                                			<a href="#pamtabs-1"><?php esc_html_e('Settings', PAM_TEXT_DOMAIN);?></a>
                                		</li>
                                		<li class="tab-link" data-tab="pamtabs-2">
                                			<a href="#pamtabs-2"><?php esc_html_e("Other Settings", PAM_TEXT_DOMAIN);?></a>
                                		</li>
                                		<li class="tab-link" data-tab="pamtabs-3">
                                			<a href="#pamtabs-3"><?php esc_html_e("How to Use", PAM_TEXT_DOMAIN);?></a>
                                		</li>
                                		<li class="tab-link" data-tab="pamtabs-4">
                                			<a href="#pamtabs-4"><?php esc_html_e("About", PAM_TEXT_DOMAIN);?></a>
                                		</li>
                                	</ul>
                                </div>
                                <div class="contextual-help-sidebar">


                                    <p class="elementor-message-actions">
                                        <a href="<?php echo esc_url( 'http://shop.podamibenepal.com/downloads/podamibe-advertisement-management/' ); ?>" class="button button-primary"><?php esc_html_e('Documentation', SFWA_TEXT_DOMAIN);?></a><br /><br />
                                        <a href="<?php echo esc_url( 'http://shop.podamibenepal.com/downloads/podamibe-advertisement-management/' ); ?>" class="button button-primary"><?php esc_html_e('Details', SFWA_TEXT_DOMAIN);?></a><br /><br />
                                        <a href="<?php echo esc_url( 'http://shop.podamibenepal.com/forums/forum/support/' ); ?>" class="button button-primary"><?php esc_html_e('Live Support', SFWA_TEXT_DOMAIN);?></a><br />
                                    </p><br /><br />


                                    <p><strong><?php esc_html_e('Visit our other plugins:', PAM_TEXT_DOMAIN);?></strong></p>
                                    <p><a href="<?php echo esc_url('https://wordpress.org/plugins/podamibe-simple-footer-widget-area/');?>"><?php esc_html_e('Podamibe Simple Footer Widget Area', PAM_TEXT_DOMAIN);?></a></p>
                                    <p><a href="<?php echo esc_url('https://wordpress.org/plugins/podamibe-custom-user-gravatar/');?>"><?php esc_html_e('Podamibe Custom User Gravatar', PAM_TEXT_DOMAIN);?></a></p>
                                    <p><a href="<?php echo esc_url('https://wordpress.org/plugins/podamibe-facebook-feed-widget/');?>"><?php esc_html_e('Podamibe Facebook Widget', PAM_TEXT_DOMAIN);?></a></p>
                                    <p><a href="<?php echo esc_url('https://wordpress.org/plugins/podamibe-twitter-feed-widget/');?>"><?php esc_html_e('Podamibe Twitter Feed Widget', PAM_TEXT_DOMAIN);?></a></p>
                                    <p><a href="<?php echo esc_url('https://wordpress.org/plugins/podamibe-social-icons-widget/');?>"><?php esc_html_e('Podamibe Social Widget', PAM_TEXT_DOMAIN);?></a></p>
                                    <p><a href="<?php echo esc_url('https://wordpress.org/plugins/podamibe-appointment-calendar/');?>"><?php esc_html_e('Podamibe Appointment Calendar', PAM_TEXT_DOMAIN);?></a></p>
                                    <p><a href="<?php echo esc_url('https://wordpress.org/plugins/podamibe-custom-post/');?>"><?php esc_html_e('Podamibe Custom Post', PAM_TEXT_DOMAIN);?></a></p>					
                                </div>
                                <div class="contextual-help-tabs-wrap">
                                    <form action="" method="post">
                                    	<div id="pamtabs-1" class="help-tab-content pam-tab-content active">
                                            <?php $this->settings_objects->setting_view();?>
                                        </div>
                                    	<div id="pamtabs-2" class="help-tab-content pam-tab-content">
                                            <?php $this->settings_objects->other_setting_view();?>
                                        </div>
                                    	<div id="pamtabs-3" class="help-tab-content pam-tab-content">
                                            <?php $this->settings_objects->use_view();?>
                                        </div>
                                    	<div id="pamtabs-4" class="help-tab-content pam-tab-content">
                                            <?php $this->settings_objects->about_view();?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }
    }
}