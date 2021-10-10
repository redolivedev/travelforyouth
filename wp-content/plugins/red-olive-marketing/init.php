<?php

namespace RoMarketing;

class RoMarketingAdminPage {

	function __construct() {
		add_action( 'wp_head', array( $this, 'frontend_ajaxurl' ), 5 );
		add_action( 'init', array( $this, 'ro_start_session' ) );
		add_action('admin_enqueue_scripts', array( $this, 'include_ro_marketing_settings_scripts' ) );
	}

	//Start up the session if it doesn't exist yet
	function ro_start_session(){
		ro_session_start();
	}

	//Add the ajaxurl to the front end for ajax calls
	function frontend_ajaxurl(){
		?>
		<script type="text/javascript">
		var frontEndAjaxURL = "<?php echo admin_url('admin-ajax.php'); ?>";
		</script>
		<?php
	}

	public function include_ro_marketing_settings_scripts(){
		wp_enqueue_style( 'ro_marketing_settings_css', RO_MARKETING_URL . 'assets/css/ro-marketing-settings.css' );
	}
}
new RoMarketingAdminPage;

if( ! function_exists( 'ro_session_start' ) ) {
	function ro_session_start(){
		if( php_sapi_name() !== 'cli' ) {
			if( version_compare( phpversion(), '5.4.0', '>=' ) ) {
				if( session_status() != PHP_SESSION_ACTIVE ) session_start();
			} else {
				if( session_id() === '' ) session_start();
			}
		}
	}
}

// Automatically update plugins
add_filter( 'auto_update_plugin', '__return_true' );

// Add the settings page and tab files
if( is_admin() ) {
	require_once RO_MARKETING_DIR . 'red-olive-marketing-settings-page.php';
	require RO_MARKETING_DIR . 'includes/singletons/options-singleton.php';
	require RO_MARKETING_DIR . 'includes/tabs/qa-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/cro-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/ppc-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/email-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/promo-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/social-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/reviews-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/general-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/scripts-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/settings-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/live-chat-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/local-seo-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/redirects-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/call-tracking-tab.php';
	require RO_MARKETING_DIR . 'includes/tabs/feature-request-tab.php';
}

//Get all of the required files
require RO_MARKETING_DIR . 'includes/required-files/required-files.php';
