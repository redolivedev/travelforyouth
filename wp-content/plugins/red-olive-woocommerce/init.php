<?php

namespace RoWooCommerce;

class RoWooCommerceAdminPage {
	function __construct(){
		add_action( 'plugins_loaded', array( $this, 'ro_wc_init' ) );
	}

	public function ro_wc_init() {
		/**
		 * Check if WooCommerce is active
		 **/
		if ( RO_WC_WOOCOMMERCE_ACTIVE ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'ro_enqueue_public_assets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'ro_enqueue_admin_assets' ) );
		}else{
			add_action( 'admin_menu', array( $this, 'ro_plugin_page_message' ), 100 );
		}
	}

	/**
	 * Display one of two menu items: If RO Marketing Free is not installed, display a menu that will tell the user to install it.
	 * If RO Marketing Free is installed, the menu telling the user to install WooCommerce will be displayed.
	 */
	public function ro_plugin_page_message()
	{
		// $GLOBALS['admin_page_hooks'] gets the list of declared menu items. If red-olive isn't in it, then RO Marketing free isn't active
		if ( empty ( $GLOBALS['admin_page_hooks']['red-olive'] ) ){
			add_menu_page(
			    'RO WooCommerce',
			    'RO WooCommerce',
			    'manage_options',
			    'ro-wc-warning',
			    array( $this, 'ro_woocommerce_menu_warning' ),
			    'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIxODBweCIgaGVpZ2h0PSIxODAuMDA4cHgiIHZpZXdCb3g9IjAgMCAxODAgMTgwLjAwOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTgwIDE4MC4wMDgiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxwYXRoIGZpbGw9IiNFQjIyMjciIGQ9Ik04OS45NzksMEM0MC4zNiwwLDAsNDAuMzc2LDAsODkuOTk4YzAsNDkuNjMyLDQwLjM2LDkwLjAxMSw4OS45NzksOTAuMDExYzQ5LjYzNywwLDkwLjAyMS00MC4zNzksOTAuMDIxLTkwLjAxMUMxODAsNDAuMzc2LDEzOS42MTUsMCw4OS45NzksMHogTTg5Ljk3OSwxNjUuMTE4Yy0xOC4yMTIsMC0zNC45MS02LjUzMS00Ny45MjEtMTcuMzYxbDE0LjkyNC0xNC45MzRsOS44MjgtOS44MDljLTMuODE3LTIuNjk1LTcuMTQ3LTYuMDAyLTkuODI4LTkuODE0Yy0yLjgxNC00LjAyOS00Ljg4OS04LjYwOS02LjEwNy0xMy41MTJjLTAuNzczLTMuMTI3LTEuMzE1LTYuMzItMS4zMTUtOS42OWMwLTIyLjI5MiwxOC4xNDctNDAuNDE2LDQwLjQyLTQwLjQxNmMzLjM2MiwwLDYuNTc3LDAuNTM0LDkuNjk5LDEuMjk0bDExLjAyNC0xMS4wMjNjLTYuMzg0LTIuNjU2LTEzLjM3OS00LjE0Ni0yMC43MjQtNC4xNDZjLTI5LjkyNSwwLTU0LjI3NywyNC4zNTYtNTQuMjc3LDU0LjI5MmMwLDcuMzQxLDEuNSwxNC4zMzUsNC4xNDUsMjAuNzI0YzEuODQ0LDQuNDc1LDQuNDE5LDguNTEyLDcuMzM1LDEyLjI5M2wtMTQuOTUxLDE0LjkzOGMtMTAuODA4LTEzLjAxNi0xNy4zNDgtMjkuNzI5LTE3LjM0OC00Ny45NTRjMC00MS40MDcsMzMuNjk3LTc1LjEwNCw3NS4wOTctNzUuMTA0YzE4LjIzLDAsMzQuOTM4LDYuNTMxLDQ3Ljk2MywxNy4zNDNsLTE0LjkzOCwxNC45NDlsLTkuODI4LDkuODA1YzMuODMxLDIuNjgyLDcuMTMzLDYuMDA0LDkuODQyLDkuODEyYzIuODE0LDQuMDI2LDQuODk0LDguNTk4LDYuMTEyLDEzLjUwN2MwLjc3MiwzLjEyMywxLjMxMSw2LjMzMSwxLjMxMSw5LjY4OGMwLDIyLjI5Ni0xOC4xNDcsNDAuNDM1LTQwLjQ2Miw0MC40MzVjLTMuMzI5LDAtNi41NDktMC41MzktOS42NjItMS4zMDNsLTExLjA0MiwxMS4wMjljNi4zOTcsMi42NDgsMTMuMzkzLDQuMTQ4LDIwLjcwNCw0LjE0OGMyOS45NTgsMCw1NC4zMjMtMjQuMzY1LDU0LjMyMy01NC4zMWMwLTcuMzMxLTEuNDk5LTE0LjMxNy00LjE0Ny0yMC43MTRjLTEuODQ1LTQuNDctNC40Mi04LjUxMy03LjMzNi0xMi4yOTNsMTQuOTI0LTE0Ljk0MmMxMC44NCwxMy4wMTUsMTcuMzgsMjkuNzM1LDE3LjM4LDQ3Ljk0OUMxNjUuMTIyLDEzMS40MiwxMzEuNDIsMTY1LjExOCw4OS45NzksMTY1LjExOHoiLz48L3N2Zz4='
			);
		}else{
	        // This page will be under the "Red Olive" menu item
			add_submenu_page(
				'red-olive',
		        'Settings Admin',
		        'RO WooCommerce',
		        'manage_options',
		        'ro-wc-settings-admin',
		        array( $this, 'ro_need_woocommerce_message' )
		    );
		}
	}

