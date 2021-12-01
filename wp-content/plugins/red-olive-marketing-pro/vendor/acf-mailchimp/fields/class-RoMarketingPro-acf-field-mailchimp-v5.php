<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( ! class_exists( 'RoMarketingPro_acf_field_mailchimp' ) ) :


class RoMarketingPro_acf_field_mailchimp extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	function __construct( $settings ) {
		
		$this->name = 'mailchimp';
		$this->label = __('MailChimp', 'acf-mailchimp');
		$this->category = 'Choice';
		$this->defaults = array();
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('mailchimp', 'error');
		*/
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-mailchimp'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();
	}
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	function render_field( $field ) {
		
		
		/*
		*  Review the data of $field.
		*  This will show what data is available
		*/
		// echo '<pre>';
		// 	print_r( $field );
		// echo '</pre>';

		$field_values = json_decode( $field['value'] );
		
		?>
		<div class="ro-acf-mailchimp-container">
            <div class="mc-left-container">
                <div class="acf-label">
                    <label for="<?php echo esc_attr($field['name']); ?>-key">MailChimp API Key</label>
                </div>
			    <input type="text" class="js-mc-api-key" name="<?php echo esc_attr($field['name']); ?>-key" value="<?php echo esc_attr($field_values->apiKey) ?>">
                <div class="acf-actions">
                    <a class="acf-button button button-primary" id="js-mc-set-key" href="#" data-event="set-api-key">Set API Key</a>
                </div>
            </div>

            <div class="mc-right-container">
                <div class="acf-label"><label for="mc-list">MailChimp List</label></div>
                <select class="js-mc-list-select" name="mc-list" style="display:none;"></select>
                <div class="js-mailchimp-creds-error mailchimp-creds-error">
                    An error occurred gathering your MailChimp account lists. Please check your information and try again.
                </div>
                <div class="js-mailchimp-server-error mailchimp-server-error">
                    An error occurred connecting to the MailChimp server. Please try again later.
                </div>
            </div>

			<!-- Contains the values stored by ACF: API Key and List ID -->
			<input type="hidden" class="js-mc-combined-values" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo htmlspecialchars( $field['value'] ); ?>">
		</div>
		<?php
	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
	function input_admin_enqueue_scripts() {		
		
		// register & include JS
		wp_register_script('acf-mailchimp', ACF_RO_MC_URL . "assets/js/input.js", array('acf-input'), ACF_RO_MC_VERSION );
		wp_enqueue_script('acf-mailchimp');
		
		
		// register & include CSS
		wp_register_style('acf-mailchimp', ACF_RO_MC_URL . "assets/css/input.css", array('acf-input'), ACF_RO_MC_VERSION );
		wp_enqueue_style('acf-mailchimp');
		
	}
}


// initialize
new RoMarketingPro_acf_field_mailchimp( $this->settings );

// class_exists check
endif;

?>