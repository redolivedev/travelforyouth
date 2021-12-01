<?php

namespace RoWooCommerce;

class UXTab{

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
         * AUTOCOMPLETE ADDRESS
         */
        add_settings_section(
            'ro_wc_autocomplete_address', // ID
            'Google Maps Autocomplete Addresses', // Title
            array( __CLASS__, 'put_an_hr' ), // Callback
            'ro-wc-ux' // Page
        );

		/**
		 * INFINITE SCROLL
		 */
		add_settings_section(
            'ro_wc_infinite_scroll',
            'Infinite Scroll',
            array( __CLASS__, 'put_an_hr' ),
            'ro-wc-ux'
        );
	}

	private static function add_fields(){

		/**
         * AUTOCOMPLETE ADDRESS
         */
        add_settings_field(
            'enable_address_autocomplete', // ID
            'Enable autocomplete addresses in checkout form', // Title
            array( __CLASS__, 'enable_address_autocomplete_callback' ), // Callback
            'ro-wc-ux', // Page
            'ro_wc_autocomplete_address' // Section
        );

        add_settings_field(
            'google_api_key',
            'Google API Key<br />(Use ours or generate your own <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">HERE</a>)',
            array( __CLASS__, 'google_api_key_callback' ),
            'ro-wc-ux',
            'ro_wc_autocomplete_address'
        );

		/**
		 * INFINITE SCROLL
		 */
		add_settings_field(
            'enable_infinite_scroll',
            'Enable Infinite Scroll',
            array( __CLASS__, 'enable_infinite_scroll' ),
            'ro-wc-ux',
            'ro_wc_infinite_scroll'
        );

        add_settings_field(
            'infinite_scroll_buffer',
            'Infinite Scroll Buffer -<br/>
            <em>The distance in pixels from the bottom of the screen when the next page is pulled</em><br />
            (The default is 100)',
            array( __CLASS__, 'infinite_scroll_buffer' ),
            'ro-wc-ux',
            'ro_wc_infinite_scroll'
        );

        add_settings_field(
            'infinite_scroll_paginator_class',
            "Paginator Class -<br />
            <em>The class of the paginator HTML element which holds the 'next' page link</em><br />
            (The default is 'page-numbers')",
            array( __CLASS__, 'infinite_scroll_paginator_class' ),
            'ro-wc-ux',
            'ro_wc_infinite_scroll'
        );

        add_settings_field(
            'infinite_scroll_product_class',
            "Product Class -<br />
            <em>The class of the HTML element holding all of the products in a page</em><br />
            (The default is 'products')",
            array( __CLASS__, 'infinite_scroll_product_class' ),
            'ro-wc-ux',
            'ro_wc_infinite_scroll'
        );
	}

	/**
	 * GENERAL FUNCTIONS
	 */
	public static function put_an_hr(){
        print '<hr />';
    }

	/**
     * AUTOCOMPLETE ADDRESS CALLBACKS
     */
    public static function enable_address_autocomplete_callback(){
        printf(
            '<input type="checkbox" id="enable_address_autocomplete" name="ro_wc_options[enable_address_autocomplete]" %s />',
            checked( self::$options['enable_address_autocomplete'], true, false )
        );
    }

    public static function google_api_key_callback(){
        printf(
            '<input type="text" id="google_api_key" placeholder="AIzaSyAgN2SYjPPDqMD0mzeNJuGlgxHmA8Is518" name="ro_wc_options[google_api_key]" value="%s" />',
            isset( self::$options['google_api_key'] ) ? esc_attr( self::$options['google_api_key']) : ''
        );
    }

	/**
	 * INFINITE SCROLL CALLBACKS
	 */
    public static function enable_infinite_scroll(){
        printf(
            '<input type="checkbox" id="enable_infinite_scroll" name="ro_wc_options[enable_infinite_scroll]" %s />',
            checked( self::$options['enable_infinite_scroll'], true, false )
        );
    }

    public static function infinite_scroll_buffer(){
        printf(
            '<input type="text" id="infinite_scroll_buffer" placeholder="xxxxxxx" name="ro_wc_options[infinite_scroll_buffer]" value="%s" />',
            isset( self::$options['infinite_scroll_buffer'] ) ? esc_attr( self::$options['infinite_scroll_buffer']) : ''
        );
    }

    public static function infinite_scroll_paginator_class(){
        printf(
            '<input type="text" id="infinite_scroll_paginator_class" placeholder="xxxxxxx" name="ro_wc_options[infinite_scroll_paginator_class]" value="%s" />',
            isset( self::$options['infinite_scroll_paginator_class'] ) ? esc_attr( self::$options['infinite_scroll_paginator_class']) : ''
        );
    }

    public static function infinite_scroll_product_class(){
        printf(
            '<input type="text" id="infinite_scroll_product_class" placeholder="xxxxxxx" name="ro_wc_options[infinite_scroll_product_class]" value="%s" />',
            isset( self::$options['infinite_scroll_product_class'] ) ? esc_attr( self::$options['infinite_scroll_product_class']) : ''
        );
    }
}