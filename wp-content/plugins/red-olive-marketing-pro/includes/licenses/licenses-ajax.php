<?php

namespace RoMarketingPro;

function ro_marketing_set_license_key(){
	if( empty( $_POST['license_key'] ) ){
		wp_send_json_error( 'Missing license key' );
	}
	$options = get_option( 'ro_marketing_options' );
	$options['marketing_license_key'] = $_POST['license_key'];
	update_option( 'ro_marketing_options', $options );
	wp_send_json_success( 'License key added' );
}
add_action( 'wp_ajax_ro_marketing_set_license_key', 'RoMarketingPro\ro_marketing_set_license_key' );
