<?php

namespace RoWooCommerce;

/*
 * Run the function to add adwords tracking pixel
 */
class RoAdwordsPixel {

	function __construct() {
		add_action( 'woocommerce_thankyou', array( $this, 'ro_adwords_tracking_pixel' ) );
	}

	function ro_adwords_tracking_pixel( $orderId ) {
		$ro_wc_options = get_option( 'ro_wc_options' );
		$order = new \WC_Order( $orderId );
		if( $order && ! get_post_meta( $orderId, 'ro_adwords_tracking_pixel', true ) ) :
		?>
			<!-- Google Code for Order Complete Conversion Page -->
			<script type="text/javascript">
				/* <![CDATA[ */
				var google_conversion_id = <?php echo $ro_wc_options['google_adwords_tracking_pixel_conversion_id'] ?>;
				var google_conversion_language = "en";
				var google_conversion_format = "1";
				var google_conversion_color = "ffffff";
				var google_conversion_label = "<?php echo $ro_wc_options['google_adwords_tracking_pixel_label'] ?>";
				var google_remarketing_only = <?php echo $ro_wc_options['google_adwords_tracking_pixel_remarketing_only'] ? 'true' : 'false' ?>;
				<?php if ( $order->get_total() ) : ?>
					var google_conversion_value = '<?php echo $order->get_total() ?>';
					var google_conversion_currency = '<?php echo $order->get_currency() ?>';
				<?php endif ?>
				/* ]]> */
			</script>
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
			<noscript>
				<div style="display:inline;">
					<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/<?php echo $ro_wc_options['google_adwords_tracking_pixel_conversion_id'] ?>/?value=<?php echo $order->get_total() ?>&amp;conversion_currency=<?php echo $order->get_currency() ?>&amp;label=<?php echo $ro_wc_options['google_adwords_tracking_pixel_label'] ?>&amp;guid=ON&amp;script=0"/>
				</div>
			</noscript>
			<!-- End Google Code for Order Complete Conversion Page -->
		<?php
			// update the post meta so it doesn't run again
			update_post_meta( $orderId, 'ro_adwords_tracking_pixel', '1' );
		endif;
	}
}

$roAdwordsPixel = new RoAdwordsPixel();
