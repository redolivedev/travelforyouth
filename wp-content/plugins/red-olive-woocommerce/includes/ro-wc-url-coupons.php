<?php

namespace RoWooCommerce;

class UrlCoupons {

	public $coupon_url_key = 'coupon';
	public $coupon_session_key = 'ro_coupon_code';

	/*
	 * Reference to global woocommerce object
	 */
	public $wc;

	public function __construct() {
		if( ! isset( $_SESSION ) ) session_start();
		
		$this->checkUrl();

		add_action( 'plugins_loaded', array( $this, 'set_up_woocommerce_variables' ) );
		add_action( 'woocommerce_set_cart_cookies', array( $this, 'applyCoupon' ) );
		add_action( 'wp_footer', array( $this, 'printScript' ) );
	}

	public function set_up_woocommerce_variables(){
		$this->wc = WC();
	}

	public function checkUrl() {
		if( isset( $_GET[$this->coupon_url_key] ) ) $this->setSession( $_GET[$this->coupon_url_key] );
	}

	public function checkSession() {
		if( isset( $_SESSION[$this->coupon_session_key] ) ) {
			$coupon = new \WC_Coupon( sanitize_text_field( $_SESSION[$this->coupon_session_key] ) );
			if( ! $coupon->is_valid() ) {
				unset( $_SESSION[$this->coupon_session_key] );
				return false;
			}
			return true;
		}

		return false;
	}

	public function clearSession() {
		if( $this->checkSession() ) {
			unset( $_SESSION[$this->coupon_session_key] );
			return true;
		}
		return false;
	}

	public function setSession( $value = null ) {
		global $wpdb;
		$value = sanitize_text_field( $value );
		if( $wpdb->get_var( 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_title = "'. $value .'" AND post_type="shop_coupon"' ) ) {
			$_SESSION[$this->coupon_session_key] = $value;
			return true;
		} else {
			return false;
		}
	}

	public function applyCoupon() {
		if( ! $this->checkSession() ) return;

		if( ! $this->wc->cart->has_discount( $_SESSION[$this->coupon_session_key] ) ){
			$this->wc->cart->add_discount( $_SESSION[$this->coupon_session_key] );
		}
	}

	public function printScript() {
		if( ! isset( $_GET[$this->coupon_url_key] ) || ! $this->checkSession() ) return;

		$message = 'This coupon has been automatically added to your cart: <strong>' . $_SESSION[$this->coupon_session_key] . '</strong>';

		if( ! is_cart() || WC()->cart->is_empty() ) {
			$message .= '<br><strong>Add products to your cart for the discount</strong>';
		}

		?>
		<script>
			jQuery(function($){
				$.alert({
					title: 'COUPON ADDED',
					content: '<?php echo $message; ?>',
					autoClose: 'confirm|4000',
				});
			});
		</script>
		<?php
	}

}
$url_coupons = new UrlCoupons;
