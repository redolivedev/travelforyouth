<?php

namespace RoMarketingPro;

if( ! function_exists( 'RoMarketingPro\ro_force_https') ) {
	function ro_force_https () {
		if ( !is_ssl() ) {
			wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
			exit();
		}
	}
	add_action ( 'template_redirect', 'RoMarketingPro\ro_force_https', 1 );
}
