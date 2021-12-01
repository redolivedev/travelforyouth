<?php

namespace RoWooCommerce;

require_once 'licenses/licenses-ajax.php';

function ro_get_lists() {

	$api = sanitize_text_field( $_POST['api_key'] );

	if( ! $api ) return false;
	if( ( $pos = strpos( $api, "-" ) ) !== FALSE ) {
	    $dc = substr( $api, $pos+1 );
	}

	$args = array(
		'headers' => array(
            'Authorization' => 'Basic ' . base64_encode( ' :' . $api ),
            'timeout'       => 15
		)
	);
	$get_lists = wp_remote_get( 'https://' . $dc . '.api.mailchimp.com/3.0/lists/', $args );
	if( is_wp_error( $get_lists ) ) {
		wp_send_json_error( $get_lists );
	} else {
		wp_send_json_success( json_decode( $get_lists['body'] ) );
	}

}
add_action( 'wp_ajax_ro_get_lists', 'RoWooCommerce\ro_get_lists' );

function ro_add_mailchimp_merge_fields(){

	if( !$_POST ){
		wp_send_json_error( 'Post is empty' );
	}

	$roWcOptions = get_option( 'ro_wc_options' );

	$api = sanitize_text_field( $_POST['api_key'] );
	$list_id = $_POST['mailchimp_list'];

	if( !$api ){
		wp_send_json_error( 'API key is empty' );
	} 
	
	if( ( $pos = strpos( $api, "-" ) ) !== FALSE ) {
	    $dc = substr( $api, $pos + 1 );
	}

	$data = array( 
		'name' => 'Email Hash',
		'tag' => 'EMAILHASH',
		'type' => 'text',
		'public' => true
	);

	$body = json_encode( $data );

	$args = array(
		'method' => 'POST',
		'headers' => array(
			'content-type' => 'application/json',
			'Authorization' => 'apikey ' . $api
		),
		'body' => $body
	);

	$url = 'https://' . $dc . '.api.mailchimp.com/3.0/lists/' . $list_id . '/merge-fields';


	$add_merge_field = wp_remote_request( $url, $args );

	if( is_wp_error( $add_merge_field) ){
		wp_send_json_error( $add_merge_field );
	}
	else{
		if( $add_merge_field['response']['code'] != 200 ){
			wp_send_json_error( $add_merge_field['body'] );
		}
		else{	
			$roWcOptions['abandoned_cart_mailchimp_config'] = true;
			$roWcOptions = update_option( 'ro_wc_options', $roWcOptions );
			wp_send_json_success( json_decode( $add_merge_field['body'] ) );
		}				
	}

}
add_action( 'wp_ajax_ro_add_mailchimp_merge_fields', 'RoWooCommerce\ro_add_mailchimp_merge_fields' );

function ro_check_mailchimp_merge_fields(){

	if( !$_POST ){
		wp_send_json_error( 'Post is empty' );
	}

	$roWcOptions = get_option( 'ro_wc_options' );
	
	$api = sanitize_text_field( $_POST['api_key'] );
	$list_id = $_POST['mailchimp_list'];

	if( !$api ){
		wp_send_json_error( 'API key is empty' );
	} 
	if( ( $pos = strpos( $api, "-" ) ) !== FALSE ) {
	    $dc = substr( $api, $pos + 1 );
	}

	$args = array(
		'method' => 'GET',
		'headers' => array(
			'content-type' => 'application/json',
			'Authorization' => 'apikey ' . $api
		)
	);

	$url = 'https://' . $dc . '.api.mailchimp.com/3.0/lists/' . $list_id . '/merge-fields';


	$check_merge_field = wp_remote_request( $url, $args );

	if( is_wp_error( $check_merge_field) ){
		wp_send_json_error( $check_merge_field );
	}
	else{
		if( $check_merge_field['response']['code'] != 200 ){
			wp_send_json_error( $check_merge_field['body'] );
		}
		else{	
			$decoded_body = json_decode( $check_merge_field['body'] );
			foreach( $decoded_body->merge_fields as $db ){
				if( $db->tag == 'EMAILHASH' ){
					$roWcOptions['abandoned_cart_mailchimp_list'] = $list_id;
					$roWcOptions['abandoned_cart_mailchimp_config'] = true;
					$roWcOptions = update_option( 'ro_wc_options', $roWcOptions );
					wp_send_json_success( array('emailhash' => true) );
				}
			}
			if( isset( $roWcOptions['abandoned_cart_mailchimp_config'] ) ){
				unset( $roWcOptions['abandoned_cart_mailchimp_config'] );
			}
			$roWcOptions['abandoned_cart_mailchimp_list'] = $list_id;
			$roWcOptions = update_option( 'ro_wc_options', $roWcOptions );
			wp_send_json_success( array( 'emailhash' => false ) );
		}
	}

}
add_action( 'wp_ajax_ro_check_mailchimp_merge_fields', 'RoWooCommerce\ro_check_mailchimp_merge_fields' );

function ro_import_csv() {
	if( $_POST['csv_data'] ){
		require_once 'ro-wc-product-import.php';
		$product_import = new ProductImport( $_POST['csv_data'] );
		$import_result = $product_import->getImportResult();
		if( $import_result ){
			wp_send_json_success( 'CSV Data Added!' );
		}
		else {
			wp_send_json_error( 'Something went wrong in getImportResult function' );
		}		
	}
	else {
		wp_send_json_error( 'No CSV Data' );
	}
}
add_action( 'wp_ajax_ro_import_csv', 'RoWooCommerce\ro_import_csv' );