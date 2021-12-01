<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-multistep-checkout
 * @subpackage woocommerce-multistep-checkout/public 
 */
if(!defined('WPINC')){	die; } 

if(!class_exists('THWMSC_Public')):
 
class THWMSC_Public {
	private $plugin_name;
	private $version;
	private $validator;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$wmsc_enabled = apply_filters('thwmsc_msc_enable', THWMSC_Utils::get_settings('enable_wmsc'));  
		if(!$wmsc_enabled){
			return;
		}
		
		$this->validator = new THWMSC_Public_Validation();
		
		add_action('after_setup_theme', array($this, 'define_public_hooks'));
	}

	public function enqueue_styles_and_scripts() {
		global $wp_scripts; 
		
		if(is_checkout()){
			$debug_mode = apply_filters('thwmsc_debug_mode', false);
			$suffix = $debug_mode ? '' : '.min';
			$jquery_version = isset($wp_scripts->registered['jquery-ui-core']->ver) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';

			$this->enqueue_styles($suffix, $jquery_version);
			$this->enqueue_scripts($suffix, $jquery_version);
		}
	}
	
	private function enqueue_styles($suffix, $jquery_version) {
		wp_register_style('select2', THWMSC_WOO_ASSETS_URL.'/css/select2.css');
		
		wp_enqueue_style('select2');
		wp_enqueue_style('dashicons');
		wp_enqueue_style('jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/'. $jquery_version .'/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('thwmsc-public-style', THWMSC_ASSETS_URL_PUBLIC . 'css/thwmsc-public'. $suffix .'.css', $this->version);

		$display_props = $this->get_display_props();
		
		if($display_props){
			$tab_panel_style = '';

			$tab_style = '';
			$tab_style_before = '';
			$tab_style_after = '';

			$tab_pt = '10';
			$tab_pb = '10';
			$tab_padding = ''; 

			$tab_style_active = '';
			$tab_before_style_active = '';
			$tab_after_style_active = '';

			$index_styles = '';
			$content_style = '';
			$tab_width_style = '';

			$step_icon_styles = '';
			$step_icon_styles_active = '';
			$step_icon_radius_unit = '';
			$iconindex_size = '';
			$imageindex_size = '';
			$imageindex_size = '';
			$icon_border_space = '';			

			$image_styles = '';

			$dashicon_yes = '';
			$dashicon_yes_responsive = '';
			
			$tab_align = isset($display_props['tab_align']) && $display_props['tab_align'] ? 'text-align:'.$display_props['tab_align'].';' : '';
			
			$tab_padding_unit = isset($display_props['tab_padding_unit']) && $display_props['tab_padding_unit'] ? $display_props['tab_padding_unit'] : 'px';
			$tab_height_unit = isset($display_props['tab_line_height_unit']) && $display_props['tab_line_height_unit'] ? $display_props['tab_line_height_unit'] : 'px';
			$tab_padding_style = isset($display_props['tab_padding']) && $display_props['tab_padding'] ? $display_props['tab_padding'] : array();

			if($tab_padding_style){
				$tab_pt = isset($tab_padding_style['tab_padding_top']) && $tab_padding_style['tab_padding_top'] ? $tab_padding_style['tab_padding_top'] : '10';
				$tab_pr = isset($tab_padding_style['tab_padding_right']) && $tab_padding_style['tab_padding_right'] ? $tab_padding_style['tab_padding_right'] : '25';
				$tab_pb = isset($tab_padding_style['tab_padding_bottom']) && $tab_padding_style['tab_padding_bottom'] ? $tab_padding_style['tab_padding_bottom'] : '10';
				$tab_pl = isset($tab_padding_style['tab_padding_left']) && $tab_padding_style['tab_padding_left'] ? $tab_padding_style['tab_padding_left'] : '25';

				$tab_padding  = 'padding-top:'.$tab_pt.$tab_padding_unit.';';
				$tab_padding .= 'padding-right:'.$tab_pr.$tab_padding_unit.';';
				$tab_padding .= 'padding-bottom:'.$tab_pb.$tab_padding_unit.';';
				$tab_padding .= 'padding-left:'.$tab_pl.$tab_padding_unit.';';

				$dashicon_yes .= 'top:'.$tab_pt.$tab_padding_unit.';';
				$dashicon_yes .= 'left:'.$tab_pl.$tab_padding_unit.';';
			}
			
			$tab_lh = isset($display_props['tab_line_height']) && $display_props['tab_line_height'] ? $display_props['tab_line_height'] : '32'; 
					
			$tab_h  = (int)$tab_lh+(int)$tab_pt+(int)$tab_pb; 				
			$arrow_height  = $tab_h/2;
			$index_height  = (int)$tab_lh; 
			$margin_right  = (int)$arrow_height/5; 
			$arrow_height .= $tab_height_unit; 
			$index_height .= $tab_height_unit;			
			$margin_right .= $tab_height_unit;	 		
			
			$tab_line_height = 'line-height:'.$tab_lh.$tab_height_unit.';';
			$tab_li_style = 'line-height:'.$tab_h.$tab_height_unit.';';
			
			$tab_label_style = $tab_padding.$tab_line_height;
			
			$tab_arrow_tab_style = 'margin-left: '.$arrow_height.';';
			
			$tab_arrow_style  = 'border-top-width: '.$arrow_height.';';
			$tab_arrow_style .= 'border-right-width: 0;';
			$tab_arrow_style .= 'border-bottom-width: '.$arrow_height.';';
			$tab_arrow_style .= 'border-left-width: '.$arrow_height.';';
			$tab_arrow_style .= 'margin-top: -'.$arrow_height.';';
			
			$vtab_arrow_style = 'width:calc(100% - '.$arrow_height.');';
			$mtab_arrow_style = 'width:calc(100% - '.$arrow_height.');';

			$index_styles .= 'width: '.$index_height.';';
			$index_styles .= 'height: '.$index_height.';';
			$index_styles .= 'line-height: '.((int)$tab_lh-2).$tab_height_unit.';';
			$tab_li_style .= 'margin-right: '.$margin_right.';';

			$dashicon_yes .= 'width: '.$index_height.';';
			$dashicon_yes .= 'height: '.$index_height.';';
			$dashicon_yes .= 'line-height: '.$index_height.';';
			//$tab_arrow_style_after = 'right: -'.$arrow_height.';';

			//Image Icon Styles start			
			$max_height = $tab_lh-4;
			$image_styles = 'width:'.$tab_lh.$tab_height_unit.';';
			//$image_styles .= 'max-height:'.$tab_lh.$tab_height_unit.';';
			$image_styles .= 'height:'.$tab_lh.$tab_height_unit.';';
			//Image Icon Styles end	
			
			if(isset($display_props['tab_panel_bg_color']) && $display_props['tab_panel_bg_color']){
				$tab_panel_style = 'background:'.$display_props['tab_panel_bg_color'].' !important;';
			}
			
			if(isset($display_props['step_bg_color']) && $display_props['step_bg_color']){
				$tab_style = 'background:'.$display_props['step_bg_color'].' !important;';
				$tab_style_after = 'border-left-color:'.$display_props['step_bg_color'].' !important;';
				$tab_style_before = 'border-top-color:'.$display_props['step_bg_color'].' !important;'.'border-bottom-color:'.$display_props['step_bg_color'].' !important;';
			}
			if(isset($display_props['step_bg_color_active']) && $display_props['step_bg_color_active']){
				$tab_style_active = 'background:'.$display_props['step_bg_color_active'].' !important;';
				$tab_after_style_active = 'border-left-color:'.$display_props['step_bg_color_active'].' !important;';
				$tab_before_style_active = 'border-top-color:'.$display_props['step_bg_color_active'].' !important;'.'border-bottom-color:'.$display_props['step_bg_color_active'].' !important;';
			}
			
			if(isset($display_props['step_text_color']) && $display_props['step_text_color']){
				$tab_style .= $tab_style ? ' color:'.$display_props['step_text_color'].'' : 'color:'.$display_props['step_text_color'].'';
				$tab_style .= ' !important;';
			}
			if(isset($display_props['step_text_font_size']) && $display_props['step_text_font_size']){
				$step_text_font_size_unit = isset($display_props['step_text_font_size_unit']) ? $display_props['step_text_font_size_unit'] : '';
				$step_font_size = $display_props['step_text_font_size'] . $step_text_font_size_unit;
				$responsive_font_size = ((int)$display_props['step_text_font_size']-2) . $step_text_font_size_unit;

				$tab_style .= $tab_style ? ' font-size:'.$step_font_size.'' : 'font-size:'.$step_font_size.'';
				$tab_style .= ' !important;';
			}
			if(isset($display_props['step_text_font_weight']) && $display_props['step_text_font_weight']){
				$tab_style .= $tab_style ? ' font-weight:'.$display_props['step_text_font_weight'].'' : 'font-weight:'.$display_props['step_text_font_weight'].'';
				$tab_style .= ' !important;';
			}
			if(isset($display_props['step_text_transform']) && $display_props['step_text_transform']){
				$tab_style .= $tab_style ? ' text-transform:'.$display_props['step_text_transform'].'' : 'text-transform:'.$display_props['step_text_transform'].'';
				$tab_style .= ' !important;';
			}
			
			if(isset($display_props['step_text_color_active']) && $display_props['step_text_color_active']){
				$tab_style_active .= $tab_style_active ? ' color:'.$display_props['step_text_color_active'].'' : 'color:'.$display_props['step_text_color_active'].'';
				$tab_style_active .= ' !important;';
			}

			//Start Icon styles

			if(isset($display_props['icon_border_space']) && is_numeric($display_props['icon_border_space'])){
				$icon_border_space_unit = isset($display_props['icon_border_space_unit']) && $display_props['icon_border_space_unit'] ? $display_props['icon_border_space_unit'] : 'px';
				$icon_border_space = isset($display_props['icon_border_space']) && $display_props['icon_border_space'] ? $display_props['icon_border_space'] : '';
				$step_icon_styles .= 'padding: ' . $icon_border_space . $icon_border_space_unit .';';
				$index_styles .= 'line-height: '.((int)$tab_lh-2-(2*$icon_border_space)).$tab_height_unit.';';				
			}

			if(isset($display_props['step_icon_radius']) && is_numeric($display_props['step_icon_radius'])){
				$step_icon_radius_unit = isset($display_props['step_icon_radius_unit']) && $display_props['step_icon_radius_unit'] ? $display_props['step_icon_radius_unit'] : 'px';
			}		
			$step_icon_radius_value =  isset($display_props['step_icon_radius']) && $display_props['step_icon_radius'] ? $display_props['step_icon_radius'] : '0';
			$icon_border_radius = 'border-radius:'.$step_icon_radius_value. $step_icon_radius_unit .' !important;';
			$step_icon_styles .= $icon_border_radius;
			$dashicon_yes .= $icon_border_radius;

			$checkmark_background =  isset($display_props['step_tickmark_background']) && $display_props['step_tickmark_background'] ? 'background: ' . $display_props['step_tickmark_background'] . ';' : '';
			$dashicon_yes .= $checkmark_background;

			$checkmark_color =  isset($display_props['step_tickmark_color']) && $display_props['step_tickmark_color'] ? 'color: ' . $display_props['step_tickmark_color'] . ';' : '';
			$dashicon_yes .= $checkmark_color;

			$checkmark_border =  isset($display_props['step_tickmark_border']) && $display_props['step_tickmark_border'] ? 'border-color: ' . $display_props['step_tickmark_border'] . ';' : '';
			$dashicon_yes .= $checkmark_border;

			if(isset($display_props['step_icon_border_color']) && $display_props['step_icon_border_color']){
				$step_icon_styles .= $step_icon_styles ? ' border-color:'.$display_props['step_icon_border_color'].'' : 'border-color:'.$display_props['step_icon_border_color'].'';
				$step_icon_styles .= ' !important;';
			}
			if(isset($display_props['step_icon_background_color']) && $display_props['step_icon_background_color']){
				$step_icon_styles .= 'background:' . $display_props['step_icon_background_color'] . '!important;';
			}
			if(isset($display_props['step_icon_font_color']) && $display_props['step_icon_font_color']){
				$step_icon_styles .= 'color:' . $display_props['step_icon_font_color'] . '!important;';
			}
			if(isset($display_props['step_icon_font_color_active']) && $display_props['step_icon_background_color_active']){
				$step_icon_styles_active .= 'color:' . $display_props['step_icon_font_color_active'] . '!important;';
			}
			if(isset($display_props['step_icon_border_color_active']) && $display_props['step_icon_border_color_active']){
				$step_icon_styles_active .= 'border-color:'. $display_props['step_icon_border_color_active'] . '!important;';
			}		
			if(isset($display_props['step_icon_background_color_active']) && $display_props['step_icon_background_color_active']){
				$step_icon_styles_active .= 'background:' . $display_props['step_icon_background_color_active'] . '!important;';
			}

			if(isset($display_props['icon_height_width']) && $display_props['icon_height_width']){
				$icon_height_width_unit = isset($display_props['icon_height_width_unit']) && $display_props['icon_height_width_unit'] ? $display_props['icon_height_width_unit'] : 'px';
				$icon_height_width = $display_props['icon_height_width'].$icon_height_width_unit;

				$iconindex_size .= 'width:' . $icon_height_width . ';';
				$iconindex_size .= 'height:' . $icon_height_width . ';';
				$iconindex_size .= 'line-height:' . ($display_props['icon_height_width']-2-(2*$icon_border_space)).$icon_height_width_unit . ';';
				$imageindex_size .= 'width:' . $icon_height_width . ';';
				//$imageindex_size .= 'max-height:' . $icon_height_width . ';';
				$imageindex_size .= 'height:' . $icon_height_width . ';';

				if((int)$display_props['icon_height_width'] < $tab_lh){			
					$index_styles = $iconindex_size;
					$image_styles = $imageindex_size;		
					$tab_lh = $display_props['icon_height_width'];
								
					$checkmark_style = 'top: '. (($tab_h-$tab_lh)/2) . $tab_height_unit . '!important;';
					$checkmark_style .= 'left: '. $tab_pl . $tab_padding_unit .'!important;';
					
					$dashicon_yes = $iconindex_size . $icon_border_radius . $checkmark_background . $checkmark_style . $checkmark_border . $checkmark_color;
					$dashicon_yes .= 'line-height:' . $icon_height_width;

				}
			}
			$dash_resposive_top = 'top: '. (($tab_h-32)/2) . $tab_height_unit . '!important;';
			$dashicon_yes_responsive .= $dash_resposive_top;

			//End Icons Styles

			//Button styles Start
			$button_padding = '';
			$button_styles = '';
			$button_styles_hover = ''; 

			if(isset($display_props['button_style_active']) && ($display_props['button_style_active']) == 'yes'){
				$button_padding_unit = isset($display_props['button_padding_unit']) && $display_props['button_padding_unit'] ? $display_props['button_padding_unit'] : 'px';

				$button_padding_style = isset($display_props['button_padding']) && $display_props['button_padding'] ? $display_props['button_padding'] : array();

				if($button_padding_style){
					$button_pt = isset($button_padding_style['button_padding_top']) && $button_padding_style['button_padding_top'] ? $button_padding_style['button_padding_top'] : '10';
					$button_pr = isset($button_padding_style['button_padding_right']) && $button_padding_style['button_padding_right'] ? $button_padding_style['button_padding_right'] : '22';
					$button_pb = isset($button_padding_style['button_padding_bottom']) && $button_padding_style['button_padding_bottom'] ? $button_padding_style['button_padding_bottom'] : '10';
					$button_pl = isset($button_padding_style['button_padding_left']) && $button_padding_style['button_padding_left'] ? $button_padding_style['button_padding_left'] : '22';

					$button_padding  = 'padding-top:'.$button_pt.$button_padding_unit.';';
					$button_padding .= 'padding-right:'.$button_pr.$button_padding_unit.';';
					$button_padding .= 'padding-bottom:'.$button_pb.$button_padding_unit.';';
					$button_padding .= 'padding-left:'.$button_pl.$button_padding_unit.';';
					$button_styles  .= $button_padding;
				}				
				
				if(isset($display_props['button_text_font_size']) && $display_props['button_text_font_size']){
					$button_text_font_size_unit = isset($display_props['button_text_font_size_unit']) ? $display_props['button_text_font_size_unit'] : '';
					$button_styles .= 'font-size:'.$display_props['button_text_font_size']. $button_text_font_size_unit .' !important;';
				}
				if(isset($display_props['button_text_font_color']) && $display_props['button_text_font_color']){
					$button_styles .= 'color:'.$display_props['button_text_font_color'].' !important;';
				}
				if(isset($display_props['button_text_font_color_hover']) && $display_props['button_text_font_color_hover']){
					$button_styles_hover .= 'color:'.$display_props['button_text_font_color_hover'].' !important;';
				}
				if(isset($display_props['button_bg_color']) && $display_props['button_bg_color']){
					$button_styles .= 'background-color:'.$display_props['button_bg_color'].' !important;';
				}
				if(isset($display_props['button_bg_color_hover']) && $display_props['button_bg_color_hover']){
					$button_styles_hover .= 'background-color:'.$display_props['button_bg_color_hover'].' !important;';
				}				
				if(isset($display_props['button_border_width']) && $display_props['button_border_width']){
					$button_border_width_unit = isset($display_props['button_border_width_unit']) ? $display_props['button_border_width_unit'] : '';
					$button_styles .= 'border-width:'.$display_props['button_border_width']. $button_border_width_unit .' !important;';
				}
				if(isset($display_props['button_border_color']) && $display_props['button_border_color']){
					$button_styles .= 'border-color:'.$display_props['button_border_color'].' !important;';
				}
				if(isset($display_props['button_border_style']) && $display_props['button_border_style']){
					$button_styles .= 'border-style:'.$display_props['button_border_style'].' !important;';
				}
				if(isset($display_props['button_border_radius']) && $display_props['button_border_radius']){
					$button_border_radius_unit = isset($display_props['button_border_radius_unit']) ? $display_props['button_border_radius_unit'] : '';
					$button_styles .= 'border-radius:'.$display_props['button_border_radius']. $button_border_radius_unit .' !important;';
				}				
			}
			//Button styles End

			//Width style for vertical step
			if(isset($display_props['thwmsc_layout']) && (($display_props['thwmsc_layout'] == 'thwmsc_vertical_arrow' || $display_props['thwmsc_layout'] == 'thwmsc_vertical_box'))){
				$tab_float = '';				

				if(isset($display_props['tab_align']) && $display_props['tab_align']){
					if($display_props['tab_align'] == 'right'){
						if($display_props['thwmsc_layout'] == 'thwmsc_vertical_arrow'){
							$tab_arrow_style .= 'left: auto;
						    right: 100%;
						    border-right-width:'.$arrow_height.';
						    border-right-color:'.$display_props['step_bg_color'].';
						    border-left-width: 0px;';
						    $tab_after_style_active .= 'border-right-color: '.$display_props['step_bg_color_active'].'!important;'; 						
						}

						$tab_float = 'float:'.$display_props['tab_align'].';';
						$vtab_arrow_style .= 'margin-left:'.$arrow_height.'!important;';						
						$tab_li_style .= 'margin-left:'.$margin_right.';margin-right:0px;';
					}					
				}
				if(isset($display_props['tab_vertical_text_align']) && $display_props['tab_vertical_text_align']){
					$tab_align = 'text-align:'.$display_props['tab_vertical_text_align'].';';
				}
				if(isset($display_props['tab_width']) && $display_props['tab_width']){
					$tab_width_style .= 'width:'.$display_props['tab_width'].';'.$tab_float;
				}				
				if(isset($display_props['content_width']) && $display_props['content_width']){
					$content_style .= 'width:'.$display_props['content_width'].';'.$tab_float;
				}
			}
			//End Width style for vertical step

			// Vertical Responsive Styles
			$vertical_label_responsive = '
						padding: 5px 10px;
					    line-height: 20px;
					    padding: 5px 10px;
					    box-sizing: border-box;
					    text-align: center;
					    font-size: 14px;
					    word-wrap: break-word;
					   	width: 100%;
					    max-width: 100%;';
			$vertical_icon_responsive = '
						margin-right: 0px;
						display:block;
						margin:0 auto;';
			$vertical_dashicon = '
						left: 0px !important;
					    right: 0px;
					    margin: 0 auto;
					    top: 5px !important;';

			$vertical_li_responsive = 'line-height: 40px;';
			$vertical_arrow_responsive = 'display: none;';
			$vertical_arrow_width_responsive = 'width: 100%; margin-left:0px !important;';

			// End Vertical Responsive Styles

			// Start force Accordion
			$accordion_style = $tab_style . $tab_line_height;
			$time_line_force_accordion = $dashicon_yes_responsive . 'right: auto;left:25px !important;';
			// End force Accordion				

            $plugin_style = "
            		.thwmsc-layout-left ul.thwmsc-tabs{ $tab_width_style }
            		.thwmsc-layout-left .thwmsc-tab-panel-wrapper{ $content_style }
                    ul.thwmsc-tabs{ $tab_align }    
                    ul.thwmsc-tabs li{ $tab_li_style }
                    li.thwmsc-tab a{ $tab_style }                       
                    li.thwmsc-tab a:before { $tab_style_before }
                    li.thwmsc-tab a:after { $tab_style_after }
                                         
                    li.thwmsc-tab a.active { $tab_style_active }
                    li.thwmsc-tab a.active:before { $tab_before_style_active }
                    li.thwmsc-tab a.active:after { $tab_after_style_active }
                   
					/*.thwmsc-tab-panels{ $tab_panel_style }*/
					.thwmsc-tab-panel{ $tab_panel_style }
					/*ul.thwmsc-tabs span.thwmsc-tab-label{ $tab_label_style }*/
					span.thwmsc-tab-label{ $tab_label_style }

					.thwmsc-arrows ul.thwmsc-tabs li a{ $tab_arrow_tab_style }
					.thwmsc-arrows ul.thwmsc-tabs li a:before, .thwmsc-arrows ul.thwmsc-tabs li a:after{ $tab_arrow_style }
					.thwmsc-tab-icon{ $index_styles }
					.thwmsc-layout-left.thwmsc-arrows ul.thwmsc-tabs li a{ $vtab_arrow_style }
					.thwmsc-buttons .thwmsc-btn { $button_styles }
					.thwmsc-buttons .thwmsc-btn:hover { $button_styles_hover }

					.thwmsc-img-icon{ $image_styles }					
					.thwmsc-index{ $step_icon_styles }
					#thwmsc_wrapper a.active .thwmsc-img-icon, #thwmsc_wrapper a.active .thwmsc-tab-icon{ $step_icon_styles_active }
					.thwmsc-wrapper span.dashicons-yes{ $dashicon_yes }

					@media only screen and (max-width: 560px) {
						.thwmsc-arrows ul.thwmsc-tabs li a{ $mtab_arrow_style }		
						.thwmsc-mobile-accordion .thwmsc-accordion-label{ $accordion_style }
						.thwmsc-mobile-accordion .thwmsc-accordion-label.active{ $tab_style_active }
						.thwmsc-wrapper span.dashicons-yes{ $dashicon_yes_responsive }
						.thwmsc-wrapper.thwmsc-layout-time-line .thwmsc-accordion-label span.dashicons.dashicons-yes{ $time_line_force_accordion }
						.thwmsc-layout-time-line.thwmsc_title_above .thwmsc-accordion-label .thwmsc-index { margin-top:0px !important; }
						.thwmsc-layout-time-line.thwmsc_title_above .thwmsc-accordion-label span.dashicons-yes{ margin-top:0px !important;}
					}";

			if(isset($display_props['mobile_accordion']) && !$display_props['mobile_accordion']){
				$plugin_style .= "
					@media only screen and (max-width: 560px) {
						.thwmsc-layout-left span.thwmsc-tab-label{ $vertical_label_responsive }
						.thwmsc-layout-left .thwmsc-index{ $vertical_icon_responsive }
						.thwmsc-layout-left ul.thwmsc-tabs li{ $vertical_li_responsive }
						.thwmsc-layout-left.thwmsc-arrows ul.thwmsc-tabs li a { $vertical_arrow_width_responsive }
						.thwmsc-layout-left.thwmsc-arrows ul.thwmsc-tabs li a:after { $vertical_arrow_responsive }
						.thwmsc-layout-left span.dashicons-yes { $vertical_dashicon }						
					}";
			}

			//Time Line Styles
			if(isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_time_line_step')){

				$step_text_color = (isset($display_props['step_text_color']) && $display_props['step_text_color']) ? 'color :'.$display_props['step_text_color'].'!important;' : '';

				$step_text_color_active = isset($display_props['step_text_color_active']) && $display_props['step_text_color_active'] ? 'color :'.$display_props['step_text_color_active'].'!important;' : '';

				$line_icon_color = isset($display_props['step_bg_color']) && $display_props['step_bg_color'] ? 'background :'.$display_props['step_bg_color'].'!important;' : '';

				//$label_margin = 'margin-top : '.$index_height .';';		
				if($tab_lh > 32){
					$index_height = '32'.$tab_height_unit;
				}		
				$label_margin = 'padding-top : '.$index_height .';';				
				$tab_icon_margin = 'margin-top : -'. ($tab_lh/2+2) . $tab_height_unit . ';';
				$tab_img_margin = 'margin-top : -'. ($tab_lh/2+2) . $tab_height_unit . ';';

				$responsive_margin = 'margin-left : -'. ($tab_lh/2 + 2) . $tab_height_unit . ';';
				$responsive_line_height = ((int)$tab_lh-12).$tab_height_unit;
				
				$responsive_styles = 'padding-top : 15px; line-height:'.$responsive_line_height.';font-size:'.$responsive_font_size.'';
				//$dashicon_yes .= 'left: 0px; right: 0px; margin: 0 auto;';

				if(isset($display_props['icon_height_width']) && $display_props['icon_height_width']){
					$icon_height_width_unit = isset($display_props['icon_height_width_unit']) && $display_props['icon_height_width_unit'] ? $display_props['icon_height_width_unit'] : 'px';
					$icon_height_width = $display_props['icon_height_width'].$icon_height_width_unit;

					$dashicon_top_titlebelow = 'top:-' . ($display_props['icon_height_width']/2+2) . $icon_height_width_unit . '!important' ;
				}



				$dashicons_responsive = 'left: 0px !important; right: 0px; margin: 0 auto; top: 0px; //top: -18px !important;';	
 
				$tab_label_style = $step_text_color . $label_margin;				 
				//$tab_icon_style = $line_icon_color . $tab_icon_margin;	
				$tab_icon_style =  $tab_icon_margin;
				$tab_image_style = $line_icon_color . $tab_img_margin;	

				$line_icon_color_active = isset($display_props['step_bg_color_active']) && $display_props['step_bg_color_active'] ? 'background :'.$display_props['step_bg_color_active'].'!important;' : '';

				$line_border_color = isset($display_props['step_bg_color']) && $display_props['step_bg_color'] ? 'border-top :4px solid '.$display_props['step_bg_color'].';' : '';			
				$line_border_color_active = isset($display_props['step_bg_color_active']) && $display_props['step_bg_color_active'] ? 'border-top :4px solid '.$display_props['step_bg_color_active'].';' : '';

				$line_border_bottom_color = isset($display_props['step_bg_color']) && $display_props['step_bg_color'] ? 'border-bottom :4px solid '.$display_props['step_bg_color'].';' : '';
				$line_border_bottom_color_active = isset($display_props['step_bg_color_active']) && $display_props['step_bg_color_active'] ? 'border-bottom :4px solid '.$display_props['step_bg_color_active'].';' : '';

				$line_border_left_color = isset($display_props['step_bg_color']) && $display_props['step_bg_color'] ? 'border-left :4px solid '.$display_props['step_bg_color'].'!important;' : '';

				$line_border_left_color_active = isset($display_props['step_bg_color_active']) && $display_props['step_bg_color_active'] ? 'border-left :4px solid '.$display_props['step_bg_color_active'].'!important;' : '';

				$steps = THWMSC_Utils::get_step_settings_public();
				$count = sizeof($steps);
				$width_time_line = 'width:'. 100/$count .'%';
				$line_height_closed = 'line-height: ' . ($tab_lh + 20) . $tab_height_unit;				
				$min_height_dot = 'min-height: ' . ($tab_lh + 16) . $tab_height_unit;				
				$margin_top_dot = 'margin-top: ' . ($tab_lh + 17) . $tab_height_unit;	
				$margin_top = 'margin-top: ' . (($tab_lh/2) + 32) . $tab_height_unit;			
				$dash_icon_title_above = 'top: ' . (($tab_lh/2) + 32) . $tab_height_unit . '!important';	
				$dash_icon_title_above_responsive = 'top: 0px !important';	
				$tab_label_style = 'line-height:' . $tab_lh . $tab_height_unit;

				$plugin_style .= "
					.thwmsc-layout-time-line ul.thwmsc-tabs li{ $width_time_line }
					.thwmsc-layout-time-line ul.thwmsc-tabs li a {  $line_border_color }
					.thwmsc-layout-time-line ul.thwmsc-tabs li a.active { $line_border_color_active }
					.thwmsc-layout-time-line.thwmsc_title_above ul.thwmsc-tabs li a {  $line_border_bottom_color }				
					.thwmsc-layout-time-line.thwmsc_title_above ul.thwmsc-tabs li a.active { $line_border_bottom_color_active }
					.thwmsc-layout-time-line ul.thwmsc-tabs span.thwmsc-tab-label { $tab_label_style }
					.thwmsc-layout-time-line ul.thwmsc-tabs .active span.thwmsc-tab-label { $step_text_color_active }
					.thwmsc-layout-time-line span.thwmsc-tab-icon { $tab_icon_style }
					.thwmsc-layout-time-line .thwmsc-img-icon { $tab_image_style }
					.thwmsc-layout-time-line a.active .thwmsc-img-icon { $line_icon_color_active }
					.thwmsc-layout-time-line ul.thwmsc-tabs li a.active .thwmsc-tab-icon { $line_icon_color_active }
					.thwmsc-wrapper span.dashicons-yes{ $dashicons_responsive }
					.thwmsc-wrapper.thwmsc_title_below span.dashicons-yes{ $dashicon_top_titlebelow }

					.thwmsc-layout-time-line.thwmsc_normal.thwmsc_title_above .thwmsc-index, .thwmsc-layout-time-line.thwmsc_end_closed.thwmsc_title_above .thwmsc-index{ $margin_top }
					.thwmsc-layout-time-line.thwmsc_title_above span.dashicons-yes{ $dash_icon_title_above }
					.thwmsc-layout-time-line.thwmsc_end_closed.thwmsc_title_above ul.thwmsc-tabs li a.first{ $min_height_dot }
					.thwmsc-layout-time-line.thwmsc_end_closed.thwmsc_title_above ul.thwmsc-tabs li a.last{ $min_height_dot }
					.thwmsc-layout-time-line.thwmsc_end_closed.thwmsc_title_above ul.thwmsc-tabs li { $line_height_closed }

					/*.thwmsc-layout-time-line.thwmsc_open_no_icon.thwmsc_title_above ul.thwmsc-tabs .thwmsc-index{ $margin_top_dot }*/
					.thwmsc-layout-time-line.thwmsc_open_no_icon.thwmsc_title_above ul.thwmsc-tabs .thwmsc-index{ $margin_top }					
					.thwmsc-layout-time-line.thwmsc_open_no_icon.thwmsc_title_above ul.thwmsc-tabs li { $line_height_closed }

					.thwmsc-layout-time-line.thwmsc_end_closed_no_icon.thwmsc_title_above ul.thwmsc-tabs li a.first{ $min_height_dot }
					.thwmsc-layout-time-line.thwmsc_end_closed_no_icon.thwmsc_title_above ul.thwmsc-tabs li a.last{ $min_height_dot }

					/*.thwmsc-layout-time-line.thwmsc_end_closed_no_icon.thwmsc_title_above ul.thwmsc-tabs .thwmsc-index{ $margin_top_dot }*/

					.thwmsc-layout-time-line.thwmsc_end_closed_no_icon.thwmsc_title_above ul.thwmsc-tabs .thwmsc-index{ $margin_top }
					.thwmsc-layout-time-line.thwmsc_end_closed_no_icon.thwmsc_title_above ul.thwmsc-tabs li { $line_height_closed }
					.thwmsc-layout-time-line.thwmsc_normal.thwmsc_title_above ul.thwmsc-tabs li { $line_height_closed }

					@media only screen and (max-width: 560px) {
						/*.thwmsc-layout-time-line ul.thwmsc-tabs li a { $line_border_left_color }
						.thwmsc-layout-time-line ul.thwmsc-tabs li a.active { $line_border_left_color_active }
						.thwmsc-layout-time-line span.thwmsc-tab-icon, .thwmsc-layout-time-line .thwmsc-img-icon { $responsive_margin }*/
						.thwmsc-layout-time-line ul.thwmsc-tabs span.thwmsc-tab-label{ $responsive_styles }
						.thwmsc-wrapper span.dashicons-yes{ $dashicons_responsive }
						.thwmsc-layout-time-line.thwmsc_title_above span.dashicons-yes{ $dash_icon_title_above_responsive }
					}
					";
			}
			//Time Line Styles End

			//Accordion Styles Start
			if(isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_accordion_tab')){		
				$accordion_style =  $tab_style . $tab_line_height;
				$accordion_style .= 'display:block;';

				$plugin_style .= "
					.thwmsc-accordion-label{ $accordion_style }
					.thwmsc-accordion-label.active{ $tab_style_active }
					.thwmsc-accordion-step .thwmsc-tab-panel{ $tab_panel_style }
				";
			}
			//Accordion Styles End


			$advanced_props = $this->get_advanced_props();
			if (isset($display_props['enable_completed_tab_bg']) && ($display_props['enable_completed_tab_bg'] == 'yes')) {
				$completed_tab_props = $this->get_display_props();
			} 
			if (isset($advanced_props['enable_completed_tab_bg']) && ($advanced_props['enable_completed_tab_bg'] == 'yes')) {
				$completed_tab_props = $this->get_advanced_props();
			}
			//Simple dot format style
			if(isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_simple_dot_format')){
				$dot_margin = '';
				$dot_padding_style = isset($display_props['dot_padding']) && $display_props['dot_padding'] ? $display_props['dot_padding'] : array();
				if ($dot_padding_style) {
					$dot_pt = isset($dot_padding_style['dot_padding_top']) && $dot_padding_style['dot_padding_top'] ? $dot_padding_style['dot_padding_top'] : '10';
					$dot_pr = isset($dot_padding_style['dot_padding_right']) && $dot_padding_style['dot_padding_right'] ? $dot_padding_style['dot_padding_right'] : '15';
					$dot_pb = isset($dot_padding_style['dot_padding_bottom']) && $dot_padding_style['dot_padding_bottom'] ? $dot_padding_style['dot_padding_bottom'] : '10';
					$dot_pl = isset($dot_padding_style['dot_padding_left']) && $dot_padding_style['dot_padding_left'] ? $dot_padding_style['dot_padding_left'] : '15';
					$dot_margin  = 'margin-top:'.$dot_pt.$tab_padding_unit.';';
					$dot_margin .= 'margin-right:'.$dot_pr.$tab_padding_unit.';';
					$dot_margin .= 'margin-bottom:'.$dot_pb.$tab_padding_unit.';';
					$dot_margin .= 'margin-left:'.$dot_pl.$tab_padding_unit.';';
				}
				$dot_background = isset($display_props['dot_format_bg_color']) ? 'background-color:'.$display_props['dot_format_bg_color'].'!important;' : '';
				$dot_background_active = isset($display_props['dot_format_bg_color_active']) ? 'background-color:'.$display_props['dot_format_bg_color_active'].'!important;' : '';
				$dot_background_completed = isset($display_props['dot_format_bg_color_completed']) ? 'background-color:'.$display_props['dot_format_bg_color_completed'].'!important;' : '';

				$dot_color = isset($display_props['dot_format_dot_color']) ? 'background-color:'.$display_props['dot_format_dot_color'].';' : '';
				$dot_color_active = isset($display_props['dot_format_dot_color_active']) ? 'background-color:'.$display_props['dot_format_dot_color_active'].';' : '';
				$dot_color_completed = isset($display_props['dot_format_dot_color_completed']) ? 'background-color:'.$display_props['dot_format_dot_color_completed'].';' : '';

				$step_text_font_size_unit = isset($display_props['step_text_font_size_unit']) ? $display_props['step_text_font_size_unit'] : '';
				$dot_height_width = isset($display_props['dot_width_height']) ? 'height:'.$display_props['dot_width_height'].$step_text_font_size_unit.';width:'.$display_props['dot_width_height'].$step_text_font_size_unit.';' : '';
				$plugin_style .= "
					.thwmsc-simple-dot-format li.thwmsc-tab a{ $dot_background }
					.thwmsc-simple-dot-format li.thwmsc-tab a.active{ $dot_background_active }
					.thwmsc-simple-dot-format li.thwmsc-tab a.thwmsc-completed{ $dot_background_completed }
					.thwmsc-simple-dot-format li.thwmsc-tab .thwmsc-dot-icon{ $dot_margin;$dot_color;$dot_height_width }
					.thwmsc-simple-dot-format li.thwmsc-tab .active .thwmsc-dot-icon{ $dot_color_active }
					.thwmsc-simple-dot-format li.thwmsc-tab .thwmsc-completed .thwmsc-dot-icon{ $dot_color_completed }";
			}
			//Simple dot format style end

			//Looped box layout style
			if(isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_looped_box_layout')) {
				$border_color = isset($display_props['step_bg_color_active']) && $display_props['step_bg_color_active'] ? 'border-bottom : 2px solid '.$display_props['step_bg_color_active'].';' : '';
				$plugin_style .= "
					.thwmsc-looped-box-layout li.thwmsc-tab{ $border_color }
				";
			}
			//Looped box layout style end

			//Tab format style
			if(isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_tab_format')) {
				if (isset($display_props['border_color_tab_format']) && $display_props['border_color_tab_format']) {
					$border_color = $display_props['border_color_tab_format'];
					$content_border = 'border : 1px solid '.$border_color.';';
					$tab_border = 'border-top : 1px solid '.$border_color.';
								   border-bottom : 1px solid '.$border_color.';
								   border-left : 1px solid '.$border_color.';
								   border-right : 1px solid '.$border_color.';';

					$active_bottom_border = (isset($display_props['step_bg_color_active']) && $display_props['step_bg_color_active']) ? 'border-bottom : 1px solid '.$display_props['step_bg_color_active'].';' : '';
					$active_bottom_margin = 'margin-bottom : -1px';
					$plugin_style .= "
						.thwmsc-tab-format .thwmsc-tab-panel-wrapper { $content_border }
						.thwmsc-tab-format li.thwmsc-tab { $tab_border }
						.thwmsc-tab-format li.thwmsc-tab.tab-active { $active_bottom_border }
						.thwmsc-tab-format ul.thwmsc-tabs li { $active_bottom_margin }
					";
				}
			}
			//Tab format style end

			//Custom separator style
			if(isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_custom_separator')) {
				if ($tab_padding_style) {
					$custom_top = $tab_pt+8;
					$arrow_top  = 'top:'.$custom_top.$tab_padding_unit.';';
					$plugin_style .= "
						.thwmsc-custom-separator .dashicons-arrow-right-alt2 { $arrow_top }
					";
				}
			}
			//Custom separator style end

			//Passed step styling Start
			if(isset($completed_tab_props['enable_completed_tab_bg']) && ($completed_tab_props['enable_completed_tab_bg'] == 'yes')){
				$passed_background = isset($completed_tab_props['completed_tab_bg_color']) && $completed_tab_props['completed_tab_bg_color'] ? $completed_tab_props['completed_tab_bg_color'].'!important;' : '';
				$passed_text_color = isset($completed_tab_props['completed_tab_text_color']) && $completed_tab_props['completed_tab_text_color'] ? 'color :'.$completed_tab_props['completed_tab_text_color'].'!important;' : '';
				$passed_style = 'background :' . $passed_background . $passed_text_color;

				$passed_after_style = 'border-left-color: ' . $passed_background;
				$passed_before_style = 'border-top-color: ' . $passed_background .'; border-bottom-color:' . $passed_background;

				$time_line_border_top = 'border-top-color: ' . $passed_background;
				$time_line_border_bottom = 'border-bottom-color: ' . $passed_background;
  
				$plugin_style .= "
					/*li.thwmsc-tab a.thwmsc-finished-step { $passed_style }*/
					li.thwmsc-tab a.thwmsc-completed { $passed_style }
					li.thwmsc-tab a.thwmsc-completed:before{ $passed_before_style }
					li.thwmsc-tab a.thwmsc-completed:after{ $passed_after_style }
					.thwmsc-tab-panel a.thwmsc-completed { $passed_style }

					.thwmsc-layout-time-line ul.thwmsc-tabs li a.thwmsc-completed { $time_line_border_top }
					.thwmsc-layout-time-line.thwmsc_title_above ul.thwmsc-tabs li a.thwmsc-completed { $time_line_border_bottom }
				";
			}
			//Passed step styling End

			//Button display none for next and previous
			$last_next_button = isset($advanced_props['hide_last_step_next']) && $advanced_props['hide_last_step_next'] == 'yes' ? 'display : none !important;' : '';
			$first_previous_button = isset($advanced_props['hide_first_step_prev']) && $advanced_props['hide_first_step_prev'] == 'yes' ? 'display : none !important;' : '';

			$plugin_style .= "
				.thwmsc-buttons .prev-first{ $first_previous_button }
				.thwmsc-buttons .next-last{ $last_next_button }
			"; 
			//Button display none end

			//Hide toggle link of coupon and login form
			$steps = THWMSC_Utils::get_step_settings_public();
			$is_login_enabled = is_array($steps) && array_key_exists('login', $steps) ? true : false;
			$is_coupon_enabled = is_array($steps) && array_key_exists('coupon', $steps) ? true : false;

			$enable_login_step = isset($advanced_props['enable_login_step']) && ($advanced_props['enable_login_step'] == 'yes') ? true : false;
			$enable_coupen_step = isset($advanced_props['enable_coupen_step']) && ($advanced_props['enable_coupen_step'] == 'yes') ? true : false;

			$login_form_on_load = $is_login_enabled && $enable_login_step && isset($advanced_props['login_form_on_load']) && $advanced_props['login_form_on_load'] == 'yes' ? true : false;
			$coupon_form_on_load = $is_coupon_enabled && $enable_coupen_step && isset($advanced_props['coupon_form_on_load']) && $advanced_props['coupon_form_on_load'] == 'yes' ? true : false;

			if($login_form_on_load){
				$plugin_style .= "
					.woocommerce-form-login-toggle{
						display: none;
					}
				";
			}

			if($coupon_form_on_load){
				$plugin_style .= "
					.woocommerce-form-coupon-toggle{
						display: none;
					}
				";
			}

            wp_add_inline_style( 'thwmsc-public-style', $plugin_style );
        }
	}

	private function enqueue_scripts($suffix, $jquery_version) {
		$display_props = $this->get_display_props();
		$advanced_props = $this->get_advanced_props();
		$steps = THWMSC_Utils::get_step_settings_public();

		$is_login_enabled = is_array($steps) && array_key_exists('login', $steps) ? true : false;
		$is_coupon_enabled = is_array($steps) && array_key_exists('coupon', $steps) ? true : false;

		$enable_step_forward = isset($display_props['enable_step_forward']) && $display_props['enable_step_forward'] == 'yes' ? true : false;
		$disable_ajax_validation = isset($display_props['disable_validation']) && $display_props['disable_validation'] == 'yes' ? true : false;

		$enable_login_step = isset($advanced_props['enable_login_step']) && ($advanced_props['enable_login_step'] == 'yes') ? true : false;
		$enable_coupen_step = isset($advanced_props['enable_coupen_step']) && ($advanced_props['enable_coupen_step'] == 'yes') ? true : false;

		$login_form_on_load = $is_login_enabled && $enable_login_step && isset($advanced_props['login_form_on_load']) && $advanced_props['login_form_on_load'] == 'yes' ? true : false;
		$coupon_form_on_load = $is_coupon_enabled && $enable_coupen_step &&  isset($advanced_props['coupon_form_on_load']) && $advanced_props['coupon_form_on_load'] == 'yes' ? true : false;

		$billing_shipping_together = isset($advanced_props['make_billing_shipping_together']) && $advanced_props['make_billing_shipping_together'] == 'yes' ? true : false;

		$steps_for_review = isset($advanced_props['steps_for_review']) && $advanced_props['steps_for_review'] ? $advanced_props['steps_for_review'] : '';
		$steps_for_review = json_encode($steps_for_review);

		$placeholder_step = isset($advanced_props['placeholder_step']) && $advanced_props['placeholder_step'] ? $advanced_props['placeholder_step'] : '';
		$placeholder_step = $this->get_step_name_from_hook($placeholder_step);

		$exclude_checkout_fields = isset($advanced_props['exclude_checkout_fields']) && $advanced_props['exclude_checkout_fields'] ? $advanced_props['exclude_checkout_fields'] : '';

		$show_billing_in_shipping = isset($advanced_props['show_billing_address_in_shipping']) && $advanced_props['show_billing_address_in_shipping'] == 'yes' ? true : false;
		$enable_cart_step = isset($advanced_props['enable_cart_step']) && $advanced_props['enable_cart_step'] == 'yes' ? true : false;

		if($enable_cart_step){
			wp_dequeue_script('wc-cart');
			wp_register_script('wc-cart', WC()->plugin_url() . '/assets/js/frontend/cart.js', array( 'jquery', 'woocommerce'), $this->version, true);
    		wp_enqueue_script('wc-cart');
		}

		wp_register_script('thwmsc-public-script', THWMSC_ASSETS_URL_PUBLIC . 'js/thwmsc-public'. $suffix .'.js', array('select2'), $this->version, true );
		
		wp_enqueue_script('thwmsc-public-script');
		$site_language = THWMSC_Utils::get_locale_code();
		
		$script_var = array(
			'ajax_url'    				=> admin_url( "admin-ajax.php?lang=$site_language" ),
			'enable_step_forward'   	=> apply_filters('thwmsc_enable_step_forward', $enable_step_forward),
			'validation_type'       	=> apply_filters('thwmsc_step_validation_type', 'ajax'),
			'show_passed_accordion' 	=> apply_filters('thwmsc_show_passed_accordion', false),
			'show_login_direclty' 		=> apply_filters('thwmsc_login_form_on_load', $login_form_on_load),
			'show_coupon_direclty' 		=> apply_filters('thwmsc_coupon_form_on_load', $coupon_form_on_load),
			
			'billing_shipping_together' => apply_filters('thwmsc_billing_shipping_together', $billing_shipping_together),
			'enable_cart_step'			=> apply_filters('thwmsc_enable_cart_step', $enable_cart_step),
			'steps_for_review'			=> apply_filters('thwmsc_steps_for_review', $steps_for_review),
			'placeholder_step'			=> apply_filters('thwmsc_placeholder_step', $placeholder_step),
			'exclude_checkout_fields'	=> apply_filters('thwmsc_exclude_checkout_fields', $exclude_checkout_fields),
			'billing_in_shipping'		=> apply_filters('thwmsc_show_billing_in_shipping', $show_billing_in_shipping),
			'disable_ajax_validation' 	=> apply_filters('thwmsc_disable_ajax_validation', $disable_ajax_validation),
			'review_shipping_fields'    => apply_filters('thwmsc_review_shipping_fields_title','Review Shipping Fields'),
			'language'					=> $site_language,
		);
		wp_localize_script('thwmsc-public-script', 'thwmsc_public_var', $script_var);
	}
	
	public function define_public_hooks(){
		//$hp_sample_hook = apply_filters('thwmsc_sample_hook_priority', 1);
		//add_action( 'sample_hook', array($this, 'sample_hook_function'), $hp_sample_hook, 1 );
		
		add_action('thwmsc_multi_step_tabs', array($this, 'render_multi_step_tabs'));
		add_action('thwmsc_multi_step_before_tab_panels', array($this, 'render_multi_step_before_tab_panels'));
		add_action('thwmsc_multi_step_after_tab_panels', array($this, 'render_multi_step_after_tab_panels'));
		add_action('thwmsc_multi_step_tab_panels', array($this, 'render_multi_step_tab_panels'));
		
		// validation by Ajax
		add_action('wp_ajax_thwmsc_step_validation', array($this->validator, 'validate_checkout_step'), 1);
		add_action('wp_ajax_nopriv_thwmsc_step_validation', array($this->validator, 'validate_checkout_step'), 1);

		// remove steps from woocommerce validation if it is disabled
		if(THWMSC_Utils::is_thwcfe_plugin_active()){
			// remove step from woocommerce validation if it is hidden
			add_filter('thwcfe_disabled_hooks', array($this, 'get_disabled_step_hooks'));
			add_filter('thwcfe_disabled_sections', array($this, 'get_disabled_default_section'));
		}else{
			add_action('woocommerce_checkout_process', array($this, 'remove_steps_validation_thwcfe_deactive'));
		}

		$current_theme = wp_get_theme();
		$theme_template = $current_theme->get_template();

		
		/* Hestia Theme Compatibility */
		if($theme_template === 'hestia'){
			remove_action('woocommerce_before_checkout_form', 'hestia_coupon_after_order_table_js');
			remove_action('woocommerce_checkout_order_review', 'hestia_coupon_after_order_table');
		}

		/* Astra Theme Compatibility */
		if($theme_template === 'astra'){
			$astra_priority = apply_filters('thwmsc_atsra_hook_priority', 99); 
			// add_filter('astra_woo_shop_product_structure_override', '__return_true');

			add_action( 'wp', array($this, 'astra_remove_shipping_from_billing'));
			add_action( 'woocommerce_checkout_shipping', array( WC()->checkout, 'checkout_form_shipping' ), $astra_priority);
		}

		/* Avada Theme Compatibility */
		if($theme_template === 'Avada'){
			$avada_priority = apply_filters('thwmsc_avada_hook_priority', 11);
			add_action('woocommerce_checkout_after_customer_details', array($this, 'thwmsc_avada_div_close'), $avada_priority);
		}

		$advanced_props = $this->get_advanced_props();
		$enable_login_step = isset($advanced_props['enable_login_step']) && $advanced_props['enable_login_step'] == 'yes' ? true : false;
		$woo_checkout_login_enabled = get_option('woocommerce_enable_checkout_login_reminder');
		$is_enabled = $this->check_step_enabled_in_general_settings('login');

		$use_my_account_login = isset($advanced_props['use_my_account_login']) && $advanced_props['use_my_account_login'] == 'yes' ? true : false;

		$enable_coupen_step = isset($advanced_props['enable_coupen_step']) && $advanced_props['enable_coupen_step'] == 'yes' ? true : false;
		$make_order_review_separate = isset($advanced_props['make_order_review_separate']) && $advanced_props['make_order_review_separate'] == 'yes' ? true : false;
		$make_billing_shipping_together = isset($advanced_props['make_billing_shipping_together']) && $advanced_props['make_billing_shipping_together'] == 'yes' ? true : false;

		$enable_cart_step = isset($advanced_props['enable_cart_step']) && $advanced_props['enable_cart_step'] == 'yes' ? true : false;
		$placeholder_step = isset($advanced_props['placeholder_step']) && $advanced_props['placeholder_step'] ? $advanced_props['placeholder_step'] : 'woocommerce_checkout_before_order_review';

		$display_props = $this->get_display_props();
		if($display_props['thwmsc_layout'] == 'thwmsc_accordion_tab') {
			remove_action('thwmsc_multi_step_after_tab_panels',array($this,'render_multi_step_after_tab_panels'));
			add_action('thwmsc_multi_step_accordion_after_tab_panels', array($this, 'thwmsc_accordion_add_next_step'));
		}

		// $steps = THWMSC_Utils::get_step_settings_public();
		$steps = THWMSC_Utils::get_step_settings();

		// var_dump($steps);

		$login_priority = isset($steps) && is_array($steps) ? array_search("login", array_keys($steps)) + 11 : 10;
		$coupen_priority = isset($steps) && is_array($steps) ? array_search("coupon", array_keys($steps)) + 11 : 10;
		$cart_priority = isset($steps) && is_array($steps) ? array_search("cart", array_keys($steps)) + 11 : 10;

		if($enable_login_step && ($woo_checkout_login_enabled == 'yes') && $is_enabled || apply_filters('thwmsc_show_login_step_display', false)){
			add_filter('thwmsc_skip_step_render_login', '__return_true');
			add_action('thwmsc_multi_step_before_tab_panels', array($this, 'thwmsc_add_login_step_content'), $login_priority);
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

			if($use_my_account_login){
				add_action('thwmsc_woocommerce_checkout_login', array($this, 'add_my_account_login_register_form'));
			}else{
				add_action('thwmsc_woocommerce_checkout_login', 'woocommerce_checkout_login_form');
				add_filter('thwmsc_steps_remove_before_display', array($this, 'thwmsc_remove_disabled_step'));
			}
		}

		add_filter('thwmsc_add_new_steps', array($this, 'remove_woo_disabled_steps'), 10, 1);

		if($enable_coupen_step){
			add_filter('thwmsc_skip_step_render_coupon', '__return_true');
			add_action('thwmsc_multi_step_before_tab_panels', array($this, 'thwmsc_add_coupon_step_content'), $coupen_priority);
			add_action('thwmsc_woocommerce_checkout_coupon', 'woocommerce_checkout_coupon_form');

			$is_enabled = $this->check_step_enabled_in_general_settings('coupon');
			if($is_enabled){
				remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
			}
		}

		if($make_order_review_separate){
			add_action('thwmsc_woocommerce_checkout_review_order', 'woocommerce_order_review');

			$is_enabled = $this->check_step_enabled_in_general_settings('review_order');
			if($is_enabled){
				remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
			}
		}

		if($make_billing_shipping_together){
			add_filter('thwmsc_add_new_steps', array($this, 'thwmsc_make_billing_shipping_tab'), 10);
		}

		if($enable_cart_step){
			add_filter('thwmsc_skip_step_render_cart', '__return_true');
			add_action('thwmsc_multi_step_before_tab_panels', array($this, 'wmsc_render_cart_tab_content'), $cart_priority);
			add_action('thwmsc_woocommerce_checkout_cart_step', array($this, 'wmsc_add_cart_shortcode'));
		}

		add_filter('thwmsc_trigger_step_reorder', '__return_true');

		add_action($placeholder_step, array($this, 'wmsc_render_review_placeholder'), 9);
	}

	public function get_step_name_from_hook($hook){
		$steps = THWMSC_Utils::get_step_settings();
		
		$step_name = '';
		if($steps){
			foreach ($steps as $key => $step) {
				if($step['action_before'] == $hook || $step['action'] == $hook){
					$step_name = $key;
				}
			}
		}

		return $step_name;
	}

	public function wmsc_render_review_placeholder(){
		$advanced_props = $this->get_advanced_props();
		$steps_for_review = isset($advanced_props['steps_for_review']) && $advanced_props['steps_for_review'] ? $advanced_props['steps_for_review'] : '';
		if($steps_for_review){
			echo '<div id="thwmsc_review_data_wrap" class="thwmsc-review-wrap"></div>';
		}
	}

	public function wmsc_add_cart_shortcode(){
		echo do_shortcode('[woocommerce_cart]');
	}

	public function astra_remove_shipping_from_billing(){
		remove_action('woocommerce_checkout_billing', array(WC()->checkout(), 'checkout_form_shipping'));
	}

	public function thwmsc_avada_div_close(){
	  	$close_html = (Avada()->settings->get('woocommerce_one_page_checkout')) ? '</div>': '';
	    echo $close_html;
	}

	public function remove_woo_disabled_steps($steps){
		$woo_coupon_enable = wc_coupons_enabled();
		if(!$woo_coupon_enable){
			unset($steps['coupon']);
		}

		$woo_checkout_login_enabled = get_option('woocommerce_enable_checkout_login_reminder');
		$advanced_props = $this->get_advanced_props();
		$use_my_account_login = isset($advanced_props['use_my_account_login']) && $advanced_props['use_my_account_login'] == 'yes' ? true : false;

		// if($woo_checkout_login_enabled === 'no' && !$use_my_account_login){
		if($woo_checkout_login_enabled === 'no' &&  !apply_filters('thwmsc_show_login_step_display', false)){
			unset($steps['login']);
		}

		return $steps;
	}

	public function add_my_account_login_register_form(){
		echo do_shortcode('[woocommerce_my_account]');
	}

	// Check for enabled in general
	public function check_step_enabled_in_general_settings($step_name){
		$steps = THWMSC_Utils::get_step_settings();
		$is_enabled = THWMSC_Utils::is_valid_enabled($steps, $step_name);
		return $is_enabled;
	}

	public function thwmsc_remove_disabled_step($settings){
		if((is_user_logged_in() && !is_admin()) &&  array_key_exists('login', $settings) && apply_filters('thwmsc_disable_login_removal_for_logged_in_user', true)){
			unset($settings['login']);
		}

		return $settings;
	}

	public function thwmsc_make_billing_shipping_tab($steps){
		if(array_key_exists('shipping', $steps)){
			unset($steps['shipping']);
		}
		
		if(array_key_exists('billing', $steps)){
			$steps['billing']['action_after'] = 'woocommerce_checkout_shipping';
			$steps['billing']['new_after_action'] = 'woocommerce_checkout_after_customer_details';
		}
		
		return $steps;
	}

	//Login step tab
	public function add_login_step_public($steps, $full_settings){
		$steps = THWMSC_Utils::add_login_step($steps, $full_settings);
		return $steps;
	}

	//Login step tab content
	public function thwmsc_add_login_step_content(){
		if(!apply_filters('thwmsc_show_login_step', false)){
			$steps = THWMSC_Utils::get_step_settings_public();
			if(array_key_exists('login', $steps)){
				$this->render_tab_content($steps['login']);
			}			
		}
	}

	//Coupen step tab
	public function add_coupon_step_public($steps, $full_settings){
	    $steps = THWMSC_Utils::add_coupon_step($steps, $full_settings);
		return $steps;
	}

	//Coupen step tab content
	public function thwmsc_add_coupon_step_content(){
		$steps = THWMSC_Utils::get_step_settings_public();
		if(array_key_exists('coupon', $steps)){
			$this->render_tab_content($steps['coupon']);
		}		
	}

	//Add Review Order Step
	public function add_order_review_step_public($steps, $full_settings){
		$steps = THWMSC_Utils::add_order_review_step($steps, $full_settings);
		return $steps;
	}

	public function wmsc_render_cart_tab_content(){
		$steps = THWMSC_Utils::get_step_settings_public();
		if(array_key_exists('cart', $steps)){
			$this->render_tab_content($steps['cart']);
		}
	}

	public function get_disabled_step_hooks($dis_hooks){
		$steps = THWMSC_Utils::get_step_settings_admin();		
		if($steps){
			foreach ($steps as $step_name => $step_prop) {
				if(!$step_prop['enabled'] && $step_prop['custom']){
        			$dis_hooks[] = $step_prop['action'];
        		}
			}
		}
		return $dis_hooks;
	}

	public function get_disabled_default_section($dis_sections){
		$steps = THWMSC_Utils::get_step_settings_admin();		
		if($steps){
			foreach ($steps as $step_name => $step_prop) {
				if(!$step_prop['enabled'] && !$step_prop['custom']){					
        			$dis_sections[] = $step_name;
        			if($step_name === 'shipping'){
						$dis_sections[] = 'order';
					}
        		}
			}
		}
		return $dis_sections;
	}

	public function remove_steps_validation_thwcfe_deactive(){
		$checkout_fields = WC()->checkout->checkout_fields;
		$steps = THWMSC_Utils::get_step_settings_admin();
		if($steps){
			foreach ($steps as $step_name => $step_prop) {
				if(!$step_prop['enabled'] && !$step_prop['custom']){
					if(array_key_exists($step_name, $checkout_fields)){
						unset($checkout_fields[$step_name]);

						if($step_name === 'shipping'){
							unset($checkout_fields['order']);
						}
					}				
        		}
			}
		}

		$checkout_fields = apply_filters('thwmsc_removed_disabled_steps', $checkout_fields, $steps);
		if($checkout_fields){
			WC()->checkout->checkout_fields = $checkout_fields;
		}
	}
	
	public function get_posted_value($name, $type = false){
		$is_posted = isset($_POST[$name]) || isset($_REQUEST[$name]) ? true : false;
		$value = false;
		
		if($is_posted){
			$value = isset($_POST[$name]) && $_POST[$name] ? $_POST[$name] : false;
			$value = empty($value) && isset($_REQUEST[$name]) ? $_REQUEST[$name] : $value;
		}
		return $value;
	}

	public function thwmsc_multistep_template( $template, $template_name, $template_path ){ 		
        if('checkout/form-checkout.php' == $template_name ){  
        	
        	$enable = THWMSC_Utils::is_wmsc_enabled();
        	$enable = apply_filters('thwmsc_msc_enable', $enable);   

        	if($enable){         	
           		$template = THWMSC_TEMPLATE_URL . 'woocommerce/checkout/form-checkout.php';  
        	}                 
        }
        return $template;      
    }

    public function login_form_adding(){    	 
    	woocommerce_checkout_login_form();    	
    }

    public function thwmsc_checkout_payment_method(){      
    	woocommerce_checkout_payment();
    }	
	
	private function get_display_props(){
		$display_props = THWMSC_Utils::get_advanced_settings();   
		$display_props = apply_filters('thwmsc_public_display_settings', $display_props );
		return $display_props;
	}

	private function get_advanced_props(){
		$advanced_props = THWMSC_Utils::get_new_advanced_settings();   
		$advanced_props = apply_filters('thwmsc_public_adavanced_settings', $advanced_props);
		return $advanced_props;
	}

	public function render_multi_step_tabs($checkout){
		$tab_align = '';
		$time_line_type = '';
		$time_line_text_position = '';

		$steps = THWMSC_Utils::get_step_settings_public();
		$display_props = $this->get_display_props();

		$mobile_class = isset($display_props['mobile_accordion']) && $display_props['mobile_accordion']  ? 'thwmsc-mobile-accordion' : '';

		if(isset($display_props['thwmsc_layout']) && $display_props['thwmsc_layout'] == 'thwmsc_time_line_step'){
			$time_line_type = isset($display_props['time_line_type']) && $display_props['time_line_type']  ? $display_props['time_line_type'] : '';
			$time_line_text_position = isset($display_props['time_line_text']) && $display_props['time_line_text']  ? $display_props['time_line_text'] : '';
		}else{
			$tab_align = isset($display_props['tab_align']) ? 'thwmsc-'.$display_props['tab_align'] : '';
		}

		$layout_classes = $this->prepare_layout_classes($display_props);
		$steps = $this->reorder_step_if_thwcfe_deactive($steps);

		$steps = apply_filters('thwmsc_change_tab_settings', $steps);
		if(apply_filters('thwmsc_trigger_step_reorder', false)){
			$steps = $this->reorder_steps_for_display($steps);
		}

		?>
		<div id="thwmsc_wrapper" class="thwmsc-wrapper <?php echo $layout_classes .' '. $mobile_class . ' '. $time_line_type .' '.$time_line_text_position ; ?>">
			<?php 

			do_action('thwmsc_multi_step_before_tabs');

			?>
			<ul id="thwmsc-tabs" class="thwmsc-tabs <?php echo $tab_align; ?>">
			<?php
			foreach($steps as $step_key => $step){
				if(apply_filters('thwmsc_skip_step_tab_'.$step_key, false, $step, $display_props)){
					continue;
				}
				$this->render_tab($step);
			}
			?>
			</ul>
			
			<?php

			do_action('thwmsc_multi_step_after_tabs');

			?>
		<?php
	}
	
	public function render_multi_step_before_tab_panels($checkout){
		?>
			<div class="thwmsc-tab-panel-wrapper">
				<div id="thwmsc-tab-panels" class="thwmsc-tab-panels"> 
		<?php
	}
	
	public function render_multi_step_after_tab_panels($checkout){
		$display_props = $this->get_display_props();
		$advanced_props = $this->get_advanced_props();
		$button_class = $this->get_button_class($display_props);

		$previous = $this->get_advanced_item_setting($advanced_props, 'button_prev_text');
		$previous = !empty($previous) ? $previous : 'Previous';

		$next = $this->get_advanced_item_setting($advanced_props, 'button_next_text');
		$next = !empty($next) ? $next : 'Next';
		
		$previous_button_text = apply_filters('thwmsc_change_previous_button', $previous); 
		$next_button_text = apply_filters('thwmsc_change_next_button', $next);

		?>
				</div>

				<?php do_action('thwmsc_multi_step_before_buttons'); ?>

				<div class="thwmsc-buttons">
					<button type="button" id="action-prev" class="thwmsc-btn button-prev <?php echo $button_class; ?>" value="">
						<?php _e( $previous_button_text, 'woocommerce-multistep-checkout' ); ?>
					</button>

					<button type="button" id="action-next" class="thwmsc-btn button-next <?php echo $button_class; ?>" value="">
						<?php _e( $next_button_text, 'woocommerce-multistep-checkout' ); ?>
					</button>
				</div>

				<?php do_action('thwmsc_multi_step_after_buttons'); ?>

			</div>
		</div> 
		<?php
	}

	// previous and next button if layout is accordion
	public function thwmsc_accordion_add_next_step($order) {
		$display_props = $this->get_display_props();
		$advanced_props = $this->get_advanced_props();
		$button_class = $this->get_button_class($display_props);

		$previous = $this->get_advanced_item_setting($advanced_props, 'button_prev_text');
		$previous = !empty($previous) ? $previous : 'Previous';

		$next = $this->get_advanced_item_setting($advanced_props, 'button_next_text');
		$next = !empty($next) ? $next : 'Next';
		
		$previous_button_text = apply_filters('thwmsc_change_previous_button', $previous); 
		$next_button_text = apply_filters('thwmsc_change_next_button', $next);

		do_action('thwmsc_multi_step_before_buttons');
		?>
		<div class="thwmsc-buttons">
			<button type="button" id="action-prev-accordion-<?php echo $order ?>" class="thwmsc-btn button-prev <?php echo $button_class; ?> action-accordion-prev" value="" data-step="<?php echo $order ?>">
				<?php _e( $previous_button_text, 'woocommerce-multistep-checkout' ); ?>
			</button>

			<button type="button" id="action-next-accordion-<?php echo $order ?>" class="thwmsc-btn button-next <?php echo $button_class; ?> action-accordion-next" value="" data-step="<?php echo $order ?>">
				<?php _e( $next_button_text, 'woocommerce-multistep-checkout' ); ?>
			</button>
		</div>

		<?php
	}
	
	public function render_multi_step_tab_panels($checkout){
		$steps = THWMSC_Utils::get_step_settings_public();
		$steps = $this->reorder_step_if_thwcfe_deactive($steps);

		$steps = apply_filters('thwmsc_change_tab_settings', $steps);
		if(apply_filters('thwmsc_trigger_step_reorder', false)){
			$steps = $this->reorder_steps_for_display($steps);
		}

		foreach($steps as $step_key => $step){
			if(apply_filters('thwmsc_skip_step_render_'.$step_key, false)){
				continue;
			}
			$this->render_tab_content($step);
		}
	}
	
	private function render_tab($step){
		$index 		  = isset($step['index']) ? htmlspecialchars_decode($step['index']) : '';
		$index_logged_in = isset($step['index_logged_in']) ? htmlspecialchars_decode($step['index_logged_in']) : $index;
		
		$order 		  = $step['order'];

		$title1 = isset($step['title']) ? $step['title'] : '';
		$step_title = THWMSC_i18n::t($title1);

		$name = isset($step['name']) ? $step['name'] : '';

		$class 		  = $step['class'];
		$indextype	  = isset($step['indextype']) ? $step['indextype'] : '';
		$index_media  = isset($step['index_media']) ? $step['index_media'] : '';
		$display_props = $this->get_display_props();

		if($indextype == 'icon_index'){
			if (isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_custom_separator') && ($display_props['custom_separator_with_icon'] != 'yes')) {
				$tab_icon = '';
			} else {
				$tab_icon = !empty($index_media) ? '<span class="thwmsc-index thwmsc-img-icon"><img class="" src="'. wp_get_attachment_url($index_media) .'"></span>' : '';
			}
		}else{
			if(is_user_logged_in() && THWMSC_Utils::check_extra_step_is_activated('enable_login_step')){
				$tab_icon = $index_logged_in != '' ? '<span class="thwmsc-index thwmsc-tab-icon">'.$index_logged_in.' </span>' : '';
			}else{
				$tab_icon = $index != '' ? '<span class="thwmsc-index thwmsc-tab-icon">'.$index.' </span>' : '';
			}
			
		}		

		if(isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_time_line_step')){
			if(empty($tab_icon) || (isset($display_props['time_line_type']) && ($display_props['time_line_type'] == 'thwmsc_open_no_icon' || $display_props['time_line_type'] == 'thwmsc_end_closed_no_icon' ))){
				$tab_icon = '<span class="thwmsc-index thwmsc-tab-icon">' . ' </span>';
			}			
		}

		if (isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_custom_separator')) {
			// shows arrow icon if layout is custom separator
			$after_icon = '<span class="dashicons dashicons-arrow-right-alt2'.$class.'"></span>';
		} elseif (isset($display_props['thwmsc_layout']) && ($display_props['thwmsc_layout'] == 'thwmsc_simple_dot_format')) {
			// shows dot icon if layout is simple dot format
			$after_icon = '<span class="thwmsc-dot-icon"></span>';
		} else {
			$after_icon = '';
		}

		$tickmark = isset($display_props['enable_step_tickmark']) && ($display_props['enable_step_tickmark'] == 'yes') && !empty($tab_icon) ? '<span class="dashicons dashicons-yes"></span>' : '';

		$title = $tab_icon.$step_title.$tickmark;
		?>
		<li class="thwmsc-tab">
			<a href="javascript:void(0)" id="step-<?php echo $order; ?>" data-step_name="<?php echo $name;?>" data-step_title="<?php echo $step_title; ?>" data-step="<?php echo $order; ?>" class="<?php echo $class; ?>">
				<span class="thwmsc-tab-label"><?php echo $title; ?></span>
				<?php echo $after_icon; ?>
			</a>
		</li> 
		<?php	
	}
	
	private function render_tab_content($step){
		$display_props = $this->get_display_props();

		$action = isset($step['action']) ? $step['action'] : false;
		$action_before = isset($step['action_before']) ? $step['action_before'] : false;
		$action_after = isset($step['action_after']) ? $step['action_after'] : false;
		$is_custom = isset($step['custom']) ? $step['custom'] : false;
		
		$name 	= isset($step['name']) ? $step['name'] : '';
		$order  = isset($step['order']) ? $step['order'] : '';
		$class  = ($step['order'] == 0) ? 'active' : '';

		$index 		  = isset($step['index']) ? htmlspecialchars_decode($step['index']) : '';
		$index_logged_in = isset($step['index_logged_in']) ? htmlspecialchars_decode($step['index_logged_in']) : $index;

		$indextype	  = isset($step['indextype']) ? $step['indextype'] : '';
		$index_media  = isset($step['index_media']) ? $step['index_media'] : '';
		$title = isset($step['title']) ? sprintf(__('%s', 'woocommerce-multistep-checkout'),$step['title']) : false;

		$enable_step_bg = isset($step['enable_step_bg']) ? $step['enable_step_bg'] : '';
		$step_font = isset($step['step_font']) && $enable_step_bg ? 'color:' . $step['step_font'] .';' : '';
		$step_bg = isset($step['step_bg']) && $enable_step_bg ? 'background: '.$step['step_bg'] . '!important;' : '';
		$step_style = $step_font . $step_bg;
		
		$step_content = isset($step['step_content']) && ( (isset($step['step_independent']) && $step['step_independent']) || !$is_custom)  ? $step['step_content'] : '';
		$custom_position	  = isset($step['custom_position']) && $step['custom_position'] ? $step['custom_position'] : 'above_fields';

		$new_after_action = isset($step['new_after_action']) ? $step['new_after_action'] : false;
		
		if($indextype == 'icon_index'){
			$tab_icon = !empty($index_media) ? '<span class="thwmsc-index thwmsc-img-icon"><img class="" src="'. wp_get_attachment_url($index_media) .'"></span>' : '';
		}else{
			if(is_user_logged_in() && THWMSC_Utils::check_extra_step_is_activated('enable_login_step')){
				$tab_icon = $index_logged_in != '' ? '<span class="thwmsc-index thwmsc-tab-icon">'.$index_logged_in.' </span>' : '';
			}else{
				$tab_icon = $index != '' ? '<span class="thwmsc-index thwmsc-tab-icon">'.$index.' </span>' : '';
			}
		}

		$tickmark = isset($display_props['enable_step_tickmark']) && ($display_props['enable_step_tickmark'] == 'yes') && !empty($tab_icon) ? '<span class="dashicons dashicons-yes"></span>' : '';
		$step_title = $title;
		$title = $tab_icon.$title.$tickmark;
		?>
		<div class="thwmsc-tab-panel <?php echo $name; ?>" id="thwmsc-tab-panel-<?php echo $order; ?>" style="<?php echo $step_style; ?>">
			<a href="javascript:void(0)" id="thwmsc-accordion-label-<?php echo $order; ?>" data-step="<?php echo $order; ?>" class="thwmsc-accordion-label <?php echo $class; ?>">
				<span class="thwmsc-tab-label <?php echo $class; ?>" id="" data-step="<?php echo $order; ?>">
					<?php echo $title; ?>
				</span>
			</a>	
			<?php // if layout is simple dot format add title
			if(isset($display_props['thwmsc_layout']) && $display_props['thwmsc_layout'] == 'thwmsc_simple_dot_format') { ?>
				<h3 class="thwmsc-tab-label thwmsc-dot-format-label"><?php echo $step_title; ?></h3>
			<?php } ?>
			<div class="thwmsc-tab-content" id="thwmsc-tab-content-<?php echo $order; ?>">
				<?php if($step_content && ($custom_position == 'above_fields')){ ?>
					<div class="thwmsc-step-custom-content <?php echo 'thwmsc-custom-'.$name ?>">
						<?php echo do_shortcode($step_content); ?>
					</div>
				<?php } ?>
				
				<?php
				if($action_before){
					do_action( $action_before );
				}
				if($action){
					if($is_custom){
						do_action( $action, $action );
					}else{
						do_action( $action );
					}
				}
				if($action_after){
					do_action( $action_after );
				}
				if($new_after_action){
					do_action($new_after_action);
				}
				//if layout is accordion
				if(isset($display_props['thwmsc_layout']) && $display_props['thwmsc_layout'] == 'thwmsc_accordion_tab') {
					do_action('thwmsc_multi_step_accordion_after_tab_panels', $order);
				}
				?>

				<?php if($step_content && ($custom_position == 'below_fields')){ ?>
					<div class="thwmsc-step-custom-content <?php echo 'thwmsc-custom-'.$name ?>" style="<?php echo 'color:' . $step_font . ';' ?>">
						<?php echo do_shortcode($step_content); ?>
					</div>
				<?php } 
				?>

			</div>
		</div>
		<?php
	}	
	
	private function prepare_layout_classes($display_props){
		$layout = isset($display_props['thwmsc_layout']) ? $display_props['thwmsc_layout'] : '';
		$class = '';
		
		switch($layout){
			case 'thwmsc_horizontal_box':
				$class = 'thwmsc-layout-top thwmsc-blocks';
				break;
			case 'thwmsc_horizontal_arrow':
				$class = 'thwmsc-layout-top thwmsc-arrows';
				break;
			case 'thwmsc_vertical_box':
				$class = 'thwmsc-layout-left thwmsc-blocks';
				break;
			case 'thwmsc_vertical_arrow':
				$class = 'thwmsc-layout-left thwmsc-arrows';
				break;
			case 'thwmsc_vertical_tab':
				$class = '';
				break;
			case 'thwmsc_time_line_step':
				$class = 'thwmsc-layout-time-line';
				break;
			case 'thwmsc_accordion_tab':
				$class = 'thwmsc-accordion-step';
				break;
			case 'thwmsc_custom_separator':
				$class = 'thwmsc-custom-separator';
				break;
			case 'thwmsc_closed_arrow_layout':
				$class = 'thwmsc-closed-arrow-layout thwmsc-arrows';
				break;
			case 'thwmsc_looped_box_layout':
				$class = 'thwmsc-looped-box-layout';
				break;
			case 'thwmsc_simple_dot_format':
				$class = 'thwmsc-simple-dot-format';
				break;
			case 'thwmsc_tab_format':
				$class = 'thwmsc-tab-format';
				break;
			default:
				$class = '';
		}
		return $class;
	}

	private function get_button_class($display_props){
		$button_class = isset($display_props['button_new_class']) ? $display_props['button_new_class'] : '';
		$button_class = str_replace(',', ' ', $button_class);
		return $button_class;
	}

	private function get_advanced_item_setting($advanced_props, $name=false){
		if($name){
			$value = isset($advanced_props[$name]) ? $advanced_props[$name] : '';
			return $value;
		}
		return false;
	}

	private function reorder_step_if_thwcfe_deactive($steps){		
		$thwcfe_active = THWMSC_Utils::is_thwcfe_plugin_active();

		if(!$thwcfe_active){
			foreach ($steps as $step) {
				$disable_wcfe = isset($step['step_independent']) && $step['step_independent'] == '1'  ? false : true ;		
				if($step['custom'] && !$thwcfe_active && $disable_wcfe){
					unset($steps[$step['name']]);
				}
			}
			$steps = $this->reorder_steps_for_display($steps);		
		}	
		return $steps;
	}

	private function reorder_steps_for_display($steps){
		$steps = THWMSC_Utils::reset_step_display_order($steps);
		$steps = THWMSC_Utils::sort_steps($steps);
		$steps = THWMSC_Utils::prepare_step_display_props($steps);

		return $steps;
	}
	
}

endif;