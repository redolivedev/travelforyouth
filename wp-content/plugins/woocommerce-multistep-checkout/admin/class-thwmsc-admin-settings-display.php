<?php
/**
 * The admin display settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-multistep-checkout
 * @subpackage woocommerce-multistep-checkout/admin
 */
if(!defined('WPINC')){	die; } 

if(!class_exists('THWMSC_Admin_Settings_Display')):

class THWMSC_Admin_Settings_Display extends THWMSC_Admin_Settings{
	protected static $_instance = null;
	
	private $settings_fields = NULL;
	private $cell_props_L = array();
	private $cell_props_R = array();
	private $cell_props_CB = array();
	private $cell_props_TA = array();
	
	public function __construct() {
		parent::__construct('display_settings');
		$this->init_constants();
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	} 
	
	public function init_constants(){
		$this->cell_props_L = array( 
			'label_cell_props' => 'class="titledesc" scope="row" style="width: 20%;"', 
			'input_cell_props' => 'class="forminp"', 
			'input_width' => '250px', 
			'label_cell_th' => true 
		);

		$this->cell_props_R = array( 
			'label_cell_width' => '13%', 
			'input_cell_width' => '34%', 
			'input_width' => '250px' 
		);

		$this->cell_props_CB = array( 
			'cell_props' => 'colspan="2"', 
			'render_input_cell' => true,
			'input_cell_props' => 'class="wmsc-switch"',
		);

		$this->cell_props_TA = array( 
			'label_cell_props' => 'class="titledesc" scope="row" style="width: 20%; vertical-align:top"', 
			'rows' => 10, 
		);
		
		$this->settings_fields = $this->get_advanced_settings_fields();
	}
	
