<?php

namespace RoWooCommerce;

function ro_bing_ads_thank_you( $orderId ) {
	$order = new \WC_Order( $orderId );
	if( $order && ! get_post_meta( $orderId, 'ro_bing_ads_thank_you_ran', true ) ) :
	?>
		<!-- Bing ads added by ro-woocommerce -->
		<script>
		 window.uetq = window.uetq || [];
		 window.uetq.push({ 'gv': <?php echo $order->get_total(); ?> });
		</script>
		<!-- End Bing ads added by ro-woocommerce -->
	<?php
		// update the post meta so it doesn't run again
		update_post_meta( $orderId, 'ro_bing_ads_thank_you_ran', '1' );
	endif;
}

add_action( 'woocommerce_thankyou', 'RoWooCommerce\ro_bing_ads_thank_you' );
