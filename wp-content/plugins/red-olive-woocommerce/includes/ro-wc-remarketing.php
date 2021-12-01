<?php

namespace RoWooCommerce;

function ro_add_wc_remarketing() {
	global $roWcOptions;

	// Google Dynamic Remarketing
	if( isset( $roWcOptions['dynamic_remarketing'] ) && $roWcOptions['dynamic_remarketing'] )
	{
		if( function_exists('is_product') && is_product() ) {
			global $product;

			if( $product->product_type == 'variable' ) {
				$allVariations = $product->get_available_variations();
				$sku = $allVariations[0]['sku'];
			} else {
				$sku = $product->get_sku();
			}
			echo '
			<!-- Google Code for dynamic Remarketing added by ro-woocommerce -->
			<script type="text/javascript">
			var google_tag_params = {
			ecomm_prodid: "'. $sku .'",
			ecomm_pagetype: "product",
			ecomm_totalvalue: '. $product->get_price() .'
			};
			</script>
			<!-- End Google Code for dynamic Remarketing added by ro-woocommerce -->
			';
		}
	}
}
add_action( 'wp_footer', 'RoWooCommerce\ro_add_wc_remarketing', 9 );