	public function ro_woocommerce_menu_warning(){
		echo '
		<h2>To use RO WooCommerce, you must first install our free prerequisite plugin, RO Marketing Free.</h2>
		<p>You can install it through your Plugins page by clicking <a target="_blank" href="plugin-install.php?s=ro+marketing&tab=search&type=term">HERE</a>
		</p>
		<p>
			Or you can download it from our website (and learn more about its features) by clicking <a target="_blank" href="https://wordpress.org/plugins/red-olive-marketing/">HERE</a>
		</p>';
		die;
	}

	public function ro_need_woocommerce_message(){
		echo '
			<h2>You must have the WooCommerce plugin activated to use RO WooCommerce.</h2>
			<p>You can install it through your Plugins page by clicking <a target="_blank" href="plugin-install.php?s=woocommerce&tab=search&type=term">HERE</a>
			</p>';
		die;
	}

	public function ro_enqueue_public_assets() {

		$roWcOptions = get_option( 'ro_wc_options' );

		wp_enqueue_script( 'ro-wc-public', RO_WC_URL . 'assets/js/min/public-min.js', array('jquery'), 1.0, true );
		wp_enqueue_style( 'ro-wc-public', RO_WC_URL . 'assets/css/ro-wc.css' );

		if( isset( $roWcOptions['enable_infinite_scroll'] ) && $roWcOptions['enable_infinite_scroll'] ){
			wp_enqueue_script( 'ro_infinite_scroll_script', RO_WC_URL . 'assets/js/infiniteScroll.js', array('jquery'), 1.0 );
		}

		wp_enqueue_script( 'ro-wc-apply-coupon', RO_WC_URL . 'assets/js/applyCoupon.js', array('jquery'), 1.0 );
	}

	public function ro_enqueue_admin_assets(){
		wp_enqueue_style( 'ro-wc-admin', RO_WC_URL . 'assets/css/ro-wc-admin.css' );
        wp_enqueue_script( 'ro_text_limit_script', RO_WC_URL . 'assets/js/textLimit.js' );
        wp_enqueue_script( 'ro_csv_upload_script', RO_WC_URL . 'assets/js/csvUpload.js' );

		wp_register_script( 'ro-wc', RO_WC_URL . 'assets/ro-wc.js', array('jquery') );
		$localization_array = array( 'pluginUrl' => RO_WC_URL );
		wp_localize_script( 'ro-wc', 'ro_wc_localized', $localization_array );
		wp_enqueue_script( 'ro-wc' );
	}

}
new RoWooCommerceAdminPage;

// Allow for ajax calls
if( defined( 'DOING_AJAX' ) && DOING_AJAX ) require_once RO_WC_DIR . 'includes/ro-wc-ajax.php';

require RO_WC_DIR . 'includes/edd-update/edd-update.php';

if( is_admin() ) {
	if( RO_WC_WOOCOMMERCE_ACTIVE ){
		require_once RO_WC_DIR . 'red-olive-wc-settings-page.php';
	}

	require_once RO_WC_DIR . 'includes/ro-wc-product-feed.php';
	require_once RO_WC_DIR . 'includes/ro-wc-email-preview.php';

	/** Require Tabs Content **/
	require_once RO_WC_DIR . 'includes/singletons/options-singleton.php';
	require_once RO_WC_DIR . 'includes/tabs/feature-request-tab.php';
	require_once RO_WC_DIR . 'includes/tabs/shopping-feeds-tab.php';
	require_once RO_WC_DIR . 'includes/tabs/url-functions-tab.php';
	require_once RO_WC_DIR . 'includes/tabs/rich-snippets.php';
	require_once RO_WC_DIR . 'includes/tabs/settings-tab.php';
	require_once RO_WC_DIR . 'includes/tabs/general-tab.php';
	require_once RO_WC_DIR . 'includes/tabs/social-tab.php';
	require_once RO_WC_DIR . 'includes/tabs/email-tab.php';
	require_once RO_WC_DIR . 'includes/tabs/ppc-tab.php';
	require_once RO_WC_DIR . 'includes/tabs/ux-tab.php';
	/** End Tabs Content **/

	if( isset( $roWcOptions['clear_expired_wc_sessions'] ) && $roWcOptions['clear_expired_wc_sessions'] ){
		require_once RO_WC_DIR . 'includes/ro-wc-clear-expired-sessions.php';	
	}

	if( isset( $roWcOptions['lifetime_value'] ) && $roWcOptions['lifetime_value'] ){
		require_once RO_WC_DIR . 'includes/ro-wc-customer-lifetime-value.php';	
	} 
}

require_once RO_WC_DIR . 'includes/abandoned-cart/ro-wc-abandoned-cart-init.php';
require_once RO_WC_DIR . 'includes/abandoned-cart/ro-wc-abandoned-cart-ajax.php';
require RO_WC_DIR . 'includes/required-files/required-files.php';