<?php

namespace RoMarketingPro;

function ro_enqueue_external_links_scripts(){
	wp_enqueue_script( 'external_links', RO_MARKETING_PRO_URL . 'assets/js/frontEndExternalLinks.js', array('jquery'), 1.0, true );
}
add_action( 'wp_enqueue_scripts', 'RoMarketingPro\ro_enqueue_external_links_scripts' );
