<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    die('No script kiddies please!');
}

if( !class_exists( 'PAM_Settings' ) ){
    class PAM_Settings {
        
        private $_settings = '';
        private $_lineStyles = array();
        
        function __construct(){
            $this->create();
            $this->_settings = get_option( PAM_SETTING_NAME );
            $this->_lineStyles = $this->lineStyles();
        }
        
        /**
         * This method is called to display the settings
     	 * @param null
     	 * @return void
     	 * @since 1.0
         */
        public function setting_view(){
            ?>
                <div id="screen-options-wrap" class="setting-wrap">
                    <fieldset class="metabox-prefs">
                		<legend><?php esc_html_e( 'Podamibe Advertisement Management', PAM_TEXT_DOMAIN );?></legend>
                		<label><input type="checkbox" name="settings[enable_disable]" value="1" <?php if(isset($this->_settings['enable_disable']) && $this->_settings['enable_disable']==1){echo 'checked="checked"';}?> /><?php esc_html_e( 'Enable/Disable ', PAM_TEXT_DOMAIN );?></label>
                	</fieldset>
                    <fieldset class="metabox-prefs">
                        <legend><?php esc_html_e('Ads Link Tab Setting', PAM_TEXT_DOMAIN);?></legend>
                        <label><input type="checkbox" name="settings[pads_open_tab]" value="1" <?php if(isset($this->_settings['pads_open_tab']) && $this->_settings['pads_open_tab']==1){echo 'checked="checked"';}?> /><?php esc_html_e('Open in new window', PAM_TEXT_DOMAIN);?></label>
                    </fieldset>
                    <fieldset class="metabox-prefs">
                        <legend><?php esc_html_e('Add Design Layout Setting', PAM_TEXT_DOMAIN);?></legend>
                        <label class="pads-design-label"><?php esc_html_e('Design Options: ', PAM_TEXT_DOMAIN);?></label>
                        <div class="pads-designing-wrap pads-wrap">
                            <div class="design_option">
                                <div class="pads_margin_option">
                                    <div class="pads_margin_option_wrapper">
                                        <span class="note"><?php esc_html_e('(in px)', PAM_TEXT_DOMAIN);?></span>
                                        <label>
                                            <?php esc_html_e('margin', PAM_TEXT_DOMAIN);?>
                                        </label>
                                        <input class="top" type="text" name="settings[margin][footer-margin-top]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-margin-top']))echo esc_html($this->_settings['margin']['footer-margin-top']);?>" />
                                        <input class="right" type="text" name="settings[margin][footer-margin-right]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-margin-right']))echo esc_html($this->_settings['margin']['footer-margin-right']);?>" />
                                        <input class="bottom" type="text" name="settings[margin][footer-margin-bottom]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-margin-bottom']))echo esc_html($this->_settings['margin']['footer-margin-bottom']);?>" />
                                        <input class="left" type="text" name="settings[margin][footer-margin-left]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-margin-left']))echo esc_html($this->_settings['margin']['footer-margin-left']);?>" />
                                    </div>
                                    <div class="pads_border_option">
                                        <div class="pads_border_option_wrapper">
                                            <label>
                                                <?php esc_html_e('border', PAM_TEXT_DOMAIN);?>
                                            </label>
                                            <input class="top" type="text" name="settings[border][footer-border-top]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-border-top']))echo esc_html($this->_settings['border']['footer-border-top']);?>" />
                                            <input class="right" type="text" name="settings[border][footer-border-right]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-border-right']))echo esc_html($this->_settings['border']['footer-border-right']);?>" />
                                            <input class="bottom" type="text" name="settings[border][footer-border-bottom]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-border-bottom']))echo esc_html($this->_settings['border']['footer-border-bottom']);?>" />
                                            <input class="left" type="text" name="settings[border][footer-border-left]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-border-left']))echo esc_html($this->_settings['border']['footer-border-left']);?>" />
                                        </div>
                                        <div class="pads_padding_option">
                                            <div class="pads_padding_option_wrapper">
                                                <label>
                                                    <?php esc_html_e('padding', PAM_TEXT_DOMAIN);?>
                                                </label>
                                                <input class="top" type="text" name="settings[padding][footer-padding-top]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-padding-top']))echo esc_html($this->_settings['padding']['footer-padding-top']);?>" />
                                                <input class="right" type="text" name="settings[padding][footer-padding-right]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-padding-right']))echo esc_html($this->_settings['padding']['footer-padding-right']);?>" />
                                                <input class="bottom" type="text" name="settings[padding][footer-padding-bottom]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-padding-bottom']))echo esc_html($this->_settings['padding']['footer-padding-bottom']);?>" />
                                                <input class="left" type="text" name="settings[padding][footer-padding-left]" placeholder="-" value="<?php if(isset($this->_settings['margin']['footer-padding-left']))echo esc_html($this->_settings['padding']['footer-padding-left']);?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pads-border-style pads_design_option_style">
                                <div class="pads-color ">
                                    <label><?php esc_html_e('Border Color: ', PAM_TEXT_DOMAIN);?></label><br />
                                    <input type="text" class="jquery-color-picker color-field" id="pads-border-color" name="settings[border_style][border_color]" value="<?php if(isset($this->_settings['border_style']['border_color'])){echo esc_html($this->_settings['border_style']['border_color']);}?>" />
                                </div>
                                <div class="pads-line-style ">
                                    <label for="pads-border-line-style"><?php esc_html_e('Border Line Style: ', PAM_TEXT_DOMAIN);?></label><br />
                                    <select name="settings[border_style][line_style]" id="pads-border-line-style" class="pads-border-style">
                                        <option value=""><?php esc_html_e('Select', PAM_TEXT_DOMAIN);?></option>
                                        <?php 
                                            foreach( $this->_lineStyles as $lineStyle ){
                                                ?>
                                                    <option value="<?php echo $lineStyle;?>" <?php if(isset($this->_settings['border_style']['line_style']) && $this->_settings['border_style']['line_style']==$lineStyle){echo 'selected="selected"';}?> >
                                                        <?php echo $lineStyle;?>
                                                    </option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="metabox-prefs">
                        <legend><input type="submit" value="Submit" name="settings_submit" class="button button-primary button-large" /></legend>
                    </fieldset>
                </div>
            <?php
        }
        
        /**
         * This method is called to display the other settings
     	 * @param null
     	 * @return void
     	 * @since 1.0
         */
        
        public function other_setting_view(){
            ?>
                <div id="screen-options-wrap" class="pads-other-set-wrap">
                    <div class="pads-select-ads-wrap">
                        <fieldset class="metabox-prefs">
                    		<legend><?php esc_html_e('Shortcode Settings', PAM_TEXT_DOMAIN);?></legend>
                    		<label>
                                <input type="checkbox" class="pam_other_setting_control" name="settings[bulk_shortcode_en_dis]" value="1" <?php if(isset($this->_settings['bulk_shortcode_en_dis']) && $this->_settings['bulk_shortcode_en_dis']==1){echo 'checked="checked"';}?> />
                                <?php esc_html_e( 'Bulk Shortcode Enable/Disable', PAM_TEXT_DOMAIN );?>
                            </label>
                    	</fieldset>
                        <div class="pam-other-section-wrap">
                            <fieldset class="metabox-prefs">
                                <legend><?php esc_html_e('Select Ads To Show In Bulk ', PAM_TEXT_DOMAIN);?></legend>
                                <?php
                                    $args = array(
                                                    'post_type' => 'pads-mgmt',
                                                    'post_status' => 'publish',
                                                    'posts_per_page' => -1,
                                                );
            
                                    $query_posts = new WP_Query( $args );
                                ?>
                                <select name="settings[selected_adds][]" multiple="multiple" class="pads-adds-select">
                                    <option value=""><?php esc_html_e('Select', PAM_TEXT_DOMAIN);?></option>
                                    <?php
                                        if($query_posts->have_posts()):
                                            while ( $query_posts->have_posts() ) : $query_posts->the_post();
                                                ?>
                                                	<option value="<?php echo get_the_ID();?>" <?php if(isset($this->_settings['selected_adds']) && in_array(get_the_ID(), $this->_settings['selected_adds'])){ echo 'selected="selected"';}?> >
                                                		<?php the_title(); ?>
                                                	</option>
                                                <?php  
                                            endwhile; // end of the loop.
                                        endif;
                                    ?>
                                </select>
                            </fieldset>
                            <fieldset class="metabox-prefs">
                                <legend><?php esc_html_e( 'Bulk Shortcode', PAM_TEXT_DOMAIN );?></legend>
                                <input type="text" name="settings[bulk_shortcode]" id="pads-bulk-shortcode" size="30" value="<?php if(isset($this->_settings['bulk_shortcode'])){echo esc_html( $this->_settings['bulk_shortcode'] );}?>" readonly="readonly" />
                            </fieldset>
                                
                            <fieldset class="metabox-prefs">
                        		<legend><?php esc_html_e('Bulk Advertisement Display Duration', PAM_TEXT_DOMAIN);?></legend>
                        		<label>
                                    <input type="checkbox" name="settings[set_duration]" value="1" id="pads-set-duration" <?php if(isset($this->_settings['set_duration']) && $this->_settings['set_duration']==1){echo 'checked="checked"';}?> />
                                    <?php esc_html_e( 'Set Duration', PAM_TEXT_DOMAIN );?>
                                </label>
                        	</fieldset>
                            
                            <div class="pads-date-duration" style="display: none;">
                                <div class="pads-start-time-wrap">
                                    <label for="pads-start-time"><?php esc_html_e( 'Start Time: ', PAM_TEXT_DOMAIN );?></label><br />
                                    <input type="text" name="settings[bulk_start_time]" id="pads-start-time" class="jquery-datepicker" value="<?php if(isset($this->_settings['bulk_start_time'])){echo esc_html( $this->_settings['bulk_start_time'] );}?>" />
                                </div>
                                <div class="pads-end-time-wrap">
                                    <label for="pads-end-time"><?php esc_html_e( 'End Time: ', PAM_TEXT_DOMAIN );?></label><br />
                                    <input type="text" name="settings[bulk_end_time]" id="pads-end-time" class="jquery-datepicker" value="<?php if(isset($this->_settings['bulk_end_time'])){echo esc_html( $this->_settings['bulk_end_time'] );}?>" />
                                </div>
                            </div>
                            
                            <div class="pads-slider-settings-wrap">
                                <fieldset class="metabox-prefs">
                            		<legend><?php esc_html_e('Bulk Advertisement Slider Settings', PAM_TEXT_DOMAIN);?></legend>
                            		<label>
                                        <input type="checkbox" name="settings[enable_slider]" value="1" id="pads-set-slider" <?php if(isset($this->_settings['enable_slider']) && $this->_settings['enable_slider']==1){echo 'checked="checked"';}?> />
                                        <?php esc_html_e( 'Enable Slider', PAM_TEXT_DOMAIN );?>
                                    </label>
                            	</fieldset>
                            </div>
                            
                        </div>
                        <fieldset class="metabox-prefs">
                            <legend><input type="submit" value="Submit" name="settings_submit" class="button button-primary button-large" /></legend>
                        </fieldset>
                    </div>
                </div>
            <?php
        }
        
        /**
         * This method is called to display the how to use view
     	 * @param null
     	 * @return void
     	 * @since 1.0
         */
        public function use_view(){
            ?>
                <div id="screen-options-wrap" class="pads-use-wrap">
                    <fieldset class="metabox-prefs">
                		<legend><?php esc_html_e('Follow the instruction to use', PAM_TEXT_DOMAIN);?></legend>
                		<p>
                            <?php esc_html_e('To display the', PAM_TEXT_DOMAIN);?> <strong><i><?php esc_html_e('Advertise', PAM_TEXT_DOMAIN);?></i></strong> <?php esc_html_e('in your web site, you can use', PAM_TEXT_DOMAIN);?> <input type="text" value="[pam post_id='add post id here']" readonly="readonly" size="30" /> <?php esc_html_e('Shortcode and for bulk ads', PAM_TEXT_DOMAIN);?> <input type="text" value="[pam_bulk pad_id='post_id,post_id']" readonly="readonly" size="35" /> <?php esc_html_e('or you can use', PAM_TEXT_DOMAIN);?> <strong><i><?php esc_html_e('Advertisement Management Widget', PAM_TEXT_DOMAIN);?></i></strong> <?php esc_html_e("from Appearance's widget section. Mainly there are two settings tabs (Settings and Other Settings ) tab that will help you to setup the plugin to work properly.", PAM_TEXT_DOMAIN);?>
                        </p>
                	</fieldset>
                    <fieldset class="metabox-prefs">
                        <legend><?php esc_html_e('Settings', PAM_TEXT_DOMAIN);?></legend>
                        <p><?php esc_html_e('In this tab for control the overall plugin, you can choose layout option and border color option and border line style. You can make choose your setting from this tab.', PAM_TEXT_DOMAIN);?></p>
                    </fieldset>
                    <fieldset class="metabox-prefs">
                        <legend><?php esc_html_e('Other Settings', PAM_TEXT_DOMAIN);?></legend>
                        <p><?php esc_html_e('This tab for control the bulk shortcode, you can enable/disable bulk shortcode, multiple add select option and set display duration in bulk. You can make choose your setting from this tab.', PAM_TEXT_DOMAIN);?></p>
                    </fieldset>
                </div>
            <?php
        }
        
        /**
         * This method is called to display the how to use view
     	 * @param null
     	 * @return void
     	 * @since 1.0
         */
        public function about_view(){
            ?>
                <div id="screen-options-wrap" class="pads-about-wrap">
                    <p>
                        <strong><i><?php esc_html_e('Podamibe Advertisement Management', PAM_TEXT_DOMAIN);?></i></strong> <?php esc_html_e('is a FREE WordPress plugin Help you to display your', PAM_TEXT_DOMAIN);?> <strong> <?php esc_html_e('advertise', PAM_TEXT_DOMAIN);?> </strong> <?php esc_html_e('in your website easily. The plugin is responsive and feature rich which is built up to simplify the user needs.', PAM_TEXT_DOMAIN);?>
                    </p>
                    <p>
                        <?php esc_html_e('A perfect plugin to show your ads in bulk and individually. You can place your', PAM_TEXT_DOMAIN);?> <strong> <?php esc_html_e('ad', PAM_TEXT_DOMAIN);?> </strong> <?php esc_html_e('any where of your site wherever it is appropriate. The plugin is free but its great and easy to use. ', PAM_TEXT_DOMAIN);?><a href="#"><?php esc_html_e('Download Today!', PAM_TEXT_DOMAIN);?></a>
                    </p>
                    <p>
                        <i><?php esc_html_e('Exceptional quality products based services and the savings. Best IT solution company you will ever find, as ethics changes with technology.', PAM_TEXT_DOMAIN);?></i> (<a href="http://podamibenepal.com/know-us/"><?php esc_html_e('Podamibe Nepal');?></a> <a href="http://podamibenepal.com/team/"><?php esc_html_e('Team', PAM_TEXT_DOMAIN);?></a>)
                    </p>
                </div>
            <?php
        }
        
        
        /**
         * This method is called to save the data
     	 * @param null
     	 * @return void
     	 * @since 1.0
         */
        public function create(){
            if( isset( $_POST['settings_submit'] ) && !empty( $_POST ) ){
                $settings_datas = $_POST['settings'];
                
                foreach( $settings_datas as $key => $val ){
                    
                    if( is_array($val) ){
                        if( $key == 'margin' || $key == 'padding' || $key == 'border' ){
                            foreach( $val as $k => $v ){
                                if( is_numeric( $v ) || empty( $v ) ){
                                    $settings_datas[$key][$k] = sanitize_text_field($v);
                                }else{
                                    $settings_datas[$key][$k] = '';
                                }
                            }
                        }else{
                            foreach( $val as $k => $v ){
                                $settings_datas[$key][$k] = sanitize_text_field($v);
                            }
                        }
                    } else {
                        $settings_datas[$key] = sanitize_text_field($val);
                    }
                    
                }
                //delete_option(PAM_SETTING_NAME);
                update_option( PAM_SETTING_NAME, $settings_datas );
            }
        }
        
        /**
         * This method is generate array of style list
     	 * @param null
     	 * @return array();
     	 * @since 1.0
         */
        public function lineStyles(){
            $lineStyles = array('none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'initial', 'inherit');
            foreach($lineStyles as $lineStyle){
                $line_style[] = esc_html__( $lineStyle, PAM_TEXT_DOMAIN );
            }
            return $line_style;
        }
        
    }
}