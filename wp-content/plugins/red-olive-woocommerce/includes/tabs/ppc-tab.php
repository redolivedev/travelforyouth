<?php

namespace RoWooCommerce;

class PPCTab{

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
		 * GOOGLE ADWORDS TRACKING PIXEL
		 */
		add_settings_section(
            'google_adwords_pixel', // ID
            'Google Ads Tracking Pixel', // Title
            array( __CLASS__, 'google_adwords_pixel_callback' ), // Callback
            'ro-wc-ppc' // Page
        );

        /*
         * GOOGLE ADWORDS REMARKETING
         */
        add_settings_section(
            'ro_wc_remarketing_section',
            'Google Ads Dynamic Remarketing',
            array( __CLASS__, 'ro_wc_remarketing_section_callback' ),
            'ro-wc-ppc'
        );

        /*
         * GOOGLE ADWORDS CUSTOMER REVIEWS
         */
        add_settings_section(
            'ro_wc_customer_reviews_section',
            'Google Ads Customer Reviews',
            array( __CLASS__, 'ro_wc_customer_reviews_section_callback' ),
            'ro-wc-ppc'
        );

        /*
         * BING ADS
         */
        add_settings_section(
            'ro_wc_bing_section',
            'Bing Ads',
            array( __CLASS__, 'ro_wc_bing_section_callback' ),
            'ro-wc-ppc'
        );
	}

	private static function add_fields(){

		/**
		 * GOOGLE ADWORDS TRACKING PIXEL
		 */
		add_settings_field(
            'google_adwords_tracking_pixel', // ID
            'Enable Google Ads Tracking Pixel', // Title
            array( __CLASS__, 'google_adwords_tracking_pixel_callback' ), // Callback
            'ro-wc-ppc', // Page
            'google_adwords_pixel' // Section
        );

        add_settings_field(
            'google_adwords_tracking_pixel_conversion_id',
            'Google Ads Conversion ID',
            array( __CLASS__, 'google_adwords_tracking_pixel_conversion_id_callback' ),
            'ro-wc-ppc',
            'google_adwords_pixel'
        );

        add_settings_field(
            'google_adwords_tracking_pixel_label',
            'Google Ads Label',
            array( __CLASS__, 'google_adwords_tracking_pixel_label_callback' ),
            'ro-wc-ppc',
            'google_adwords_pixel'
        );


        add_settings_field(
            'google_adwords_tracking_pixel_remarketing_only',
            'Google Ads Remarketing Only',
            array( __CLASS__, 'google_adwords_tracking_pixel_remarketing_only_callback' ),
            'ro-wc-ppc',
            'google_adwords_pixel'
        );

        /*
         * GOOGLE ADWORDS REMARKETING
         */
        add_settings_field(
            'dynamic_remarketing',
            'Enable Google Ads Dynamic Remarketing<br /> (Requires Remarketing turned on in RO Marketing > PPC Tab)',
            array( __CLASS__, 'ro_dynamic_remarketing_callback' ),
            'ro-wc-ppc',
            'ro_wc_remarketing_section'
        );

        /*
         * GOOGLE ADWORDS CUSTOMER REVIEWS
         */
        add_settings_field(
            'customer_reviews',
            'Enable Google Ads Customer Reviews',
            array( __CLASS__, 'ro_customer_reviews_callback' ),
            'ro-wc-ppc',
            'ro_wc_customer_reviews_section'
        );

        add_settings_field(
            'merchant_id',
            'Google Merchant ID',
            array( __CLASS__, 'ro_merchant_id_callback' ),
            'ro-wc-ppc',
            'ro_wc_customer_reviews_section'
        );

        add_settings_field(
            'estimated_delivery_period',
            'Estimated Delivery Time (in days)',
            array( __CLASS__, 'ro_estimated_delivery_period_callback' ),
            'ro-wc-ppc',
            'ro_wc_customer_reviews_section'
        );

        /*
         * BING ADS
         */
        add_settings_field(
            'bing_ads_pass_value',
            'Enable Pass Order Value',
            array( __CLASS__, 'ro_bing_ads_pass_value_callback' ),
            'ro-wc-ppc',
            'ro_wc_bing_section'
        );
	}

	/**
	 * GENERAL FUNCTIONS
	 */
	public static function put_an_hr(){
        print '<hr />';
    }

	/**
	 * GOOGLE ADWORDS TRACKING PIXEL CALLBACKS
	 */
    public static function google_adwords_tracking_pixel_callback(){
        printf(
            '<input type="checkbox" id="google_adwords_tracking_pixel" name="ro_wc_options[google_adwords_tracking_pixel]" %s />',
            checked( self::$options['google_adwords_tracking_pixel'], true, false )
        );
    }

    public static function google_adwords_tracking_pixel_conversion_id_callback(){
        printf(
            '<input type="text" id="google_adwords_tracking_pixel_conversion_id" placeholder="xxxxxxxxx" name="ro_wc_options[google_adwords_tracking_pixel_conversion_id]" value="%s" />',
            isset( self::$options['google_adwords_tracking_pixel_conversion_id'] ) ? esc_attr( self::$options['google_adwords_tracking_pixel_conversion_id']) : ''
        );
    }

    public static function google_adwords_tracking_pixel_label_callback(){
        printf(
            '<input type="text" id="google_adwords_tracking_pixel_label" placeholder="xxxxxxxxxx-xx-xxxxx" name="ro_wc_options[google_adwords_tracking_pixel_label]" value="%s" />',
            isset( self::$options['google_adwords_tracking_pixel_label'] ) ? esc_attr( self::$options['google_adwords_tracking_pixel_label']) : ''
        );
    }

    public static function google_adwords_tracking_pixel_remarketing_only_callback(){
        printf(
            '<input type="checkbox" id="google_adwords_tracking_pixel_remarketing_only" name="ro_wc_options[google_adwords_tracking_pixel_remarketing_only]" %s />',
            checked( self::$options['google_adwords_tracking_pixel_remarketing_only'], true, false )
        );
    }

    /**
     * GOOGLE ADWORDS TRACKING PIXEL
     */
    public static function google_adwords_pixel_callback()
    {
    	echo '<hr />Add the Google Ads Conversion Tracking Pixel to the order-received (checkout success) page and send the<br />
    	Total Conversion Value to your account to be tracked in the Google Ads Dashboard';
    }

    /*
     * GOOGLE ADWORDS REMARKETING
     */
    public static function ro_wc_remarketing_section_callback()
    {
        echo '<hr />Add Google Ads Dynamic Remarketing tag to product pages';
    }

    public static function ro_dynamic_remarketing_callback()
    {
        $checked_value = isset( self::$options['dynamic_remarketing'] ) 
            ? checked( self::$options['dynamic_remarketing'], true, false) 
            : false;
        printf( 
            '<input type="checkbox" value="1" id="dynamic_remarketing" name="ro_wc_options[dynamic_remarketing]" %s />', $checked_value 
        );
    }

    /*
     * GOOGLE ADWORDS CUSTOMER REVIEWS
     */
    public static function ro_wc_customer_reviews_section_callback()
    {
        echo '<hr />Add Google Ads Customer Reviews pop-up to checkout success pages.';
    }

    public static function ro_customer_reviews_callback()
    {
        $checked_value = isset( self::$options['customer_reviews'] ) 
            ? checked( self::$options['customer_reviews'], true, false ) 
            : false;
        printf( 
            '<input type="checkbox" value="1" id="customer_reviews" name="ro_wc_options[customer_reviews]" %s />', $checked_value 
        );
    }

    public static function ro_merchant_id_callback()
    {
        printf(
            '<input type="text" id="merchant_id" placeholder="xxxxxxxx" name="ro_wc_options[merchant_id]" value="%s" />',
            isset( self::$options['merchant_id'] ) ? esc_attr( self::$options['merchant_id']) : ''
        );
    }

    public static function ro_estimated_delivery_period_callback()
    {
        printf(
            '<input type="text" id="estimated_delivery_period" placeholder="5" name="ro_wc_options[estimated_delivery_period]" value="%s" />',
            isset( self::$options['estimated_delivery_period'] ) 
                ? esc_attr( self::$options['estimated_delivery_period']) 
                : ''
        );
    }

    /*
     * BING ADS
     */
    public static function ro_wc_bing_section_callback()
    {
        echo '<hr />Pass order value to the Bing Ads dashboard from checkout success page';
    }

    public static function ro_bing_ads_pass_value_callback()
    {
        $checked_value = isset( self::$options['bing_ads_pass_value'] ) ? checked( self::$options['bing_ads_pass_value'], true, false) : false;
        printf( '<input type="checkbox" value="1" id="bing_ads_pass_value" name="ro_wc_options[bing_ads_pass_value]" %s />', $checked_value );
    }
}
