<?php

// Get the options
$marketingOptions = get_option( 'ro_marketing_options' );

// Add the ga script
if( isset( $marketingOptions['google_analytics'] ) && $marketingOptions['google_analytics'] && $marketingOptions['google_analytics_account_id'] )
	require_once RO_MARKETING_DIR . 'includes/ga-script.php';

// Add Google Experiment Tag script
if( isset( $marketingOptions['google_experiment_tag'] ) && $marketingOptions['google_experiment_tag'] && $marketingOptions['google_experiment_tag_id'] )
	require_once RO_MARKETING_DIR . 'includes/ga-experiment-script.php';

// Add the Google Tag Manager script
if( isset( $marketingOptions['google_tag_manager'] ) && $marketingOptions['google_tag_manager'] && $marketingOptions['google_tag_manager_account_id'] ){

    /**
     * Check if the active header.php file has the after_opening_body tag. If not, display an admin warning banner.
     */

    // If the active theme has a parent, get its path. We might need it.
	if( wp_get_theme()->parent() ){
        $parent_stylesheet_directory = wp_get_theme()->parent()->get_stylesheet_directory();
    }
    
    // Get the active theme's path
    $stylesheet_directory = wp_get_theme()->get_stylesheet_directory();

    // Check if the active theme has a header.php file. If not check its parent for one
    if( file_exists( $stylesheet_directory . '/header.php' ) ){
        $header_file = $stylesheet_directory . '/header.php';
    }else if( file_exists( $parent_stylesheet_directory . '/header.php' ) ){
        $header_file = $parent_stylesheet_directory . '/header.php';
    }

    // Check for the after_opening_body tag in the header file
	if( $header_file ){
		if( strpos( file_get_contents( $header_file ), 'after_opening_body' ) !== false ){
			require_once RO_MARKETING_DIR . 'includes/gtm-script.php';
		}elseif( is_admin() ){
			require_once RO_MARKETING_DIR . 'includes/gtm-script-admin-warning.php';
		}
	}
}

// Call tracking plugin
if( isset( $marketingOptions['call_tracking'] ) && $marketingOptions['call_tracking'] && $marketingOptions['call_tracking_account_id'] )
	require_once RO_MARKETING_DIR . 'includes/call-tracking.php';

// Header Footer scripts
if( ! is_admin() ) require_once RO_MARKETING_DIR . 'includes/header-footer-scripts.php';

// Crazy Egg
if( isset( $marketingOptions['add_crazy_egg'] ) && $marketingOptions['add_crazy_egg'] )
	require_once RO_MARKETING_DIR . 'includes/crazy-egg.php';

// Track Duck
if( isset( $marketingOptions['add_track_duck'] ) && $marketingOptions['add_track_duck'] && isset( $marketingOptions['track_duck_id'] ) && $marketingOptions['track_duck_id'] )
	require_once RO_MARKETING_DIR . 'includes/track-duck.php';

// Google AdWords Remarketing
if( isset( $marketingOptions['standard_remarketing'] ) && $marketingOptions['standard_remarketing'])
	require_once RO_MARKETING_DIR . 'includes/remarketing.php';

// Bing Ads Tracking Code
if( isset( $marketingOptions['bing_ads'] ) && $marketingOptions['bing_ads'] && $marketingOptions['bing_ads_account_id'] )
	require_once RO_MARKETING_DIR . 'includes/bing-ads.php';

// Live Chat Script
if( isset( $marketingOptions['enable_live_chat'] ) && $marketingOptions['enable_live_chat'] && $marketingOptions['live_chat_license_number'] )
	require_once RO_MARKETING_DIR . 'includes/live-chat-script.php';

// Olark Script
if( isset( $marketingOptions['enable_olark'] ) && $marketingOptions['enable_olark'] && $marketingOptions['olark_site_id'] )
	require_once RO_MARKETING_DIR . 'includes/olark-script.php';

// LinkedIn Insight Script
if( isset( $marketingOptions['linkedin_insight'] ) && $marketingOptions['linkedin_insight'] && $marketingOptions['linkedin_insight_partner_id'] )
	require_once RO_MARKETING_DIR . 'includes/linkedin-insight-script.php';

// Facebook Pixel Script
if( isset( $marketingOptions['ro_facebook_pixel'] ) && $marketingOptions['ro_facebook_pixel'] && $marketingOptions['ro_facebook_pixel_id'] )
	require_once RO_MARKETING_DIR . 'includes/facebook-pixel-script.php';