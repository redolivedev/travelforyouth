<?php
/**
 * The admin advanced settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.1.0
 *
 * @package    woocommerce-multistep-checkout
 * @subpackage woocommerce-multistep-checkout/admin
 */
if(!defined('WPINC')){	die; } 

if(!class_exists('THWMSC_Admin_Settings_Advanced')):

class THWMSC_Admin_Settings_Advanced extends THWMSC_Admin_Settings{
	protected static $_instance = null;
	
	private $settings_fields = NULL;
	private $cell_props_L = array();
	private $cell_props_R = array();
	private $cell_props_CB = array();
	private $cell_props_TA = array();
	
	public function __construct() {
		parent::__construct('advanced_settings');
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
		
		$this->settings_fields = $this->get_additional_settings_fields();
	}

	public function get_available_steps_array(){
		$steps = THWMSC_Utils::get_step_settings();
		$default_steps = array('cart', 'login', 'coupon', 'review_order', 'order_review');

		$advanced_settings = THWMSC_Utils::get_new_advanced_settings();
		$billing_shipping_together = isset($advanced_settings['make_billing_shipping_together']) && $advanced_settings['make_billing_shipping_together'] == 'yes' ? true : false;
		$custom_step_action = isset($advanced_settings['placeholder_step']) ? $advanced_settings['placeholder_step'] : '';
		$placeholder_steps = $this->get_placeholder_steps();

		$steps_array = array();
		if($steps){
			foreach ($steps as $key => $step) {
				if(!THWMSC_Utils::is_enabled($step)){
					continue;
				}

				if($billing_shipping_together && $key == 'shipping'){
					continue;
				}

				if(!in_array($key, $default_steps) && THWMSC_Utils::is_enabled($step)){
					$steps_array[$key] = isset($step['title']) ? $step['title'] : '';
				}

				//on first load, before saving advanced_settings
				if(empty($custom_step_action) && !empty($placeholder_steps)){
					$placeholder_step = array();
					//select first key element array_key_first
					foreach($placeholder_steps as $step_key => $value) {
						$placeholder_step[] = $step_key;
					}
					if(!empty($placeholder_step) && $step['action_before'] == $placeholder_step[0]){
						unset($steps_array[$key]);
					}
				}
				
				if (isset($step['action_before']) && $step['action_before'] && ($step['action_before'] == $custom_step_action)) {
					unset($steps_array[$key]);
				}
			}
		}

		return apply_filters('thwmsc_available_steps_for_review', $steps_array, $steps, $default_steps);
	}

	public static function get_available_all_steps_array(){
		$steps = THWMSC_Utils::get_step_settings();
		$default_steps = array('cart', 'login', 'coupon', 'review_order', 'order_review');

		$advanced_settings = THWMSC_Utils::get_new_advanced_settings();
		$billing_shipping_together = isset($advanced_settings['make_billing_shipping_together']) && $advanced_settings['make_billing_shipping_together'] == 'yes' ? true : false;
		$custom_step_action = isset($advanced_settings['placeholder_step']) ? $advanced_settings['placeholder_step'] : '';

		$steps_array = array();
		if($steps){
			foreach ($steps as $key => $step) {
				if(!THWMSC_Utils::is_enabled($step)){
					continue;
				}

				if($billing_shipping_together && $key == 'shipping'){
					continue;
				}

				if(!in_array($key, $default_steps) && THWMSC_Utils::is_enabled($step)){
					$steps_array[$key] = isset($step['title']) ? $step['title'] : '';
				}
			}
		}

		return apply_filters('thwmsc_available_steps_for_review', $steps_array, $steps, $default_steps);
	}

	public function get_placeholder_steps(){
		$steps = THWMSC_Utils::get_step_settings();
		$placeholder_steps = apply_filters('thwmsc_placeholder_steps', array('review_order', 'order_review'));

		$steps_array = array();
		if($steps){
			foreach ($steps as $key => $step) {
				$is_custom = isset($step['custom']) && $step['custom'] ? $step['custom'] : 0;

				if(!THWMSC_Utils::is_enabled($step)){
					continue;
				}

				if(in_array($key, $placeholder_steps) || $is_custom){
					$hook = isset($step['action_before'])&& $step['action_before'] ? $step['action_before'] : $step['action'];
					$steps_array[$hook] = isset($step['title']) ? $step['title'] : '';
				}
			}
		}

		return apply_filters('thwmsc_placeholder_hook_and_step_array', $steps_array, $steps);
	}

