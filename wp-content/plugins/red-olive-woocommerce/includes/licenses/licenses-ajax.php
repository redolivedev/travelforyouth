<?php

namespace RoWooCommerce;

function ro_wc_set_license_key(){
	if( empty( $_POST['license_key'] ) ){
		wp_send_json_error( 'Missing license key' );
	}
	$options = get_option( 'ro_wc_options' );
	$options['wc_license_key'] = $_POST['license_key'];
	update_option( 'ro_wc_options', $options );
	wp_send_json_success( 'License key added' );
}
add_action( 'wp_ajax_ro_wc_set_license_key', 'RoWooCommerce\ro_wc_set_license_key' );