<?php

// Get the options
$marketingOptions = get_option( 'ro_marketing_options' );

// device detection class
if( ! class_exists( 'Mobile_Detect' ) )
	require_once RO_MARKETING_PRO_DIR . 'includes/class-mobile-detect.php';

// Clear expired sessions
if( isset( $marketingOptions['clear_expired_sessions'] ) && $marketingOptions['clear_expired_sessions'] )
	require_once RO_MARKETING_PRO_DIR . 'includes/clear-expired-sessions.php';

// Add the AB Testing script
require_once RO_MARKETING_PRO_DIR . 'includes/ab-testing/ro-ab-testing.php';

// Stop comment spam section
if( isset( $marketingOptions['stop_comment_spam'] ) && $marketingOptions['stop_comment_spam'] )
	require_once RO_MARKETING_PRO_DIR . 'includes/stop-comment-spam.php';

// Rename Media Library files
if( isset( $marketingOptions['enable_media_file_renaming'] ) && $marketingOptions['enable_media_file_renaming'] )
	require_once RO_MARKETING_PRO_DIR . 'includes/rename-media-files.php';

// Force HTTPS
if( isset( $marketingOptions['force_https'] ) && $marketingOptions['force_https'] )
	require_once RO_MARKETING_PRO_DIR . 'includes/force-https.php';

// MailChimp Widget
require_once RO_MARKETING_PRO_DIR . 'includes/mailchimp-widget/mailchimp-widget.php';

// Social media reviews
if( isset( $marketingOptions['social_media_reviews'] ) && $marketingOptions['social_media_reviews'] )
	require_once RO_MARKETING_PRO_DIR . 'includes/social-media-reviews.php';

// Open External Links in New Tab
if( isset( $marketingOptions['external_links'] ) && $marketingOptions['external_links'] )
	require_once RO_MARKETING_PRO_DIR . 'includes/external-links.php';

// Redirects
require_once RO_MARKETING_PRO_DIR . 'includes/redirects/redirects.php';

// KML Sitemap
require_once RO_MARKETING_PRO_DIR . 'includes/kml-sitemap/classes/class-kml-sitemap.php';

// Site-wide Banner
require_once RO_MARKETING_PRO_DIR . 'includes/site-wide-banner/classes/class-site-wide-banner.php';

require_once RO_MARKETING_PRO_DIR . 'includes/ro-session.php';

// Floating CTA
require_once RO_MARKETING_PRO_DIR . 'includes/floating-cta/classes/class-floating-cta.php';

//scripts
require_once RO_MARKETING_PRO_DIR . 'includes/scripts/classes/scripts.php';

// Pop Up Ads
require_once RO_MARKETING_PRO_DIR . 'includes/pop-up-ads/classes/pop-up-ads-public.php';

// NAP Builder
require_once RO_MARKETING_PRO_DIR . 'includes/nap-builder/classes/nap-builder.php';