	public function get_all_checkout_fields(){
		$checkout_fields = array();

		if(THWMSC_Utils::is_thwcfe_plugin_active()){
			$checkout_fields = $this->get_thwcfe_checkout_fields();
		}else{
			$checkout_fields = $this->get_normal_checkout_fields();
		}

		return $checkout_fields;
	}

	public function get_thwcfe_checkout_fields(){
		$checkout_fields = array();
		$sections = WCFE_Checkout_Fields_Utils::get_checkout_sections();

		if($sections && is_array($sections)){
			foreach($sections as $sname => $section){
				if($section && THWCFE_Utils_Section::is_valid_section($section)){
					$fields = THWCFE_Utils_Section::get_fields($section);
					
					if($fields && is_array($fields)){
						$sections_fields = array();

						foreach($fields as $name => $field){
							if($field && THWCFE_Utils_Field::is_valid_field($field)){
								$label = $field->get_property('title');
								$label = empty($label) ? $name : $label;
								$sections_fields[$name] = $label;
							}
						}
						
						if(!empty($sections_fields)){
							// $checkout_fields[$sname] = $sections_fields;
							$checkout_fields[$section->get_property('title')] = $sections_fields;
						}
					}
				}
			}
		}

		return $checkout_fields;
	}

	public function get_normal_checkout_fields(){
		$billing = WC()->countries->get_address_fields('','billing_');
		$checkout_fields['billing'] = $this->get_checkout_field_value_pair($billing, 'billing');

		$shipping = WC()->countries->get_address_fields('','shipping_');
		$checkout_fields['shipping'] = $this->get_checkout_field_value_pair($shipping, 'shipping');

		$checkout_fields['order'] = array('order_comments' => 'Order notes');

		return $checkout_fields;
	}

	public function get_checkout_field_value_pair($fields, $section_name){
		$checkout_fields = array();
		if($fields){
			foreach ($fields as $key => $field) {
				$label = isset($field['label']) && $field['label'] ? $field['label'] : $key;
				$checkout_fields[$key] = $label;
			}
		}

		return $checkout_fields;
	}
	
