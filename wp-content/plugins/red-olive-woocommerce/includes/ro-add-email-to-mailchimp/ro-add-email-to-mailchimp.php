<?php

namespace RoWooCommerce;

class ROAddEmailToMailChimpFromCheckout{
	protected $order_id;
	protected $customer_info;

	public function __construct( $order_id ){
		$this->order_id = $order_id;
		$this->customer_info = $this->get_info_from_order();

		if( ! $this->customer_info ){
			return;
		}

		$this->send_email_to_mailchimp();
	}

	protected function get_info_from_order(){
		$order = new \WC_Order( $this->order_id );

		if( ! $order ){
			return false;
		}

		// newer versions of WC
		if( method_exists( $order, 'get_billing_email' ) ){
			$customer_info = array(
				'email' => $order->get_billing_email(),
				'fname' => $order->get_billing_first_name(),
				'lname' => $order->get_billing_last_name()
			);
		} else {
			$customer_info = array(
				'email' => $order->billing_email,
				'fname' => $order->billing_first_name,
				'lname' => $order->billing_last_name
			);
		}

		return $customer_info;
	}

	protected function send_email_to_mailchimp(){
		global $roWcOptions;

		if( !isset( $roWcOptions['add_email_to_mailchimp_api_key'] ) || !isset( $roWcOptions['add_email_to_mailchimp_list_id'] ) ){
			return false;
		}
		else{
			$mailchimp_key = $roWcOptions['add_email_to_mailchimp_api_key'];
			$mailchimp_list = $roWcOptions['add_email_to_mailchimp_list_id'];
		}

		if( ( $pos = strpos( $mailchimp_key, "-" ) ) !== FALSE ) {
		    $dc = substr( $mailchimp_key, $pos + 1 );
		}
		else{
			return false;
		}

		$data = array(
			'email_address' => $this->customer_info['email'],
			'status' => 'subscribed',
			'merge_fields' => array(
				'FNAME' => $this->customer_info['fname'],
				'LNAME' => $this->customer_info['lname']
			)
		);

		$body = json_encode( $data );

		$args = array(
			'method' => 'PUT',
			'headers' => array(
				'content-type' => 'application/json',
				'Authorization' => 'apikey ' . $mailchimp_key
			),
			'body' => $body
		);

		$list = 'https://' . $dc . '.api.mailchimp.com/3.0/lists/' . $mailchimp_list . '/members/'
		. md5( strtolower( $this->customer_info['email'] ) );


		$add_member = wp_remote_request( $list, $args );
	}
}

function ro_initialize_email_to_mailchimp( $order_id ){
	$ROAddEmailToMailChimpFromCheckout = new ROAddEmailToMailChimpFromCheckout( $order_id );
}
add_action( 'woocommerce_thankyou', 'RoWooCommerce\ro_initialize_email_to_mailchimp' );