	public function get_advanced_settings_fields(){
		$layout_options = array(
			'thwmsc_horizontal_box'		=> array('name' => __('Horizontal Box Layout', 'woocommerce-multistep-checkout'), 'layout_image' => 'horizontal_box.png'),
			'thwmsc_horizontal_arrow' 	=> array('name' => __('Horizontal Arrow Layout', 'woocommerce-multistep-checkout'), 'layout_image' => 'horizontal_arrow.png'),
			'thwmsc_vertical_box' 		=> array('name' => __('Vertical Box Layout', 'woocommerce-multistep-checkout'), 'layout_image' => 'vertical_box.png'),
			'thwmsc_vertical_arrow'		=> array('name' => __('Vertical Arrow Layout', 'woocommerce-multistep-checkout'), 'layout_image' => 'vertical_arrow.png'), 
			'thwmsc_time_line_step'		=> array('name' => __('Time Line Layout', 'woocommerce-multistep-checkout'), 'layout_image' => 'timeline.png'),
			'thwmsc_accordion_tab' 		=> array('name' => __('Accordion Layout', 'woocommerce-multistep-checkout'), 'layout_image' => 'accordion.png'),
			'thwmsc_custom_separator' 	=> array('name' => __('Custom Separator', 'woocommerce-multistep-checkout'), 'layout_image' => 'custom_separator.png'),
			'thwmsc_closed_arrow_layout' => array('name' => __('Closed Arrow Layout', 'woocommerce-multistep-checkout'), 'layout_image' => 'closed_arrow.png'),
			'thwmsc_looped_box_layout' 	 => array('name' => __('Looped Box Layout', 'woocommerce-multistep-checkout'), 'layout_image' => 'looped_box.png'),
			'thwmsc_simple_dot_format' 	 => array('name' => __('Simple Dot Format', 'woocommerce-multistep-checkout'), 'layout_image' => 'simple_dot.png'),
			'thwmsc_tab_format' 	 => array('name' => __('Tab Format', 'woocommerce-multistep-checkout'), 'layout_image' => 'tab_format.png')
		);
		
		$layout_options1 = array(
			//'thwmsc_horizontal' => 'Horizontal Layout',
			'thwmsc_horizontal_box' => 'Horizontal Box Layout',
			'thwmsc_horizontal_arrow' => 'Horizontal Arrow Layout',
			//'thwmsc_vertical' => 'Vertical Layout', 
			'thwmsc_vertical_box' => 'Vertical Box Layout',
			'thwmsc_vertical_arrow' => 'Vertical Arrow Layout', 
			'thwmsc_time_line_step' => 'Time Line Layout',
			'thwmsc_accordion_tab' => 'Accordion Layout',
			'thwmsc_custom_separator' => 'Custom Separator',
			'thwmsc_closed_arrow_layout' => 'Closed Arrow Layout',
			'thwmsc_looped_box_layout' => 'Looped Box Layout',
			'thwmsc_simple_dot_format' => 'Simple Dot Format',
			'thwmsc_tab_format' => 'Tab Format'
		);

		$tab_styles = array( 
			'block' => 'Blocks',  
			'arrow' => 'Arrows',
		);

		$tab_position = array( 
			'left' => 'Left',  
			'right' => 'Right',
			'center' => 'Center',
		);

		$font_weight = array( 
			'lighter' => 'Lighter',  
			'normal' => 'Normal',
			'bold' => 'Bold',
			'bolder' => 'Bolder',
		);

		$text_transform = array(
			'initial' => 'Initial',
			'capitalize' => 'Capitalize',
			'lowercase' => 'Lowercase',
			'uppercase' => 'Uppercase',
		);

		$border_style =  array(
			'none'   => 'None',
			'dashed' => 'Dashed',
			'dotted' => 'Dotted',
			'solid'  => 'Solid'
		);

		$padding_type = array(
			'button_padding_top' => '10px',
			'button_padding_right' => '22px',
			'button_padding_bottom' => '10px',
			'button_padding_left' => '22px'
		);

		$step_padding = array(
			'tab_padding_top' => '10px',
			'tab_padding_right' => '25px',
			'tab_padding_bottom' => '10px',
			'tab_padding_left' => '25px'
		);

		$dot_padding = array(
			'dot_padding_top' => '10px',
			'dot_padding_right' => '15px',
			'dot_padding_bottom' => '10px',
			'dot_padding_left' => '15px'
		);

		$timeline_types = array(
			'thwmsc_normal' 		 	=> 'Normal',
			'thwmsc_end_closed'			=> 'Two End Closed With Icon',
			'thwmsc_open_no_icon'		=> 'Two End Open Without Icon',
			'thwmsc_end_closed_no_icon' => 'Two End Closed Without Icon',
			//'thwmsc_title_above' 		=> 'Title Above The Icon',
		);

		$timeline_text = array(
			'thwmsc_title_below' => 'Below Index',
			'thwmsc_title_above' => 'Above Index'
		);
		

		$layout_field = array(
			'thwmsc_display' => array('title'=>__('Display Layout', 'woocommerce-multistep-checkout'), 'type'=>'separator', 'colspan'=>'6'),		
			'enable_wmsc' => array(   
				'name'=>"enable_wmsc", 'label'=> __('Enable Multistep', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>1
			),
			'disable_validation' => array(   
				'name'=>"disable_validation", 'label'=>__('Disable step validation', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0
			),
			'enable_step_forward' => array(
				'name'=>"enable_step_forward", 'label'=>__('Enable forward navigation on step click', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0
			),
			'mobile_accordion' => array(
				'name'=>'mobile_accordion', 'label'=>__('Force accordion layout for mobile view', 'woocommerce-multistep-checkout'), 'hint_text'=>__('If the layout is broken in mobile view, you can use the accordion layout for mobile view to overcome css issue.', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0
			),
			'title_multistep_layout' => array('title'=>__('Multistep Layout'), 'type'=>'separator', 'colspan'=>'6'),
			'thwmsc_layout' => array( 
				'name'=>'thwmsc_layout', 'label'=>__('Multistep Layout', 'woocommerce-multistep-checkout'), 'type'=>'radio', 'value'=>'thwmsc_horizontal_box', 'options'=> $layout_options, 'onchange'=> 'thwmscverticalStyle(this)' 					
			),			

			'tab_style' => array( 
				'name'=>'tab_style', 'label'=>__('Step Style', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'block', 'options'=> $tab_styles										
			),
			'tab_align' => array( 
				'name'=>'tab_align', 'label'=>__('Tab Position', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'left', 'options'=> $tab_position										
			),
			'tab_vertical_text_align' => array( 
				'name'=>'tab_vertical_text_align', 'label'=>__('Text alignment', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'left', 'options'=> $tab_position										
			),
			'tab_width' => array( 
				'name'=>'tab_width', 'label'=>__('Step Section Width', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'25%', 'placeholder'=>'Width in %'
			),
			'content_width' => array( 
				'name'=>'content_width', 'label'=>__('Step Content Width', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'75%', 'placeholder'=>'Width in %'
			),
			'custom_separator_with_icon' => array( 
				'name'=>"custom_separator_with_icon", 'label'=>__('Custom separator with icon', 'woocommerce-multistep-checkout'), 'hint_text'=>__('Shows icon index added in step'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0
			),
			
			'title_display_styles' => array('title'=>__('Display Style', 'woocommerce-multistep-checkout'), 'type'=>'separator', 'colspan'=>'6'),
			'tab_panel_bg_color' => array( 
				'name'=>'tab_panel_bg_color', 'label'=>__('Content background color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#FBFBFB'
			),
			'step_text_color' => array(
				'name'=>'step_text_color', 'label'=>__('Step text color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#8B8B8B'
			),
			'step_text_color_active' => array(    
				'name'=>'step_text_color_active', 'label'=>__('Step text color - Active', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#FFFFFF'
			),
			'step_text_font_size' => array(
				'name'=>'step_text_font_size', 'label'=>__('Step font size', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'16px', 'unittype'=>'mixed', 'placeholder'=>'Eg: 16px'
			),
			'step_text_font_weight' => array(
				'name'=>'step_text_font_weight', 'label'=>__('Step font weight', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'normal', 'options'=> $font_weight
			),
			'step_text_transform' => array(
				'name'=>'step_text_transform', 'label'=>__('Step text transform', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'initial', 'options'=> $text_transform
			),
			'enable_completed_tab_bg' => array(
				'name'=>'enable_completed_tab_bg', 'label'=>__('Show different color for completed tabs', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0, 'onchange'=>'thwmscPassedbgEnable(this)'
			),
			'completed_tab_bg_color' => array( 
				'name'=>'completed_tab_bg_color', 'label'=>__('Completed Tab Background color', 'woocommerce-multistep-checkout'), 'hint_text'=>__('Disable the option &#39;Enable forward navigation on step click&#39; in display settings for activating this feature.', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#018DC2'
			),
			'completed_tab_text_color' => array( 
				'name'=>'completed_tab_text_color', 'label'=>__('Completed Tab Text color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#FFFFFF'
			),
			'border_color_tab_format' => array(
				'name'=>'border_color_tab_format', 'label'=>__('Border color for tab format layout', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#8b8b8b'
			),
			'dot_format_bg_color' => array(
				'name'=>'dot_format_bg_color', 'label'=>__('Background color for dot format layout', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#FFFFFF'
			),
			'dot_format_dot_color' => array(
				'name'=>'dot_format_dot_color', 'label'=>__('Dot color for dot format layout', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#018DC2'
			),
			'dot_format_bg_color_active' => array(
				'name'=>'dot_format_bg_color_active', 'label'=>__('Background color for dot format layout - Active', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#FFFFFF'
			),
			'dot_format_dot_color_active' => array(
				'name'=>'dot_format_dot_color_active', 'label'=>__('Dot color for dot format layout - Active', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#8b8b8b'
			),
			'dot_format_bg_color_completed' => array(
				'name'=>'dot_format_bg_color_completed', 'label'=>__('Background color for dot format layout - Completed', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#FFFFFF'
			),
			'dot_format_dot_color_completed' => array(
				'name'=>'dot_format_dot_color_completed', 'label'=>__('Dot color for dot format layout - Completed', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#018DC2'
			),
			'dot_width_height' => array(
				'name'=>'dot_width_height', 'label'=>__('Dot width/height', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'16px', 'unittype'=>'mixed', 'placeholder'=>'Eg: 16px'
			),
			'dot_padding' => array(       
				'name'=>'dot_padding', 'label'=>__('Dot Padding', 'woocommerce-multistep-checkout'), 'type'=>'propertygroup', 'value'=>'', 'unittype'=>'mixed', 'property_items'=> $dot_padding
			),
			'step_bg_color' => array( 
				'name'=>'step_bg_color', 'label'=>__('Step background color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#B2B2B0'
			),  
			'step_bg_color_active' => array(       
				'name'=>'step_bg_color_active', 'label'=>__('Step background color - Active', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#018DC2' 
			),
			'tab_line_height' => array(
				'name'=>'tab_line_height', 'label'=>__('Step line height', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'32px', 'unittype'=>'mixed'
			),			 
			'tab_padding' => array(       
				'name'=>'tab_padding', 'label'=>__('Step Padding', 'woocommerce-multistep-checkout'), 'type'=>'propertygroup', 'value'=>'', 'unittype'=>'mixed', 'property_items'=> $step_padding
			),

			//Start Icon or index styles 
			'icon_styles' => array('title'=>__('Step Index style', 'woocommerce-multistep-checkout'), 'type'=>'separator', 'colspan'=>'6'),
			'enable_step_tickmark' => array(
				'name'=>"enable_step_tickmark", 'label'=>__('Enable step progress with checkmark symbols for completed steps', 'woocommerce-multistep-checkout'), 'hint_text'=>__('Disable the option &#39;Enable forward navigation on step click&#39; in display settings for activating this feature.', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0
			), 
			'step_tickmark_background' => array(
				'name'=>'step_tickmark_background', 'label'=>__('Checkmark background color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#018dc2',
			),
			'step_tickmark_color' => array(
				'name'=>'step_tickmark_color', 'label'=>__('Checkmark Tick color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#ffffff',
			),
			'step_tickmark_border' => array(
				'name'=>'step_tickmark_border', 'label'=>__('Checkmark border color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#ffffff',
			),
			'step_icon_radius' => array(
				'name'=>'step_icon_radius', 'label'=>__('Step index roundness', 'woocommerce-multistep-checkout'), 'type'=>'text', 'hint_text'=>__('Adjust the roundness of the step title icon (input in px or %).', 'woocommerce-multistep-checkout'), 'value'=>'50%', 'unittype'=>'mixed'
			),
			'icon_border_space' => array(
				'name'=>'icon_border_space', 'label'=>__('Space between border and index', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'2px', 'unittype'=>'mixed'
			),
			'icon_height_width' => array(
				'name'=>'icon_height_width', 'label'=>__('Step Index width/height', 'woocommerce-multistep-checkout'), 'type'=>'text', 'hint_text'=>__('Height and width of the index is applicable only if the value is less than line height', 'woocommerce-multistep-checkout'), 'value'=>'30px', 'unittype'=>'mixed'
			),
			'step_icon_font_color' => array(
				'name'=>'step_icon_font_color', 'label'=>__('Index font color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#000000',
			),
			'step_icon_font_color_active' => array(
				'name'=>'step_icon_font_color_active', 'label'=>__('Index font color active', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#000000',
			),
			'step_icon_border_color' => array(
				'name'=>'step_icon_border_color', 'label'=>__('Index border color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#d5d5d5',
			),
			'step_icon_border_color_active' => array(
				'name'=>'step_icon_border_color_active', 'label'=>__('Index border color active', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#018dc2',
			),
			'step_icon_background_color' => array(
				'name'=>'step_icon_background_color', 'label'=>__('Index background color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#ffffff',
			),
			'step_icon_background_color_active' => array(
				'name'=>'step_icon_background_color_active', 'label'=>__('Index background color active', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#ffffff',
			),
			
			//End Icon or index styles

			//Button Styles 

			'button_display_styles' => array('title'=>__('Button Style', 'woocommerce-multistep-checkout'), 'type'=>'separator', 'colspan'=>'6'),
			'button_new_class' => array(
				'name'=>'button_new_class', 'label'=>__('Button Class', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'', 'placeholder'=>'Seperate classes with comma'
			),
			'button_style_active' => array(
				'name'=>"button_style_active", 'label'=>__('Enable button styling', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0, 'onchange'=>'thwmscButtonStyleListner(this)' 
			),
			'button_text_font_size' => array(
				'name'=>'button_text_font_size', 'label'=>__('Font size', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'', 'unittype'=>'mixed', 'placeholder'=>'Eg : 16px'
			),			
			'button_text_font_color' => array(
				'name'=>'button_text_font_color', 'label'=>__('Font color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#333333'
			),
			'button_text_font_color_hover' => array(
				'name'=>'button_text_font_color_hover', 'label'=>__('Font color - Hover', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#333333'
			),
			'button_bg_color' => array(
				'name'=>'button_bg_color', 'label'=>__('Background color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#eeeeee' 
			),
			'button_bg_color_hover' => array(
				'name'=>'button_bg_color_hover', 'label'=>__('Background color - Hover', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#d5d5d5' 
			),			
			'button_padding' => array(
				'name'=>'button_padding', 'label'=>__('Padding', 'woocommerce-multistep-checkout'), 'type'=>'propertygroup', 'value'=>'', 'unittype'=>'mixed', 'property_items'=> $padding_type 
			),
			'button_border_width' => array(
				'name'=>'button_border_width', 'label'=>__('Border width', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'', 'unittype'=>'mixed', 'placeholder'=>'Eg : 2px'
			),
			'button_border_color' => array(
				'name'=>'button_border_color', 'label'=>__('Border color', 'woocommerce-multistep-checkout'), 'type'=>'colorpicker', 'value'=>'#d5d5d5',
			),
			'button_border_style' => array(
				'name'=>'button_border_style', 'label'=>__('Border style', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'none', 'options'=>$border_style
			),
			'button_border_radius' => array(
				'name'=>'button_border_radius', 'label'=>__('Border radius', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'', 'unittype'=>'mixed', 'placeholder'=>'Eg : 5px'
			),
			//Button Styles End

			//Start Time Line Types
			'time_line_type' => array(
				'name'=>'time_line_type', 'label'=>__('Time Line Types', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'normal', 'options'=>$timeline_types
			),
			'time_line_type' => array(
				'name'=>'time_line_type', 'label'=>__('Time Line Types', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'normal', 'options'=>$timeline_types
			),
			'time_line_text' => array(
				'name'=>'time_line_text', 'label'=>__('Time Line Title', 'woocommerce-multistep-checkout'), 'type'=>'select', 'value'=>'below_index', 'options'=>$timeline_text
			),
			//End Time Line Types

		);  		
		return $layout_field;  
	}
	
	public function render_page(){
		$this->render_tabs();
		$this->render_content();
		//$this->render_import_export_settings();
	}
		
	public function save_advanced_settings($settings){
		$result = update_option(THWMSC_Utils::OPTION_KEY_ADVANCED_SETTINGS, $settings);
		return $result;
	}
	
	private function reset_settings(){
		delete_option(THWMSC_Utils::OPTION_KEY_ADVANCED_SETTINGS);
		echo '<div class="updated"><p>'. __('Settings successfully reset', 'woocommerce-multistep-checkout') .'</p></div>';
	}
	
	private function save_settings($advanced_settings){
		$settings = array();
		
		foreach( $this->settings_fields as $name => $field ) {
			// if($field['type'] === 'dynamic_options'){
			// 	$prefix = isset($field['prefix']) ? 'i_'.$field['prefix'].'_' : 'i_';
				
			// 	$vnames = !empty( $_POST[$prefix.'validator_name'] ) ? $_POST[$prefix.'validator_name'] : array();
			// 	$vlabels = !empty( $_POST[$prefix.'validator_label'] ) ? $_POST[$prefix.'validator_label'] : array();
			// 	$vpatterns = !empty( $_POST[$prefix.'validator_pattern'] ) ? $_POST[$prefix.'validator_pattern'] : array();
			// 	$vmessages = !empty( $_POST[$prefix.'validator_message'] ) ? $_POST[$prefix.'validator_message'] : array();
				
			// 	$validators = array();
			// 	$max = max( array_map( 'absint', array_keys( $vnames ) ) );
			// 	for($i = 0; $i <= $max; $i++) {
			// 		$vname = isset($vnames[$i]) ? stripslashes(trim($vnames[$i])) : '';
			// 		$vlabel = isset($vlabels[$i]) ? stripslashes(trim($vlabels[$i])) : '';
			// 		$vpattern = isset($vpatterns[$i]) ? stripslashes(trim($vpatterns[$i])) : '';
			// 		$vmessage = isset($vmessages[$i]) ? stripslashes(trim($vmessages[$i])) : '';
					
			// 		if(!empty($vname) && !empty($vpattern)){
			// 			$vlabel = empty($vlabel) ? $vname : $vlabel;
						
			// 			$validator = array();
			// 			$validator['name'] = $vname;
			// 			$validator['label'] = $vlabel;
			// 			$validator['pattern'] = $vpattern;
			// 			$validator['message'] = $vmessage;
						
			// 			$validators[$vname] = $validator;
			// 		}
			// 	}
			// 	$settings[$name] = $validators;
			// }else{
				$value = '';
				$mixed = false; 
				if(isset($field['unittype']) && $field['unittype'] === 'mixed'){
					$mixed = true;
				}

				if($field['type'] === 'checkbox'){
					$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
				}else if($field['type'] === 'multiselect_grouped'){
					$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
					$value = is_array($value) ? implode(',', $value) : $value;
				}else if($field['type'] === 'text' || $field['type'] === 'textarea'){
					$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
					$value = !empty($value) ? wc_clean(wp_unslash($value)) : '';
					
					if($value && $mixed){
						$unit_value = $this->unit_value_separator($value); 
						if(is_array($unit_value)){
							$settings[$name.'_unit'] = $unit_value['unit'];
							$value = $unit_value['value'];
						}
					}
				}else if($field['type'] === 'propertygroup'){
					$property_items = ($field['property_items']) && is_array($field['property_items']) ? $field['property_items'] : array();	 								
					if($property_items){  
						settype($value, 'array');
						$property_grp = array();
						foreach ($property_items as $grp_key => $grp_value) {
							$pvalue = !empty( $_POST['i_'.$grp_key] ) ? $_POST['i_'.$grp_key] : '';
							$pvalue = !empty($pvalue) ? wc_clean(wp_unslash($pvalue)) : '';

							if($pvalue && $mixed){
								$unit_value = $this->unit_value_separator($pvalue); 
								if(is_array($unit_value)){
									$settings[$name.'_unit'] = $unit_value['unit'];
									$pvalue = $unit_value['value'];
								}
							}
							$property_grp[$grp_key] = $pvalue;   
						} 
						$value = $property_grp; 
					} 								
				}else{
					$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
					if($value && $mixed){
						$unit_value = $this->unit_value_separator($value); 
						if(is_array($unit_value)){
							$settings[$name.'_unit'] = $unit_value['unit'];
							$value = $unit_value['value'];
						}
					}
				}
				
				$settings[$name] = $value;    
			// }

		}

		$advanced_settings = array_merge($advanced_settings, $settings);

		$result = $this->save_advanced_settings($advanced_settings);
		if ($result == true) { 
			echo '<div class="updated"><p>'. __('Your changes were saved.', 'woocommerce-multistep-checkout') .'</p></div>';
		} else {
			echo '<div class="error"><p>'. __('Your changes were not saved due to an error (or you made none!).', 'woocommerce-multistep-checkout') .'</p></div>';
		}	
	}

	private function unit_value_separator($mixed){
		//if($mixed && is_numeric($mixed)){
		//	return $mixed;
		//}else 
		if($mixed){
			$unit_value = array();					
			$value = preg_replace('/[^0-9\.]/','',$mixed);
			$unit = str_replace($value,"",$mixed);

			if(is_numeric($value)){   	
				$unit_value['value'] = $value;
				$unit_value['unit'] = $unit ? $unit : 'px';				
				return $unit_value;
			}			
		}
	}

	private function unit_value_concatenation($value, $unit){
		return ($value.$unit);
	}
	
	private function render_content(){
		$settings = THWMSC_Utils::get_advanced_settings();

		if(isset($_POST['reset_settings'])) 
			$this->reset_settings();	
			
		if(isset($_POST['save_settings']))
			$this->save_settings($settings);
		
		$fields = $this->settings_fields;
		$settings = THWMSC_Utils::get_advanced_settings();

		//var_dump($settings);
		
		$display_style = isset($settings['button_style_active']) && ($settings['button_style_active'] == 'yes') ? '' : 'wmsc-blur';
		$width_style = isset($settings['thwmsc_layout']) && (($settings['thwmsc_layout'] == 'thwmsc_vertical_arrow' || $settings['thwmsc_layout'] == 'thwmsc_vertical_box')) ? 'table-row' : 'none';
		$tab_align_style = isset($settings['thwmsc_layout']) && ($settings['thwmsc_layout'] == 'thwmsc_time_line_step' || $settings['thwmsc_layout'] == 'thwmsc_accordion_tab' ) ? 'none' : 'table-row';
		$time_line_type = isset($settings['thwmsc_layout']) && ($settings['thwmsc_layout'] == 'thwmsc_time_line_step') ? 'table-row' : 'none';
		$enable_passed_style = isset($settings['enable_completed_tab_bg']) && ($settings['enable_completed_tab_bg'] == 'yes') ? '' : 'wmsc-blur';
		$custom_separator_style = isset($settings['thwmsc_layout']) && ($settings['thwmsc_layout'] == 'thwmsc_custom_separator') ? 'table-row' : 'none';
		$border_tab_style = isset($settings['thwmsc_layout']) && ($settings['thwmsc_layout'] == 'thwmsc_tab_format') ? 'table-row' : 'none';
		$dot_format_style = isset($settings['thwmsc_layout']) && ($settings['thwmsc_layout'] == 'thwmsc_simple_dot_format') ? 'table-row' : 'none';
		$step_style =  isset($settings['thwmsc_layout']) && ($settings['thwmsc_layout'] != 'thwmsc_simple_dot_format') ? 'table-row' : 'none';

		foreach( $fields as $name => &$field ) {
			$mixed = false; 
			if(isset($field['unittype']) && $field['unittype'] === 'mixed'){
				$mixed = true;
			}

			if($field['type'] != 'separator'){
				if(is_array($settings) && isset($settings[$name])){
					if($field['type'] === 'checkbox'){
						if(isset($field['value']) && $field['value'] === $settings[$name]){
							$field['checked'] = 1;
						}else{
							$field['checked'] = 0;
						}
					}else if($field['type'] === 'propertygroup'){
						$property_items = ($field['property_items']) && is_array($field['property_items']) ? $field['property_items'] : array();
						$db_content = array();
						if($property_items && is_array($property_items)){
							$db_content = $settings[$name]; 
							$unit = isset($settings[$name.'_unit']) ? $settings[$name.'_unit'] : 'px';	
							$populate_db = array();
							foreach ($property_items as $grp_key => $grp_value) {
								$value = $db_content[$grp_key]; 
								if($value && $mixed){									
									$value = $this->unit_value_concatenation($value, $unit); 							
								}
								$populate_db[$grp_key] = $value; 
							}
							$field['property_items'] = $populate_db; 
						}  
					}else{
						$value = esc_attr($settings[$name]); 
						if(is_numeric($value) && $mixed){
							$unit = isset($settings[$name.'_unit']) ? $settings[$name.'_unit'] : 'px';
							$value = $this->unit_value_concatenation($value, $unit); 							
						}
						$field['value'] = $value;
					}
				}
			}
		}
		
		?>
		<div style="padding-left: 30px;">               
		    <form id="thwmsc_advanced_settings_form" method="post" action="">
				<table class="form-table thpladmin-form-table">
                    <tbody> 
						<?php $this->render_form_section_separator($fields['thwmsc_display']); ?>
						<tr>
							<?php     
							$cell_props_cb = $this->cell_props_CB;
							$cell_props_cb['render_label_cell'] = true;     
							$this->render_form_field_element($fields['enable_wmsc'], $cell_props_cb);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['disable_validation'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['enable_step_forward'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['mobile_accordion'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>

						<?php $this->render_form_section_separator($fields['title_multistep_layout']); ?>
						<tr>
							<?php
							$cell_props_rd = $this->cell_props_CB;
							$cell_props_rd['input_cell_props'] = 'class="forminp layout_wrap" colspan="6"';
							$this->render_form_field_element($fields['thwmsc_layout'], $cell_props_rd);							
							?>
						</tr>
						
						<tr class="tab_align" style="display:<?php echo $tab_align_style; ?>;">
							<?php
							$this->render_form_field_element($fields['tab_align'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>

						<tr class="time_line_type" style="display:<?php echo $time_line_type; ?>;">
							<?php
							$this->render_form_field_element($fields['time_line_type'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr class="time_line_type" style="display:<?php echo $time_line_type; ?>;">
							<?php
							$this->render_form_field_element($fields['time_line_text'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr class="vertical_width" style="display:<?php echo $width_style; ?>;">
							<?php
							$this->render_form_field_element($fields['tab_vertical_text_align'], $this->cell_props_L);
							$this->render_form_field_blank(); 
							?>
						</tr>	
						<tr class="vertical_width" style="display:<?php echo $width_style; ?>;">
							<?php
							$this->render_form_field_element($fields['tab_width'], $this->cell_props_L);
							$this->render_form_field_element($fields['content_width'], $this->cell_props_R);
							?>
						</tr>	
						<tr class="wmsc-switch custom_separator_icon" style="display:<?php echo $custom_separator_style; ?>;">
							<?php
							$this->render_form_field_element($fields['custom_separator_with_icon'], $cell_props_cb);
							?>
						</tr>								
						<?php $this->render_form_section_separator($fields['title_display_styles']); ?>
						<tr class="step_style" style="display:<?php echo $step_style; ?>;">
							<?php          
							$this->render_form_field_element($fields['step_bg_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_text_color'], $this->cell_props_R);
							?>
						</tr>
						<tr class="step_style" style="display:<?php echo $step_style; ?>;">
							<?php          
							$this->render_form_field_element($fields['step_bg_color_active'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_text_color_active'], $this->cell_props_R);
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['tab_panel_bg_color'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr class="step_style" style="display:<?php echo $step_style; ?>;">
							<?php          
							$this->render_form_field_element($fields['step_text_font_size'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_text_font_weight'], $this->cell_props_R);
							?>
						</tr>	
                        <tr class="step_style" style="display:<?php echo $step_style; ?>;">
							<?php          
							$this->render_form_field_element($fields['tab_line_height'], $this->cell_props_L);	
							$this->render_form_field_element($fields['tab_padding'], $this->cell_props_R);
							?>
						</tr>
						<tr class="step_style" style="display:<?php echo $step_style; ?>;">
							<?php          
							$this->render_form_field_element($fields['step_text_transform'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr class="step_style" style="display:<?php echo $step_style; ?>;">
							<?php
							$this->render_form_field_element($fields['enable_completed_tab_bg'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>

						<tr id="passed-tab-settings" class="<?php echo $enable_passed_style; ?> step_style" style="opacity:<?php ?>;display:<?php echo $step_style; ?>">
							<?php          
							$this->render_form_field_element($fields['completed_tab_bg_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['completed_tab_text_color'], $this->cell_props_L);
							?>
						</tr>

						<tr class="border_tab_format" style="display:<?php echo $border_tab_style; ?>">
							<?php          
							$this->render_form_field_element($fields['border_color_tab_format'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>

						<tr class="dot_format_style" style="display:<?php echo $dot_format_style; ?>">
							<?php          
							$this->render_form_field_element($fields['dot_format_bg_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['dot_format_dot_color'], $this->cell_props_R);
							?>
						</tr>

						<tr class="dot_format_style" style="display:<?php echo $dot_format_style; ?>">
							<?php          
							$this->render_form_field_element($fields['dot_format_bg_color_active'], $this->cell_props_L);
							$this->render_form_field_element($fields['dot_format_dot_color_active'], $this->cell_props_R);
							?>
						</tr>

						<tr class="dot_format_style" style="display:<?php echo $dot_format_style; ?>">
							<?php          
							$this->render_form_field_element($fields['dot_format_bg_color_completed'], $this->cell_props_L);
							$this->render_form_field_element($fields['dot_format_dot_color_completed'], $this->cell_props_R);
							?>
						</tr>

						<tr class="dot_format_style" style="display:<?php echo $dot_format_style; ?>">
							<?php          
							$this->render_form_field_element($fields['dot_width_height'], $this->cell_props_L);
							$this->render_form_field_element($fields['dot_padding'], $this->cell_props_R);
							?>
						</tr>

						<?php $this->render_form_section_separator($fields['icon_styles']); ?>
						<tr>
							<?php
							$this->render_form_field_element($fields['enable_step_tickmark'], $cell_props_cb);
							$this->render_form_field_blank();							
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['step_tickmark_background'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_tickmark_border'], $this->cell_props_R);
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['step_tickmark_color'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['icon_height_width'], $this->cell_props_L);
							$this->render_form_field_blank();							
							?>
						</tr>					
                        <tr>
							<?php          
							$this->render_form_field_element($fields['step_icon_radius'], $this->cell_props_L);
							$this->render_form_field_element($fields['icon_border_space'], $this->cell_props_R);	
							?>
						</tr>												
						<tr>
							<?php          
							$this->render_form_field_element($fields['step_icon_font_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_icon_font_color_active'], $this->cell_props_R);
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['step_icon_border_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_icon_border_color_active'], $this->cell_props_R);
							?>
						</tr>						
						<tr>
							<?php          
							$this->render_form_field_element($fields['step_icon_background_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_icon_background_color_active'], $this->cell_props_R);
							?>
						</tr>											
						<?php $this->render_form_section_separator($fields['button_display_styles']); ?>	
						<tr>
							<?php $this->render_form_field_element($fields['button_new_class'], $this->cell_props_L); ?>
						</tr>
					
						<tr>
							<?php     
							$cell_props_cb = $this->cell_props_CB;
							$cell_props_cb['render_label_cell'] = true;     
							$this->render_form_field_element($fields['button_style_active'], $cell_props_cb);
							$this->render_form_field_blank();
							?>
						</tr>
					</tbody> 
					<tbody id="thwmsc_button_styles" class="<?php echo $display_style; ?>">						
						<tr>
							<?php          
							$this->render_form_field_element($fields['button_text_font_size'], $this->cell_props_L);
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['button_text_font_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['button_text_font_color_hover'], $this->cell_props_R);							
							?>
						</tr>	
						<tr>
							<?php          
							$this->render_form_field_element($fields['button_bg_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['button_bg_color_hover'], $this->cell_props_R);
							?>
						</tr>					
						<tr> 
							<?php        							
							$this->render_form_field_element($fields['button_padding'], $this->cell_props_L);	
							$this->render_form_field_blank();
							?>
						</tr>				
						<tr>
							<?php          
							$this->render_form_field_element($fields['button_border_width'], $this->cell_props_L);
							$this->render_form_field_element($fields['button_border_color'], $this->cell_props_R);
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['button_border_radius'], $this->cell_props_L);
							$this->render_form_field_element($fields['button_border_style'], $this->cell_props_R);
							?>
						</tr>
                    </tbody>
                </table>
				                
                <p class="submit">
					<input type="submit" name="save_settings" class="button-primary" value="Save changes">
					<input type="submit" name="reset_settings" class="button-secondary" value="Reset to default" onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
            	</p>
            </form>
    	</div>
    	<?php
	}
}

endif;