	public function get_additional_settings_fields(){
		$woo_login_enable_url = admin_url('admin.php?page=wc-settings&tab=account');
		$woo_coupon_enable_url = admin_url('admin.php?page=wc-settings&tab=general');

		$placeholder_steps = $this->get_placeholder_steps();
		$available_steps = $this->get_available_steps_array();

		$checkout_fields = $this->get_all_checkout_fields();

		$layout_field = array(
			'thwmsc_advanced' => array('title'=>__('Advanced Settings', 'woocommerce-multistep-checkout'), 'type'=>'separator', 'colspan'=>'6'),

			'login_description' => array('description'=>sprintf(__('Enable <a target="_blank" href="%s">Allow customers to log into an existing account during checkout</a> from Woocommerce settings.', 'woocommerce-multistep-checkout'),$woo_login_enable_url), 'type'=>'description', 'colspan'=>'4'),
			'coupon_description' => array('description'=>sprintf(__('Activate the <a target="_blank" href="%s">Enable the use of coupon codes</a> Option from Woocommerce settings.', 'woocommerce-multistep-checkout'),$woo_coupon_enable_url), 'type'=>'description', 'colspan'=>'4'),

			'enable_login_step' => array(
				'name'=>'enable_login_step', 'label'=>__('Include Login step', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'hint_text'=>__('You can change the step index from checkout steps tab. For accordion style, login reordering is not possible due to nested form effect. The login step will depend on the woocommerce Accounts & Privacy tab settings.', 'woocommerce-multistep-checkout'), 'onchange'=>'thwmscShowLogin(this)', 'value'=>'yes', 'checked'=>0
			),
			'enable_coupen_step' => array(
				'name'=>'enable_coupen_step', 'label'=>__('Include Coupon step', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'hint_text'=>__('You can change the step index from checkout steps tab. For accordion style, coupon reordering is not possible due to nested form effect. The coupon step will depend on the woocommerce Accounts & Privacy tab settings.', 'woocommerce-multistep-checkout'), 'onchange'=>'thwmscShowCoupon(this)', 'value'=>'yes', 'checked'=>0
			),
			'make_order_review_separate' => array(
				'name'=>'make_order_review_separate', 'label'=>__('Show Order Review and Payment in Separate Steps', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'hint_text'=>__('You can change the step index from checkout steps tab', 'woocommerce-multistep-checkout'),'value'=>'yes', 'checked'=>0
			),
			'make_billing_shipping_together' => array(
				'name'=>'make_billing_shipping_together', 'label'=>__('Combine Billing step and Shipping step', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'hint_text'=>__('The Shipping step will merge to the Billing step, Please change the Billing step title if required from checkout steps tab ', 'woocommerce-multistep-checkout'), 'checked'=>0
			),
			'use_my_account_login' => array(
				'name'=>'use_my_account_login', 'label'=>__('Display My account Landing form in Login step', 'woocommerce-multistep-checkout'), 'hint_text'=>__('The default login step content will be replaced with the form used in the login and registration of my account page. My account landing page will be displayed in case of the Logged in users.', 'woocommerce-multistep-checkout'), 'onchange'=>'thwmscMyaccountChange(this)', 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0,
			),
			'login_form_on_load' => array(
				'name'=>'login_form_on_load', 'label'=>__('Show login form on load', 'woocommerce-multistep-checkout'), 'hint_text'=>__('Enable this option to display the login form on page load.', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0,
			),
			'coupon_form_on_load' => array(
				'name'=>'coupon_form_on_load', 'label'=>__('Show coupon form on load', 'woocommerce-multistep-checkout'), 'hint_text'=>__('This will shows coupon form on page loads', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0,
			),
			'thwmsc_button_text' => array('title'=>__('Next and Previous Button Settings', 'woocommerce-multistep-checkout'), 'type'=>'separator', 'colspan'=>'6'),
			'button_prev_text' => array(
				'name'=>'button_prev_text', 'label'=>__('Button Previous Text', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'', 'placeholder'=>''
			),
			'button_next_text' => array(
				'name'=>'button_next_text', 'label'=>__('Button Next Text', 'woocommerce-multistep-checkout'), 'type'=>'text', 'value'=>'', 'placeholder'=>''
			),
			'hide_last_step_next' => array(
				'name'=>'hide_last_step_next', 'label'=>__('Hide the Next button for the Last step', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0
			),
			'hide_first_step_prev' => array(
				'name'=>'hide_first_step_prev', 'label'=>__('Hide the Previous button on the First step', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0
			),
			'enable_cart_step' => array(
				'name'=>'enable_cart_step', 'label'=>__('Include Cart step', 'woocommerce-multistep-checkout'), 'hint_text'=>__('You can change the step index from checkout steps tab. For accordion style, Cart step reordering is not possible due to nested form effect.', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'value'=>'yes', 'checked'=>0,
			),

			// Review step details
			'review_step_details' => array('title'=>__('Review Step Details', 'woocommerce-multistep-checkout'), 'type'=>'separator', 'colspan'=>'6'),
			'steps_for_review' => array(
				'name'=>'steps_for_review', 'label'=>__('Select Steps for Review', 'woocommerce-multistep-checkout'), 'type'=>'multiselect', 'options'=>$available_steps
			),
			'placeholder_step' => array(
				'name'=>'placeholder_step', 'label'=>__('Step for displaying Review', 'woocommerce-multistep-checkout'), 'type'=>'select', 'options'=>$placeholder_steps
			),
			'show_billing_address_in_shipping' => array(
				'name'=>'show_billing_address_in_shipping', 'label'=>__('Show Billing data in Shipping Review data', 'woocommerce-multistep-checkout'), 'type'=>'checkbox', 'hint_text'=>__('If the Shipping destination (WooCommerce Settings) is set as Force shipping to the customer billing address or If the option Combine Billing step and Shipping step is enabled, then this option will take no effect.', 'woocommerce-multistep-checkout'), 'value'=>'yes', 'checked'=>0
			),
			'exclude_checkout_fields' => array(
				'name'=>'exclude_checkout_fields', 'label'=>__('Exclude Checkout Fields', 'woocommerce-multistep-checkout'), 'type'=>'multiselect_grouped', 'options'=>$checkout_fields, 'hint_text'=>__('Exclude the fields from review section', 'woocommerce-multistep-checkout'),
			),

		);  		
		return $layout_field;  
	}
	
	public function render_page(){
		$this->render_tabs();
		$this->render_content();
		$this->render_import_export_settings();
	}
		
	public function save_advanced_settings($settings){
		$result = update_option(THWMSC_Utils::OPTION_KEY_ADVANCED_SETTINGS, $settings);
		return $result;
	}

	public function save_new_advanced_settings($settings){
		$result = update_option(THWMSC_Utils::OPTION_KEY_NEW_SETTINGS, $settings);
		return $result;
	}
	
	private function reset_settings(){
		$result = THWMSC_Utils::remove_extra_steps_added();
		//if($result){
		delete_option(THWMSC_Utils::OPTION_KEY_NEW_SETTINGS);
		echo '<div class="updated"><p>'. __('Settings successfully reset', 'woocommerce-multistep-checkout') .'</p></div>';
		//}
	}
	
	private function save_settings(){
		$settings = array();
		
		foreach( $this->settings_fields as $name => $field ) {
			
			if($field['type'] === 'separator' || $field['type'] === 'description'){
				continue;
			}

			$value = '';
			$mixed = false; 
			if(isset($field['unittype']) && $field['unittype'] === 'mixed'){
				$mixed = true;					 
			}


			// else if($field['type'] === 'multiselect_grouped'){
			// 	$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
			// 	$value = is_array($value) ? implode(',', $value) : $value;
			// }

			if($field['type'] === 'checkbox'){
				$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
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
		}

		$steps = THWMSC_Utils::get_step_settings();

		if(isset($settings['enable_coupen_step']) && $settings['enable_coupen_step'] == 'yes'){
			$is_exist = THWMSC_Utils::check_step_is_already_exist($steps, 'coupon');
			if(!$is_exist){
				$coupon_step = THWMSC_Utils::get_coupon_step_settings();

				if(THWMSC_Utils::check_step_is_already_exist($steps, 'login')){
					$steps = $this->add_step_after_specific_key($steps, $coupon_step, 'login');
				}else if(THWMSC_Utils::check_step_is_already_exist($steps, 'cart')){
					$steps = $this->add_step_after_specific_key($steps, $coupon_step, 'cart');
				}else{
					$steps = $coupon_step + $steps;
				}
			}
		}else{
			$is_exist = THWMSC_Utils::check_step_is_already_exist($steps, 'coupon');
			if($is_exist){
				unset($steps['coupon']);
			}
		}

		if(isset($settings['enable_login_step']) && $settings['enable_login_step'] == 'yes'){
			$is_exist = THWMSC_Utils::check_step_is_already_exist($steps, 'login');
			if(!$is_exist){
				$login_step = THWMSC_Utils::get_login_step_settings();

				if(THWMSC_Utils::check_step_is_already_exist($steps, 'cart')){
					$steps = $this->add_step_after_specific_key($steps, $login_step, 'cart');
				}else{
					$steps = $login_step + $steps;
				}
			}
		}else{
			$is_exist = THWMSC_Utils::check_step_is_already_exist($steps, 'login');
			if($is_exist){
				unset($steps['login']);
			}
		}

		if(isset($settings['make_order_review_separate']) && $settings['make_order_review_separate'] == 'yes'){
			$steps = THWMSC_Utils::add_order_review_step($steps, $steps);
		}else{
			$is_exist = THWMSC_Utils::check_step_is_already_exist($steps, 'review_order');
			if($is_exist){
				unset($steps['review_order']);
			}
		}

		if(isset($settings['enable_cart_step']) && $settings['enable_cart_step'] == 'yes'){
			$is_exist = THWMSC_Utils::check_step_is_already_exist($steps, 'cart');
			if(!$is_exist){
				$cart_step = THWMSC_Utils::get_cart_step_settings();
				$steps = $cart_step + $steps;
			}
		}else{
			$is_exist = THWMSC_Utils::check_step_is_already_exist($steps, 'cart');
			if($is_exist){
				unset($steps['cart']);
			}
		}

		//var_dump($steps);

		$result1 = THWMSC_Utils::save_step_settings($steps);
		$result = $this->save_new_advanced_settings($settings);
		if ($result == true) {
			echo '<div class="updated"><p>'. __('Your changes were saved.', 'woocommerce-multistep-checkout') .'</p></div>';
		} else {
			echo '<div class="error"><p>'. __('Your changes were not saved due to an error (or you made none!).', 'woocommerce-multistep-checkout') .'</p></div>';
		}
	}

	public function add_step_after_specific_key($steps, $new_step, $reference_key){
		$new_array = array();
		if(is_array($steps) && !empty($steps)){
			foreach ($steps as $key => $step) {
				$new_array[$key] = $step;
				if($reference_key == $key){
					$new_array = $new_array + $new_step;
				}
			}
		}
		return $new_array;
	}

	private function unit_value_separator($mixed){ 
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
		//$settings = THWMSC_Utils::get_new_advanced_settings();

		if(isset($_POST['reset_settings'])) 
			$this->reset_settings();	
			
		if(isset($_POST['save_settings']))
			$this->save_settings();
		
		// $fields = $this->settings_fields;
		$fields = $this->get_additional_settings_fields();
		$settings = THWMSC_Utils::get_new_advanced_settings();

		// var_dump($settings);

		$enable_login_step = isset($settings['enable_login_step']) && ($settings['enable_login_step'] == 'yes') ? '' : 'wmsc-blur';
		$enable_coupen_step = isset($settings['enable_coupen_step']) && ($settings['enable_coupen_step'] == 'yes') ? '' : 'wmsc-blur';
		$use_my_account_login = isset($settings['use_my_account_login']) && ($settings['use_my_account_login'] == 'yes') ? 'wmsc-blur' : '';

		$use_login_form = $use_my_account_login ? $use_my_account_login : $enable_login_step;

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
					}else if($field['type'] === 'multiselect' || $field['type'] === 'multiselect_grouped'){
						$field['value'] = $settings[$name];
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
						<?php $this->render_form_section_separator($fields['thwmsc_advanced']); ?>
						<?php     
							$cell_props_cb = $this->cell_props_CB;
							$cell_props_cb['render_label_cell'] = true;
						?>
						<tr>
							<?php
							$this->render_form_field_element($fields['enable_cart_step'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['enable_login_step'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<?php $this->render_form_section_description($fields['login_description'], $cell_props_cb); ?>
						<tr id="th-show-myaccount-form" class="th-show-login-settings <?php echo $enable_login_step; ?>">
							<?php
							$this->render_form_field_element($fields['use_my_account_login'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<tr id="th-show-login-form" class="th-show-login-settings <?php echo $use_login_form; ?>">
							<?php
							$this->render_form_field_element($fields['login_form_on_load'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['enable_coupen_step'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<?php $this->render_form_section_description($fields['coupon_description'], $cell_props_cb); ?>
						<tr id="th-show-coupon-form" class="<?php echo $enable_coupen_step; ?>">
							<?php
							$this->render_form_field_element($fields['coupon_form_on_load'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['make_order_review_separate'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['make_billing_shipping_together'], $cell_props_cb);
							$this->render_form_field_blank();
							?>							
						</tr>
						
						<?php $this->render_form_section_separator($fields['review_step_details']); ?>
						<tr>
							<?php
							$this->render_form_field_element($fields['steps_for_review'], $cell_props_cb);
							$this->render_form_field_blank();
							?>	
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['placeholder_step'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>	
						</tr>
						<tr class="thwmsc_tooltip_zindex">
							<?php
							$this->render_form_field_element($fields['show_billing_address_in_shipping'], $cell_props_cb);
							$this->render_form_field_blank();
							?>	
						</tr>
						<tr class="thwmsc_tooltip_zindex">
							<?php
							$this->render_form_field_element($fields['exclude_checkout_fields'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>	
						</tr>
						<?php $this->render_form_section_separator($fields['thwmsc_button_text']); ?>
						<tr>
							<?php
							$this->render_form_field_element($fields['hide_first_step_prev'], $cell_props_cb);
							$this->render_form_field_blank();
							?>	
						</tr>
						<tr>
							<?php
							$this->render_form_field_element($fields['hide_last_step_next'], $cell_props_cb);
							$this->render_form_field_blank();
							?>	
						</tr>
						<tr>
							<?php $this->render_form_field_element($fields['button_prev_text'], $this->cell_props_L); ?>
						</tr>
						<tr>
							<?php $this->render_form_field_element($fields['button_next_text'], $this->cell_props_L); ?>
						</tr>
                    </tbody>
                </table>
				                
                <p class="submit">
					<input type="submit" name="save_settings" class="button-primary" value="Save changes">
					<input type="submit" name="reset_settings" class="button-secondary" value="Reset to default"
					onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
            	</p>
            </form>
    	</div>
    	<?php
	}
	
    /************************************************
	 *-------- IMPORT & EXPORT SETTINGS - START -----
	 ************************************************/
	public function prepare_plugin_settings(){
		$settings_sections = get_option(THWMSC_Utils::OPTION_KEY_STEP_SETTINGS);
		$settings_advanced = get_option(THWMSC_Utils::OPTION_KEY_ADVANCED_SETTINGS);
		$settings_advanced_new = get_option(THWMSC_Utils::OPTION_KEY_NEW_SETTINGS);

		$plugin_settings = array(
			'OPTION_KEY_STEP_SETTINGS' => $settings_sections,
			'OPTION_KEY_ADVANCED_SETTINGS' => $settings_advanced,  
			'OPTION_KEY_NEW_SETTINGS' => $settings_advanced_new,
		);

		return base64_encode(serialize($plugin_settings));  
	}
	
	public function render_import_export_settings(){
		if(isset($_POST['save_plugin_settings'])) 
			$result = $this->save_plugin_settings(); 
		
		if(isset($_POST['import_settings'])){			   
		} 
		
		$plugin_settings = $this->prepare_plugin_settings();
		if(isset($_POST['export_settings']))
			echo $this->export_settings($plugin_settings);   
		
		$imp_exp_fields = array(
			'section_import_export' => array('title'=>__('Backup and Import Settings', 'woocommerce-multistep-checkout'), 'type'=>'separator', 'colspan'=>'3'),
			'settings_data' => array(
				'name'=>'settings_data', 'label'=>__('Plugin Settings Data', 'woocommerce-multistep-checkout'), 'type'=>'textarea', 'value' => $plugin_settings,
				'sub_label'=>__('You can transfer the saved settings data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Settings".', 'woocommerce-multistep-checkout'),
				//'sub_label'=>'You can insert the settings data to the textarea field to import the settings from one site to another website.'
			),
		);
		?>
		<div style="padding-left: 30px;">               
		    <form id="import_export_settings_form" method="post" action="" class="clear">
                <table class="form-table thpladmin-form-table">
                    <tbody>
                    <?php 
					foreach( $imp_exp_fields as $name => $field ) { 
						if($field['type'] === 'separator'){
							$this->render_form_section_separator($field);
						}else {
							?>
							<tr valign="top">
								<?php  
								if($field['type'] === 'checkbox'){
									$this->render_form_field_element($field, $this->cell_props_CB, false);
								}else if($field['type'] === 'multiselect'){
									$this->render_form_field_element($field, $cell_props);
								}else if($field['type'] === 'textarea'){
									$this->render_form_field_element($field, $this->cell_props_TA);
								}else{
									$this->render_form_field_element($field, $cell_props);
								}
								?>
							</tr>
                    		<?php 
						}
					} 
					?>
                    </tbody>
					<tfoot>
						<tr valign="top">
							<td colspan="2">&nbsp;</td>
							<td class="submit">
								<input type="submit" name="save_plugin_settings" class="button-primary" value="Import Settings">
								<!--<input type="submit" name="import_settings" class="button" value="Import Settings(CSV)">-->
								<!--<input type="submit" name="export_settings" class="button" value="Export Settings(CSV)">-->
							</td>
						</tr>
					</tfoot>
                </table> 
            </form>
    	</div> 
		<?php
	}
		
	public function save_plugin_settings(){	
		if(isset($_POST['i_settings_data']) && !empty($_POST['i_settings_data'])) {
			$settings_data_encoded = $_POST['i_settings_data'];   
			$settings = @unserialize(base64_decode($settings_data_encoded)); 
			
			$result = $result1 = $result2 = $result3 = false;
			if($settings){	
				foreach($settings as $key => $value){	
					if($key === 'OPTION_KEY_STEP_SETTINGS'){
						$result = update_option(THWMSC_Utils::OPTION_KEY_STEP_SETTINGS, $value);	
					}
					if($key === 'OPTION_KEY_ADVANCED_SETTINGS'){
						$result1 = $this->save_advanced_settings($value);
					}
					if($key === 'OPTION_KEY_NEW_SETTINGS'){
						$result2 = $this->save_new_advanced_settings($value);
					}
				}					
			}

			if($result || $result1 || $result2){
				echo '<div class="updated"><p>'. __('Your Settings Updated.', 'woocommerce-multistep-checkout') .'</p></div>';
				return true; 
			}else{
				echo '<div class="error"><p>'. __('Your changes were not saved due to an error (or you made none!).', 'woocommerce-multistep-checkout') .'</p></div>';
				return false;
			}	 			
		}
	}

	public function export_settings($settings){
		ob_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=\"wcfe-checkout-field-editor-settings.csv\";" );
		echo $settings;	
        ob_flush();     
     	exit; 		
	}
	
	public function import_settings(){
	
	}
    /**********************************************
	 *-------- IMPORT & EXPORT SETTINGS - END -----
	 **********************************************/
}

endif;