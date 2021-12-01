<?php

namespace RoMarketingPro;

class MailchimpWidget{

	protected $marketingOptions;
	protected $mailchimp_api_key;
	protected $mailchimp_api_dc;
	protected $mailchimp_api_list;

	public function __construct(){
		$this->marketingOptions = get_option( 'ro_marketing_options' );
		$this->set_up_hooks();
	}

	protected function set_up_hooks(){
		add_action('wp_enqueue_scripts', array( $this, 'ro_include_mailchimp_widget_scripts' ) );
		add_action('admin_enqueue_scripts', array( $this, 'ro_include_mailchimp_widget_admin_scripts' ) );
		add_shortcode( 'ro_mailchimp_widget', array( $this, 'ro_mailchimp_widget_output' ) );
		add_action( 'wp_ajax_ro_get_mailchimp_lists', array( $this, 'ro_get_mailchimp_lists' ) );
		add_action( 'wp_ajax_ro_add_email_to_mailchimp', array( $this, 'ro_add_email_to_mailchimp' ) );
		add_action( 'wp_ajax_nopriv_ro_add_email_to_mailchimp', array( $this, 'ro_add_email_to_mailchimp' ) );
	}

	public function ro_include_mailchimp_widget_scripts(){
		wp_enqueue_script(
			'mailchimp_widget_script', 
			plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/js/frontEndMailchimpWidget.js', 
			array('jquery')
		);
	}

	public function ro_include_mailchimp_widget_admin_scripts(){
		wp_enqueue_script(
			'mailchimp_widget_admin_script', 
			plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/js/mailchimpWidget.js', 
			array('jquery')
		);
	}

	public function ro_mailchimp_widget_output( $atts ){
		$output = '';
		$button_text = 'Join';
		$placeholder_text = 'Email Address';
		$success_message = 'Added Successfully';

		if( isset( $atts['button-text'] ) && $atts['button-text'] ){
			$button_text = $atts['button-text'];
		}
		if( isset( $atts['placeholder-text'] ) && $atts['placeholder-text'] ){
			$placeholder_text = $atts['placeholder-text'];
		}
		if( isset( $atts['success-message'] ) && $atts['success-message'] ){
			$success_message = $atts['success-message'];
		}
		
		$output = '<input class="mailchimp-text" type="text" placeholder="' . $placeholder_text . '">
				   <button class="mailchimp-button">' . $button_text . '</button>
				   <div class="mailchimp-success" style="display:none;">' . $success_message . '</div>
				   <div class="mailchimp-error" style="display:none;"></div>';

		return $output;
	}

	public function ro_get_mailchimp_lists() {
		$api = sanitize_text_field( $_POST['api_key'] );

		if( ! $api ) wp_send_json_error( 'No API Key' );

		$this->ro_process_mailchimp_api_key( $api );

		if( ! $this->mailchimp_api_key || ! $this->mailchimp_api_dc ){
			wp_send_json_error( 'No API Key' );
		}

		$url = 'https://' . $this->mailchimp_api_dc . '.api.mailchimp.com/3.0/lists/';

		$args = array(
			'timeout' => 15,
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( ' :' . $this->mailchimp_api_key )
			)
		);

		$get_lists = wp_remote_get( $url, $args );

		if( is_wp_error( $get_lists ) ) {
			wp_send_json_error( $get_lists );
		} else {
			wp_send_json_success( json_decode( $get_lists['body'] ) );
		}
	}

    /**
     * This function is called by the MailChimp Widget as well as the Pop Ups feature to post emails to MailChimp.
     */
	public function ro_add_email_to_mailchimp(){
		if( ! isset( $_POST['email'] ) || ! $_POST['email'] ){
			wp_send_json_error( 'No Email' );
		}

		if( ! is_email( $_POST['email'] ) ){
			wp_send_json_error( 'Not a Valid Email Address' );
		}

		if( isset( $_POST['api_key'] ) && $_POST['api_key'] && isset( $_POST['list_id'] ) && $_POST['list_id'] ){
            // If this is being called from the Pop Ups feature, api_key and list_id should be post variables
			$this->ro_process_mailchimp_api_key( $_POST['api_key'], $_POST['list_id'] );
		}else{
            // Otherwise, the api_key and list_id will be found in the Marketing Options
			$this->ro_process_mailchimp_api_key( $this->marketingOptions['mailchimp_widget_api_key'] );

			if( ! $this->mailchimp_api_key || ! $this->mailchimp_api_dc || ! $this->mailchimp_api_list ){
				wp_send_json_error( 'No API Information' );
			}
		}
		
		$url = 'https://' . $this->mailchimp_api_dc . '.api.mailchimp.com/3.0/lists/' . $this->mailchimp_api_list . '/members';

		$data = array(
			'email_address' => $_POST['email'],
			'status' => 'subscribed'
		);

		$body = json_encode( $data );

		$args = array(
			'method' => 'POST',
			'headers' => array(
				'content-type' => 'application/json',
				'Authorization' => 'apikey ' . $this->mailchimp_api_key
			),
			'body' => $body
		);

		$add_email = wp_remote_request( $url, $args );

		if( is_wp_error( $add_email ) ){
			$error_message = $add_email->get_error_message();
		}elseif( $add_email['response']['code'] != 200 ){
			$error_message = $this->handle_add_email_failure( $add_email );
		}

		if( $add_email['response']['code'] == 200 ){
			wp_send_json_success();
		}

		if( isset( $error_message ) && $error_message ){
			wp_send_json_error( $error_message );
		}else{
			wp_send_json_error( 'Something went wrong. Please try again later.' );
		}	
	}

	protected function handle_add_email_failure( $response ){

		if( $response['body'] ){
			$body = json_decode( $response['body'], true );
		}

		if( isset( $body ) && $body ){
			$message = $body['title'];
		}

		if( isset( $message ) && $message ){
			return $message;
		}else{
			return 'Code: ' . $response['response']['code'] . ' Message: ' . $response['response']['message'];
		}
	}

	protected function ro_process_mailchimp_api_key( $api_key, $list_id = null ){
		$this->mailchimp_api_key = $api_key;

		if( ( $pos = strpos( $this->mailchimp_api_key, "-" ) ) !== FALSE ) {
		    $this->mailchimp_api_dc = substr( $this->mailchimp_api_key, ( $pos + 1 ) );
		}

		if( $list_id ){
			$this->mailchimp_api_list = $list_id;
		}elseif( isset( $this->marketingOptions['mailchimp_widget_list_id'] ) && $this->marketingOptions['mailchimp_widget_list_id'] ){
			$this->mailchimp_api_list = $this->marketingOptions['mailchimp_widget_list_id'];
		}
	}
}

function ro_load_mailchimp_widget(){
	$mailchimpWidget = new MailchimpWidget;
}
add_action( 'wp_loaded', 'RoMarketingPro\ro_load_mailchimp_widget' );