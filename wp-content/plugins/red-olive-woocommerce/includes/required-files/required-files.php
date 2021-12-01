<?php

// Get the options
global $roWcOptions;

// Lifetime value
if( isset( $roWcOptions['lifetime_value'] ) && $roWcOptions['lifetime_value'] ) 
	require_once RO_WC_DIR . 'includes/ro-wc-customer-lifetime-value-public.php';
// WooCommerce Google Analytics 
if( isset( $roWcOptions['google_analytics'] ) && $roWcOptions['google_analytics'] ) 
	require_once RO_WC_DIR . 'includes/ro-woocommerce-ga.php';

// AdWords tracking pixel
if( isset( $roWcOptions['google_adwords_tracking_pixel'] ) && $roWcOptions['google_adwords_tracking_pixel'] ) 
    require_once RO_WC_DIR . 'includes/ro-wc-adwords-pixel-public.php';
    
// Google AdWords Dynamic Remarketing
if( isset( $roWcOptions['dynamic_remarketing'] ) && $roWcOptions['dynamic_remarketing'] )
    require_once RO_WC_DIR . 'includes/ro-wc-remarketing.php';

// Google AdWords Customer Reviews
if( isset( $roWcOptions['customer_reviews'] ) && $roWcOptions['customer_reviews'] )
    require_once RO_WC_DIR . 'includes/google-customer-reviews/ro-wc-google-customer-reviews.php';

// Bing Ads WooCommerce Thank You
if( isset( $roWcOptions['bing_ads_pass_value'] ) && $roWcOptions['bing_ads_pass_value'] )
    require_once RO_WC_DIR . 'includes/ro-wc-bing-ads-woocommerce-thank-you.php';

// Public product feed
if( isset( $_GET['ro_product_feed'] ) || $_SERVER['REQUEST_URI'] === '/ro_product_feed/google' ) 
	require_once RO_WC_DIR . 'includes/ro-wc-product-feed-public.php';

// Product export to CSV
if( isset( $_GET['ro_product_export'] ) ) 
	require_once RO_WC_DIR . 'includes/ro-wc-product-export.php';

// Infinite scroll
if( isset( $roWcOptions['enable_infinite_scroll'] ) && $roWcOptions['enable_infinite_scroll'] ) 
	require_once RO_WC_DIR . 'includes/ro-wc-infinite-scroll.php';

// Abandoned cart
if( isset( $roWcOptions['abandoned_cart_enabled'] ) && $roWcOptions['abandoned_cart_enabled'] )
	require_once RO_WC_DIR . 'includes/abandoned-cart/ro-wc-abandoned-cart-fe-init.php';

// Autocomplete address in checkout
if( isset( $roWcOptions['enable_address_autocomplete'] ) && $roWcOptions['enable_address_autocomplete'] )
	require_once RO_WC_DIR . 'includes/ro-address-autocomplete.php';

// Product Schema
if( isset( $roWcOptions['product_schema'] ) && $roWcOptions['product_schema'] )
	require_once RO_WC_DIR . 'includes/product-schema/product-schema.php';

// URL coupons
if( RO_WC_WOOCOMMERCE_ACTIVE )
	require_once RO_WC_DIR . 'includes/ro-wc-url-coupons.php';

// URL products
if( RO_WC_WOOCOMMERCE_ACTIVE )
	require_once RO_WC_DIR . 'includes/ro-wc-url-products.php';

// Add email to mailchimp
require_once RO_WC_DIR . 'includes/ro-add-email-to-mailchimp/ro-add-email-to-mailchimp.php';
