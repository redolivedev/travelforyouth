<?php

namespace RoWooCommerce;

//Add variables to the front end that jQuery will need to use
function infinite_scroll_variables(){

	global $wp_query;

	//Get the options setup in the backend
	$ro_wc_options = get_option( 'ro_wc_options' );

	//Set values specified by the user in the admin panel. If not specified, the default is used
	$buffer = $ro_wc_options['infinite_scroll_buffer'] ? $ro_wc_options['infinite_scroll_buffer'] : 100;
	$page_class = $ro_wc_options['infinite_scroll_paginator_class'] ? $ro_wc_options['infinite_scroll_paginator_class'] : 'page-numbers';
	$product_class = $ro_wc_options['infinite_scroll_product_class'] ? $ro_wc_options['infinite_scroll_product_class'] : 'products';

	echo 
	'
		<script type="text/javascript">
			var scrollBuffer = ' . $buffer . ';
			var pageClass = "' . $page_class . '";
			var prodClass = "' . $product_class . '";
			var maxPages = "'. $wp_query->max_num_pages . '";
			var pluginDir = "' . plugin_dir_url( dirname( __FILE__ ) ) . '";
		</script>
	';
}
add_action( 'wp_head', 'RoWooCommerce\infinite_scroll_variables' );