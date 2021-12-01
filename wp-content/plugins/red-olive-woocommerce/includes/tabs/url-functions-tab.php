<?php

namespace RoWooCommerce;

class URLFunctionsTab{

	protected static $options;

	public static function init(){
		self::set_singleton();
		self::add_sections();
		self::add_fields();
	}

	private static function set_singleton(){
		$options_singleton = RoWooCommerceOptions::get_instance();
		self::$options = $options_singleton->get_options();
	}

	private static function add_sections(){
		/**
		 * URL FUNCTIONS
		 */
		add_settings_section(
            'ro_url_functions_section', // ID
            '', // Title
            array( __CLASS__, 'ro_url_functions_section_callback' ), // Callback
            'ro-wc-url-functions' // Page
        );
	}

	private static function add_fields(){

	}

	/**
	 * URL FUNCTIONS
	 */
	public static function ro_url_functions_section_callback(){
        ob_start();
        require RO_WC_DIR . 'includes/templates/url-functions.php';
        echo ob_get_clean();
    }
}