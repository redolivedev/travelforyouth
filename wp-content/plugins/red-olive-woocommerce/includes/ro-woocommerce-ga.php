<?php

namespace RoWooCommerce;

/**
 * WooCommerce Google Analytics Integration
**/
function ro_wc_ga_integration( $order_id ) {

	$analytics_recorded = get_post_meta( $order_id, 'google_analytics_recorded', true );

	if( ! $analytics_recorded ) :
		$order = new \WC_Order( $order_id );
		$subtotal = $order->get_subtotal() - $order->get_total_discount();

		?>
			<script type="text/javascript">
			ga('require', 'ecommerce', 'ecommerce.js'); // Load The Ecommerce Tracking Plugin

				// Transaction Details
				ga('ecommerce:addTransaction', {
					'id': '<?php echo $order_id;?>',
					'affiliation': '<?php echo get_option( "blogname" );?>',
					'revenue': '<?php echo $subtotal;?>',
					'shipping': '<?php echo $order->get_total_shipping();?>',
					'tax': '<?php echo $order->get_total_tax();?>',
					'currency': '<?php echo get_woocommerce_currency();?>'
				});


			<?php
				//Item Details
			if ( sizeof( $order->get_items() ) > 0 ) {
				foreach( $order->get_items() as $item ) {
					$product_cats = get_the_terms( $item["product_id"], 'product_cat' );
						if ($product_cats) {
							$cat = $product_cats[0];
						} ?>
					ga('ecommerce:addItem', {
						'id': '<?php echo $order_id;?>',
						'name': '<?php echo $item['name'];?>',
						'sku': '<?php echo get_post_meta($item["product_id"], '_sku', true);?>',
						'category': '<?php echo $cat->name;?>',
						'price': '<?php echo number_format( $item['line_subtotal'] / $item['qty'], 2 );?>',
						'quantity': '<?php echo $item['qty'];?>',
						'currency': '<?php echo get_woocommerce_currency();?>'
					});
			<?php
				}
			} ?>
				ga('ecommerce:send');
				</script>
			<?php update_post_meta( $order_id, 'google_analytics_recorded', '1' ); ?>
	<?php endif; ?>
<?php }
add_action( 'woocommerce_thankyou', 'RoWooCommerce\ro_wc_ga_integration' );
