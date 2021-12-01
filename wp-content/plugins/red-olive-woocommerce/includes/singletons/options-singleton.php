<?php

namespace RoWooCommerce;

final class RoWooCommerceOptions
{
	protected $options = array();
    
    /**
     * Call this method to get singleton
     *
     * @return UserFactory
     */
    public static function get_instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new self();
        }
        return $inst;
    }

    public function get_options(){
    	if( ! $this->options ) $this->options = get_option( 'ro_wc_options' );
    	return get_option( 'ro_wc_options' );
    }

    /**
     * Private constructor so nobody else can instance it
     */
    private function __construct(){}

    /**
     * Private clone so nobody else can instance it
     */
    private function __clone(){}

    /**
     * Throw exception on wakeup attempt
     */
    public function __wakeup(){
        throw new Exception('Cannot unserialize singleton');
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public static function sanitize( $input ){

        $new_input = array();

        // sanitize the array
        foreach ($input as $key => $value) {
            $new_input[$key] = sanitize_text_field( $value );
        }

        // figure out which checkboxes are checked
        $checkboxArray = array( 
        	'abandoned_cart_enabled', 
        	'abandoned_cart_3_day_enabled', 
        	'abandoned_cart_7_day_enabled', 
        	'bing_ads_pass_value', 
        	'clear_expired_wc_sessions', 
        	'dynamic_remarketing', 
        	'enable_infinite_scroll', 
        	'enable_address_autocomplete', 
        	'feeds_enable_global_custom_labels', 
        	'google_analytics', 
        	'google_adwords_tracking_pixel', 
        	'google_adwords_tracking_pixel_remarketing_only', 
        	'lifetime_value', 
        	'product_schema', 
        	'product_schema_aggregate_rating', 
        	'product_schema_category', 
        	'product_schema_image', 
        	'product_schema_price', 
        	'product_schema_availability', 
        	'product_schema_seller'
        );
        foreach( $checkboxArray as $checkboxField ) {
            $new_input[$checkboxField] = isset( $input[$checkboxField] ) ? 1 : 0;
        }

        //Check that the license key hasn't changed
        $options = get_option( 'ro_wc_options' );
        $old = $options['wc_license_key'];
        if( $old && $old != $new_input['wc_license_key'] ) {
            delete_option( 'red_olive_woocommerce_license_status' ); // new license has been entered, so must reactivate
        }

        return $new_input;
    }
}
