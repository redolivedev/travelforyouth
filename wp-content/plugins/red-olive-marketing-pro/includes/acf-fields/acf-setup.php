<?php

namespace RoMarketingPro;

class AcfSetup {
	public function __construct(){
		// 1. customize ACF path
		add_filter('acf/settings/path', array( $this, 'ro_marketing_acf_settings_path' ) );

		// 2. customize ACF dir
		add_filter('acf/settings/dir', array( $this, 'ro_marketing_acf_settings_dir' ) );

		// 3. Hide ACF field group menu item
		add_filter('acf/settings/show_admin', '__return_false');

		// 4. Include ACF
		include_once( RO_MARKETING_PRO_DIR . '/vendor/acf/acf.php' );
	}


	function ro_marketing_acf_settings_path( $path ) {
		// update path
		return RO_MARKETING_PRO_DIR . '/vendor/acf/';
	}

	function ro_marketing_acf_settings_dir( $dir ) {
		// update path
		return RO_MARKETING_PRO_URL . '/vendor/acf/';
	}

}

if( ! function_exists( 'get_field' ) ){
	new AcfSetup;
}
