<?php

namespace RoWooCommerce;

class SettingsTab{

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
         * GENERAL SETTINGS
         */
        add_settings_section(
            'ro_wc_general',
            'General Settings',
            array( __CLASS__, 'put_an_hr' ),
            'ro-wc-settings'
        );
	}

	private static function add_fields(){

        /**
         * GENERAL SETTINGS
         */
        add_settings_field(
            'clear_expired_wc_sessions',
            'Clear WooCommerce expired sessions automatically',
            array( __CLASS__, 'clear_expired_wc_sessions' ),
            'ro-wc-settings',
            'ro_wc_general'
        );
	}

	/**
	 * GENERAL FUNCTIONS
	 */
	public static function put_an_hr(){
        print '<hr />';
    }

     /**
     * GENERAL SETTINGS CALLBACKS
     */
    public static function clear_expired_wc_sessions(){
        printf(
            '<input type="checkbox" id="clear_expired_wc_sessions" name="ro_wc_options[clear_expired_wc_sessions]" %s />',
            checked( self::$options['clear_expired_wc_sessions'], true, false )
        );
    }
}