<?php

namespace RoMarketingPro;

/**
 * Checks if the AJAX request has its own version to implement. Otherwise, attempts
 * to find a current version in the session.
 */
function ro_ab_testing_lookup(){
	if( $_POST['ajax_sent'] ){
		if( isset( $_POST['version'] ) && $_POST['version'] != 'false' ){
			if( $version_data = ro_get_version_data( $_POST['version'] ) ){
				if( ! $version_data['single_page'] ){
					$_SESSION['version'] = $_POST['version'];
				}

				wp_send_json_success( $version_data['element_and_text'] );
			}else{
				wp_send_json_success( false );
			}
		}
		else if( isset( $_SESSION['version'] ) && $_SESSION['version'] ){
			if( $version_data = ro_get_version_data( $_SESSION['version'] ) ){
				if( ! $version_data['single_page'] ){
					wp_send_json_success( $version_data['element_and_text'] );
				}				
			}			
		}
		else{
			wp_send_json_success( false );
		}
	}
	else {
		wp_send_json_error( 'No data sent' );
	}
}
add_action( 'wp_ajax_ab_testing_lookup', 'RoMarketingPro\ro_ab_testing_lookup' );
add_action( 'wp_ajax_nopriv_ab_testing_lookup', 'RoMarketingPro\ro_ab_testing_lookup' );


/**
 * Returns the data for this text version.
 */
function ro_get_version_data( $version ){
	remove_filter('acf_the_content', 'wpautop'); //Removes added <p> tags from WYSIWYG content
	$version_info = get_field( 'ab_testing_elements', 'options' );
	add_filter('acf_the_content', 'wpautop'); //Returns ACF filters back to normal

	if( !$version_info ){
		return false;
	}

	foreach( $version_info as $info ){
		if( $info['version'] == $version ){			
			return $info;
		}
	}

	return false;
}