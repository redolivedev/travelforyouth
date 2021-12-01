<?php
/**
 * Plugin Name:       Multi-Step Checkout for WooCommerce (Pro)
 * Plugin URI:        https://themehigh.com/product/woocommerce-multistep-checkout
 * Description:       Multi-Step Checkout for WooCommerce plugin helps you to split the WooCommerce checkout form into simpler steps.
 * Version:           2.0.2
 * Author:            ThemeHigh
 * Author URI:        https://themehigh.com/
 *
 * Text Domain:       woocommerce-multistep-checkout
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 5.5.1
 */

if(!defined('WPINC')){	die; }

if (!function_exists('is_woocommerce_active')){
	function is_woocommerce_active(){
	    $active_plugins = (array) get_option('active_plugins', array());
	    if(is_multisite()){
		   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	    }
	    return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) || class_exists('WooCommerce'); 
	}
}

if(is_woocommerce_active()) {
	define('THWMSC_VERSION', '2.0.2');
	!defined('THWMSC_SOFTWARE_TITLE') && define('THWMSC_SOFTWARE_TITLE', 'WooCommerce Multistep Checkout');
	!defined('THWMSC_FILE') && define('THWMSC_FILE', __FILE__);
	!defined('THWMSC_PATH') && define('THWMSC_PATH', plugin_dir_path( __FILE__ ));
	!defined('THWMSC_URL') && define('THWMSC_URL', plugins_url( '/', __FILE__ ));
	!defined('THWMSC_BASE_NAME') && define('THWMSC_BASE_NAME', plugin_basename( __FILE__ ));
	
	/**
	 * The code that runs during plugin activation.  
	 */
	function activate_thwmsc() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-thwmsc-activator.php';
		THWMSC_Activator::activate();
	}
	
	/**
	 * The code that runs during plugin deactivation.
	 */
	function deactivate_thwmsc() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-thwmsc-deactivator.php';
		THWMSC_Deactivator::deactivate();
	}
	
	register_activation_hook( __FILE__, 'activate_thwmsc' );
	register_deactivation_hook( __FILE__, 'deactivate_thwmsc' );
	
	function thwmsc_license_form_title_note($title_note){
        $help_doc_url = 'https://www.themehigh.com/help-guides/general-guides/download-purchased-plugin-file';

        $title_note .= sprintf(__(' Find out how to <a href="%s" target="_blank">get your license key</a>.', 'woocommerce-multistep-checkout'),$help_doc_url);
        // $title_note  = sprintf($title_note, $help_doc_url);
        return $title_note;
    }

	function thwmsc_license_page_url($url, $prefix){
		$url = 'admin.php?page=th_multi_step_checkout&tab=license_settings';
		return admin_url($url);
	}

	function init_auto_updater_thwmsc(){
		if(!class_exists('THWMSC_License_Manager') ) {
			add_filter('thlm_license_form_title_note_woocommerce_multistep_checkout', 'thwmsc_license_form_title_note');
			add_filter('thlm_license_page_url_woocommerce_multistep_checkout', 'thwmsc_license_page_url', 10, 2);
			add_filter('thlm_enable_default_license_page', '__return_false');

			require_once( plugin_dir_path( __FILE__ ) . 'class-thwmsc-license-manager.php' );
			$api_url = 'https://themehigh.com/';
			THWMSC_License_Manager::instance(__FILE__, $api_url, 'plugin', THWMSC_SOFTWARE_TITLE);
		}
	}
	init_auto_updater_thwmsc();
	
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-thwmsc.php';
	
	/**
	 * Begins execution of the plugin.
	 */
	function run_thwmsc() {
		$plugin = new THWMSC();
		$plugin->run();
	}
	run_thwmsc();
}