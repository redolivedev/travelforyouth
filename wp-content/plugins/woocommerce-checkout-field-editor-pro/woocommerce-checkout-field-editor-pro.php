<?php
/**
 * Plugin Name: 	Checkout Field Editor for WooCommerce (Pro)
 * Plugin URI:  	https://www.themehigh.com/product/woocommerce-checkout-field-editor-pro/
 * Description: 	Design woocommerce checkout form in your own way, customize checkout fields(Add, Edit, Delete and re arrange fields).
 * Version:     	3.1.6
 * Author:      	ThemeHigh
 * Author URI:  	https://www.themehigh.com
 *
 * Text Domain: 	woocommerce-checkout-field-editor-pro
 * Domain Path: 	/languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 5.3.0
 */
 
if(!defined('WPINC')){	die; }

if (!function_exists('is_woocommerce_active')){
	function is_woocommerce_active(){
	    $active_plugins = (array) get_option('active_plugins', array());
	    if(is_multisite()){
		   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	    }
	    
	    if(in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) || class_exists('WooCommerce')){
	        return true;
	    }else{
	        return false;
	    }
	}
}

if(is_woocommerce_active()) {	
	define('THWCFE_VERSION', '3.1.6');
	!defined('THWCFE_SOFTWARE_TITLE') && define('THWCFE_SOFTWARE_TITLE', 'WooCommerce Checkout Field Editor');
	!defined('THWCFE_FILE_') && define('THWCFE_FILE_', __FILE__);
	!defined('THWCFE_PATH') && define('THWCFE_PATH', plugin_dir_path( __FILE__ ));
	!defined('THWCFE_URL') && define('THWCFE_URL', plugins_url( '/', __FILE__ ));
	!defined('THWCFE_BASE_NAME') && define('THWCFE_BASE_NAME', plugin_basename( __FILE__ ));

	/**
	 * The code that runs during plugin activation.
	 */
	function activate_thwcfe() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-thwcfe-activator.php';
		THWCFE_Activator::activate();
	}
	
	/**
	 * The code that runs during plugin deactivation.
	 */
	function deactivate_thwcfe() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-thwcfe-deactivator.php';
		THWCFE_Deactivator::deactivate();
	}
	
	register_activation_hook( __FILE__, 'activate_thwcfe' );
	register_deactivation_hook( __FILE__, 'deactivate_thwcfe' );

	function thwcfe_license_form_title_note($title_note){
		$help_doc_url = 'https://www.themehigh.com/help-guides/general-guides/download-purchased-plugin-file';

		$title_note .= ' Find out how to <a href="%s" target="_blank">get your license key</a>.';
		$title_note  = sprintf($title_note, $help_doc_url);
		return $title_note;
	}

	function thwcfe_license_page_url($url, $prefix){
		$url = 'admin.php?page=th_checkout_field_editor_pro&tab=license_settings';
		return admin_url($url);
	}

	function init_auto_updater_thwcfe(){
		if(!class_exists('THWCFE_License_Manager') ) {
			add_filter('thlm_license_form_title_note_woocommerce_checkout_field_editor', 'thwcfe_license_form_title_note');
			add_filter('thlm_license_page_url_woocommerce_checkout_field_editor', 'thwcfe_license_page_url', 10, 2);
			add_filter('thlm_enable_default_license_page', '__return_false');

			require_once( plugin_dir_path( __FILE__ ) . 'class-thwcfe-license-manager.php' );
			$api_url = 'https://themehigh.com/';
			THWCFE_License_Manager::instance(__FILE__, $api_url, 'plugin', THWCFE_SOFTWARE_TITLE);
		}
	}
	init_auto_updater_thwcfe();

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-thwcfe.php';
	
	/**
	 * Begins execution of the plugin.
	 */
	function run_thwcfe() {
		$plugin = new THWCFE();
		$plugin->run();
	}
	run_thwcfe();

	/**
	 * Returns helper class instance.
	 */
	function get_thwcfe_helper(){
		return new THWCFE_Functions();
	}	
}