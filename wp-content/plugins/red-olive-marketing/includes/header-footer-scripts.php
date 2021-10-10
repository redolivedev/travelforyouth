<?php

namespace RoMarketing;

function ro_marketing_header_scripts(){
	global $marketingOptions;
	echo isset( $marketingOptions['header_scripts'] ) ? $marketingOptions['header_scripts'] : false;
}
add_action( 'wp_head', 'RoMarketing\ro_marketing_header_scripts' );

function ro_marketing_footer_scripts(){
	global $marketingOptions;
	echo isset( $marketingOptions['footer_scripts'] ) ? $marketingOptions['footer_scripts'] : false;
}
add_action( 'wp_footer', 'RoMarketing\ro_marketing_footer_scripts' );
