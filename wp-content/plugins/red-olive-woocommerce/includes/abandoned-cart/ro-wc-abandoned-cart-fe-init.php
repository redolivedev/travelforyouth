<?php

namespace RoWooCommerce;

if( ! isset( $_SESSION ) ) session_start();

function ro_abandoned_cart_script(){
    wp_register_script( 'ro-wc-abandoned-cart-script', RO_WC_URL . 'assets/js/abandonedCart.js', array('jquery'), 1.0, true );
	$localization_array = array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'ro-wc-abandoned-cart-script', 'ro_wc_ac_localized', $localization_array );
	wp_enqueue_script( 'ro-wc-abandoned-cart-script' );	

}
add_action( 'wp_enqueue_scripts', 'RoWooCommerce\ro_abandoned_cart_script' );


function ro_add_cart_to_session(){
	if( !isset( $_GET['returncart'] ) || !$_GET['returncart'] ){
		return;
	}	

	$hash_and_id = explode( '-', $_GET['returncart'] );	

	global $wpdb;
	$woocommerce = WC();
	$wc_session = $woocommerce->session;
	$table = $wpdb->prefix . 'ro_abandoned_cart';

	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM $table 
			WHERE hash = %s
			AND id = %d
			AND checkout_at IS NULL", 
			$hash_and_id[0],
			$hash_and_id[1]
		)
	);	

	if( $results ){
		$array = unserialize( end( $results )->cart_contents );

		$wc_session->set(
			'cart', 
			$array
		);		

		$woocommerce->cart->get_cart_from_session();

		$_SESSION['retreived_email_hash'] = $results[0]->hash;

		//Redirect user back to the cart without the URL params so they can delete cart items if they'd like
		wp_redirect( get_home_url() . strtok( $_SERVER['REQUEST_URI'], '?' ) ); 
		exit;
	}
}
add_action( 'wp_loaded', 'RoWooCommerce\ro_add_cart_to_session' );

/**
 * Print out a javascript variable with the order ID on the checkout success page.
 */
function ro_localize_order_id_on_checkout_success( $order_id ){
    ?>
    <script>
        wcOrderID = <?php echo $order_id; ?>
    </script>
    <?php
}
add_action( 'woocommerce_thankyou', 'RoWooCommerce\ro_localize_order_id_on_checkout_success');
