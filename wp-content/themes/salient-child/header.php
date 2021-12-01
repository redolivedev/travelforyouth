<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
	
	$nectar_options = get_nectar_theme_options();
	
	if ( ! empty( $nectar_options['responsive'] ) && '1' === $nectar_options['responsive'] ) { 
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />';
	} else { 
		echo '<meta name="viewport" content="width=1200" />';
	} 

	if ( is_wc_endpoint_url( 'order-received' ) ) {
		global $wp;
		//Get Order ID
		$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
		$order = wc_get_order( $order_id );
	
		foreach( $order->get_items() as $item ) {
			if( $item['product_id'] == 1231 ) {
				// wp_redirect( '/waitlist-thank-you/' );
				// exit;
				echo '<meta http-equiv="refresh" content="0;url=https://travelforyouth.org/waitlist-thank-you/" />';
			} else {
				// wp_redirect( '/thank-you/' );
				// exit;
				echo '<meta http-equiv="refresh" content="0;url=https://travelforyouth.org/thank-you/" />';
			}
		}

		//echo '<meta http-equiv="refresh" content="1;url=https://staging.travelforyouth.org/thank-you/" />';
		//$current_order_id =  intval( str_replace( 'checkout/order-received/', '', $wp->request ) );
		//echo $current_order_id;
	}


	
	// Shortcut icon fallback.
	if ( ! empty( $nectar_options['favicon'] ) && ! empty( $nectar_options['favicon']['url'] ) ) {
		echo '<link rel="shortcut icon" href="'. esc_url( nectar_options_img( $nectar_options['favicon'] ) ) .'" />';
	}
	
	wp_head();
	
?>
</head><?php

$nectar_header_options = nectar_get_header_variables();

?><body <?php body_class(); ?> <?php nectar_body_attributes(); ?>>
	<?php do_action( 'after_opening_body' ); ?>
	<?php
	
	nectar_hook_after_body_open();
	
	nectar_hook_before_header_nav();
	
	// Boxed theme option opening div.
	if ( $nectar_header_options['n_boxed_style'] ) {
		echo '<div id="boxed">';
	}
	
	get_template_part( 'includes/partials/header/header-space' );
	
	?>
	<div id="header-outer" <?php nectar_header_nav_attributes(); ?>>
		<?php
		
		get_template_part( 'includes/partials/header/secondary-navigation' );
		
		if ('ascend' !== $nectar_header_options['theme_skin'] && 
			  'left-header' !== $nectar_header_options['header_format']) {
			get_template_part( 'includes/header-search' );
		}
		
		get_template_part( 'includes/partials/header/header-menu' );
		
		
		?>
		
	</div>
	<?php
	
	if ( ! empty( $nectar_options['enable-cart'] ) && '1' === $nectar_options['enable-cart'] ) {
		get_template_part( 'includes/partials/header/woo-slide-in-cart' );
	}
	
	if ( 'ascend' === $nectar_header_options['theme_skin'] || 
		   'left-header' === $nectar_header_options['header_format'] && 
		   'false' !== $nectar_header_options['header_search'] ) {
		get_template_part( 'includes/header-search' ); 
	}
	
	?>
	<div id="ajax-content-wrap">
<?php
		
		nectar_hook_after_outer_wrap_open();
