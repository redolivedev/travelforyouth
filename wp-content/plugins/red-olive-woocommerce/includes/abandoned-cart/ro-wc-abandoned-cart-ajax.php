<?php

namespace RoWooCommerce;

function ro_save_cart_contents(){	
	if( $_POST['email'] ){
		$email = $_POST['email'];
		if( is_email( $email ) ){

			if( isset( $_SESSION['retreived_email_hash'] ) ){
				wp_send_json_error( 'Cart belongs to returning customer' );
			}

			global $wpdb;
			$woocommerce = WC();

			$table = $wpdb->prefix . 'ro_abandoned_cart';

			$check_duplicate = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM $table 
					WHERE email = %s 
					AND sent_at_initial IS NULL", 
					$email
				)
			);

			if( $check_duplicate ){
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM $table
						WHERE email = %s 
						AND checkout_at IS NULL",
						$email
					)
				);
			}

			$cart_items = $woocommerce->cart->get_cart_for_session();

			if( empty( $cart_items ) ){
				wp_send_json_error( 'Cart empty' );
			}

			$_SESSION['email_hash'] = sha1( $email );

			$result = $wpdb->insert(
				$table,
				array(
					'email' => $email,
					'hash' => $_SESSION['email_hash'],
					'cart_contents' => serialize( $cart_items ),
					'created_at' => date('Y-m-d H:i:s')
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s'
				)
			);	
			
			if( $result === false ){
				wp_send_json_error( $wpdb->last_error );
			}
			else{
				wp_send_json_success();
			}			
		}
		else{
			wp_send_json_error( 'Invalid email' );
		}	
	}
	else{
		wp_send_json_error( 'No email sent' );
	}
}
add_action( 'wp_ajax_ro_save_cart_contents', 'RoWooCommerce\ro_save_cart_contents' );
add_action( 'wp_ajax_nopriv_ro_save_cart_contents', 'RoWooCommerce\ro_save_cart_contents' );

function ro_checkout_or_delete_stored_email(){
	global $wpdb;
    $table = $wpdb->prefix . 'ro_abandoned_cart';

	if( isset( $_SESSION['retreived_email_hash'] ) ){
		$updated = $wpdb->query(
			$wpdb->prepare(
				"UPDATE $table
				SET checkout_at = %s, order_id = %s
				WHERE hash = %s
				AND (
					sent_at_initial IS NOT NULL
					OR sent_at_3day IS NOT NULL
				)
				AND checkout_at IS NULL",
                current_time( 'mysql' ),
                $_POST['order_id'],
				$_SESSION['retreived_email_hash']				
			)
		);

		if( $updated == false ){
			wp_send_json_error( 'An error occurred. No matching hash found' );
		}
		else{
            update_post_meta( $_POST['order_id'], 'ro_cart_recovered', 'Yes' );
			wp_send_json_success();
		}
	}
	else{
		$deleted = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $table
				WHERE hash = %s
				AND sent_at_initial IS NULL
				AND sent_at_3day IS NULL
				AND checkout_at IS NULL",
				$_SESSION['email_hash']
			)
		);

		if( $deleted == false ){
			wp_send_json_error( 'Email not found' );
		}
		else{
			wp_send_json_success();
		}
	}	
}
add_action( 'wp_ajax_ro_checkout_or_delete_stored_email', 'RoWooCommerce\ro_checkout_or_delete_stored_email' );
add_action( 'wp_ajax_nopriv_ro_checkout_or_delete_stored_email', 'RoWooCommerce\ro_checkout_or_delete_stored_email' );

function ro_send_test_rac_email(){
    require RO_WC_DIR . 'includes/abandoned-cart/ro-wc-abandoned-cart-process-emails.php';
	if( $_POST['test_email'] ){
		if( is_email( $_POST['test_email'] ) ){
			if( function_exists( 'RoWooCommerce\send_system_email' ) ){
				$result = send_system_email( $_POST['test_email'] );
				if( $result ){
					wp_send_json_success( $result );
				}else{
					wp_send_json_error( $result );
				}
			}else{
				wp_send_json_error( 'send_system_email function does not exist'  );
			}
		}else{
			wp_send_json_error( 'Not a valid email' );
		}		
	}else{
		wp_send_json_error( 'No test email' );
	}
}
add_action( 'wp_ajax_ro_send_test_rac_email', 'RoWooCommerce\ro_send_test_rac_email' );