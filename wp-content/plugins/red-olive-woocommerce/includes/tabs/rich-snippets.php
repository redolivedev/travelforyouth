<?php

namespace RoWooCommerce;

class RichSnippetsTab{

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
		 * PRODUCT SCHEMA
		 */
		add_settings_section(
            'ro_product_schema',
            'Product Schema',
            array( __CLASS__, 'put_an_hr' ),
            'ro-wc-rich-snippets'
        );
	}

	private static function add_fields(){

		/**
		 * PRODUCT SCHEMA
		 */ 
		add_settings_field(
            'product_schema',
            'Enable Product Schema tags on product pages',
            array( __CLASS__, 'product_schema_callback' ),
            'ro-wc-rich-snippets',
            'ro_product_schema'
        );

        add_settings_field(
            'product_schema_aggregate_rating',
            'Include Aggregate Rating',
            array( __CLASS__, 'product_schema_aggregate_rating_callback' ),
            'ro-wc-rich-snippets',
            'ro_product_schema'
        );

        add_settings_field(
            'product_schema_category',
            'Include Category',
            array( __CLASS__, 'product_schema_category_callback' ),
            'ro-wc-rich-snippets',
            'ro_product_schema'
        );

        add_settings_field(
            'product_schema_image',
            'Include Image',
            array( __CLASS__, 'product_schema_image_callback' ),
            'ro-wc-rich-snippets',
            'ro_product_schema'
        );

        add_settings_field(
            'product_schema_price',
            'Include Price',
            array( __CLASS__, 'product_schema_price_callback' ),
            'ro-wc-rich-snippets',
            'ro_product_schema'
        );

        add_settings_field(
            'product_schema_availability',
            'Include Availability',
            array( __CLASS__, 'product_schema_availability_callback' ),
            'ro-wc-rich-snippets',
            'ro_product_schema'
        );

        add_settings_field(
            'product_schema_seller',
            'Include Seller',
            array( __CLASS__, 'product_schema_seller_callback' ),
            'ro-wc-rich-snippets',
            'ro_product_schema'
        );
	}

	/**
	 * GENERAL FUNCTIONS
	 */
	public static function put_an_hr(){
        print '<hr />';
    }

	/**
	 * PRODUCT SCHEMA CALLBACKS
	 */ 
    public static function product_schema_callback(){
        printf(
            '<input type="checkbox" id="product_schema" name="ro_wc_options[product_schema]" %s />',
            checked( self::$options['product_schema'], true, false )
        );
    }

    public static function product_schema_aggregate_rating_callback(){
    	printf(
            '<input type="checkbox" id="product_schema_aggregate_rating" name="ro_wc_options[product_schema_aggregate_rating]" %s />',
            checked( self::$options['product_schema_aggregate_rating'], true, false )
        );
    }

    public static function product_schema_category_callback(){
    	printf(
            '<input type="checkbox" id="product_schema_category" name="ro_wc_options[product_schema_category]" %s />',
            checked( self::$options['product_schema_category'], true, false )
        );
    }

    public static function product_schema_image_callback(){
    	printf(
            '<input type="checkbox" id="product_schema_image" name="ro_wc_options[product_schema_image]" %s />',
            checked( self::$options['product_schema_image'], true, false )
        );
    }

    public static function product_schema_price_callback(){
    	printf(
            '<input type="checkbox" id="product_schema_price" name="ro_wc_options[product_schema_price]" %s />',
            checked( self::$options['product_schema_price'], true, false )
        );
    }

    public static function product_schema_availability_callback(){
    	printf(
            '<input type="checkbox" id="product_schema_availability" name="ro_wc_options[product_schema_availability]" %s />',
            checked( self::$options['product_schema_availability'], true, false )
        );
    }

    public static function product_schema_seller_callback(){
    	printf(
            '<input type="checkbox" id="product_schema_seller" name="ro_wc_options[product_schema_seller]" %s />',
            checked( self::$options['product_schema_seller'], true, false )
        );
    }
}
