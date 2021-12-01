<?php 
add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles', 100);

function salient_child_enqueue_styles() {
		
		$nectar_theme_version = nectar_get_theme_version();
		wp_enqueue_style( 'salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version );
		
    if ( is_rtl() ) {
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
		}
}
function ro_scripts() {
	if ( is_home() || is_front_page() ) {
		wp_enqueue_style(  'ro_sass',  get_stylesheet_directory_uri(). '/dist/css/app.css', array(), '3', 'all' );
		wp_enqueue_style(  'ro_owlcss',  get_stylesheet_directory_uri(). '/src/owl.carousel.min.css', array(), '1', 'all' );
		wp_enqueue_script(  'ro_owljs',  get_stylesheet_directory_uri(). '/src/owl.carousel.min.js', array('jquery'), '2', true );		
		wp_enqueue_script(  'ro_js',  get_stylesheet_directory_uri(). '/dist/js/app.js', array('jquery'), '1', true );
	}
	wp_enqueue_style(  'ro_woocss',  get_stylesheet_directory_uri(). '/ro-woo.css', array(), '1', 'all' );
}

add_action('wp_enqueue_scripts', 'ro_scripts', 200);

/**
 * @snippet       WooCommerce Max 1 Product @ Cart
 */
  
add_filter( 'woocommerce_add_to_cart_validation', 'bbloomer_only_one_in_cart', 9999, 2 );
add_filter( 'wc_add_to_cart_message_html', '__return_false' );  
function bbloomer_only_one_in_cart( $passed, $added_product_id ) {
   wc_empty_cart();
   return $passed;
}
function ro_remove_quantity_fields( $return, $product ) {
    return true;
}
add_filter( 'woocommerce_is_sold_individually', 'ro_remove_quantity_fields', 10, 2 );


/**
 * Auto Complete all WooCommerce orders.
 */
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}


//Skip stright to checkout  
add_filter('woocommerce_add_to_cart_redirect', 'ro_skip_cart_page');
	function ro_skip_cart_page () {
	global $woocommerce;
	$redirect_checkout = wc_get_checkout_url();
	return $redirect_checkout;
}



//This makes a shortcode for a variable product to be selected * Donate Page

function add_to_cart_form_shortcode( $atts ) {
	if ( empty( $atts ) ) {
		return '';
	}

	if ( ! isset( $atts['id'] ) && ! isset( $atts['sku'] ) ) {
		return '';
	}

	$args = array(
		'posts_per_page'      => 1,
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1,
	);

	if ( isset( $atts['sku'] ) ) {
		$args['meta_query'][] = array(
			'key'     => '_sku',
			'value'   => sanitize_text_field( $atts['sku'] ),
			'compare' => '=',
		);

		$args['post_type'] = array( 'product', 'product_variation' );
	}

	if ( isset( $atts['id'] ) ) {
		$args['p'] = absint( $atts['id'] );
	}

	$single_product = new WP_Query( $args );

	$preselected_id = '0';


	if ( isset( $atts['sku'] ) && $single_product->have_posts() && 'product_variation' === $single_product->post->post_type ) {

		$variation = new WC_Product_Variation( $single_product->post->ID );
		$attributes = $variation->get_attributes();


		$preselected_id = $single_product->post->ID;


		$args = array(
			'posts_per_page'      => 1,
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'p'                   => $single_product->post->post_parent,
		);

		$single_product = new WP_Query( $args );
	?>

		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				var $variations_form = $( '[data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>"]' ).find( 'form.variations_form' );
				<?php foreach ( $attributes as $attr => $value ) { ?>
					$variations_form.find( 'select[name="<?php echo esc_attr( $attr ); ?>"]' ).val( '<?php echo esc_js( $value ); ?>' );
				<?php } ?>
			});
		</script>
	<?php
	}

	$single_product->is_single = true;
	ob_start();
	global $wp_query;

	$previous_wp_query = $wp_query;

	$wp_query          = $single_product;

	wp_enqueue_script( 'wc-single-product' );
	while ( $single_product->have_posts() ) {
		$single_product->the_post();
		?>
		<div class="ro-shortcode">
			<script>
				jQuery( document ).ready( function( $ ) {
					var $only = $('select option').length;
					$('.ro-shortcode').addClass('select-drops-'+ $only);
				});
			</script>
			<h6 class="yellow">Trip Dates</h6>
			<div class="single-product" data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>">
				<?php woocommerce_template_single_add_to_cart(); ?>
				<div class="info"><i class="fas fa-asterisk"></i><?php the_field('deposit_text', 'option'); ?></div>
			</div>
		</div>
		<?php
	}

	$wp_query = $previous_wp_query;

	wp_reset_postdata();
	return '<div class="woocommerce">' . ob_get_clean() . '</div>';
}
/*Example Usage [add_to_cart_form id=54]*/
add_shortcode( 'add_to_cart_form', 'add_to_cart_form_shortcode' );


add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'filter_dropdown_option_html', 12, 2 );
function filter_dropdown_option_html( $html, $args ) {
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' );
    $show_option_none_html = '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

    $html = str_replace($show_option_none_html, '', $html);

    return $html;
}



add_filter( 'woocommerce_get_availability', 'customize_product_availability', 10, 2);
function customize_product_availability( $availability, $product ) {
    // product not in stock and not applicable for backorder
    if ( ! $product->is_in_stock() && ! $product->is_on_backorder()) {
        $text = __('Currently Sold Out!<br/><a href="/waitlist" target="_blank">Please click here to learn more about the waitlist</a>.', 'astra');
        $icon = 'times-circle';
        $color = 'color:#e2401c;';
    }
    //product not in stock, but backorder is ok -> dropshipping
    elseif( $product->managing_stock() && $product->is_on_backorder() ){
        $text = __('Order with backorder', 'astra');
        $icon = 'check-circle';
        $color = 'color:orange;';
    }
    //product is in stock (nothing special)
    elseif( $product->is_in_stock() && $product->managing_stock() ){
		$stock = $product->get_stock_quantity();
		if($stock<=5){
			$text = __('Only '.$stock.' Spots Left!', 'astra');
		}
        $icon = 'check-circle';
        $color = 'color:#0f834d;';
    }

    //$availability['availability'] = '<span style="'.$color.'"><i class="fa far fa-'.$icon.' fa-lg"></i> '.$text .'</span>';
	$availability['availability'] = '<span class="stock">'.$text .'</span>';
    return $availability;
}


//Here is if we need to switch different buttons
// For all products except variable product
// add_filter( 'woocommerce_product_single_add_to_cart_text', 'product_single_add_to_cart_text_filter_callback', 20, 2 );
// function product_single_add_to_cart_text_filter_callback( $button_text, $product ) {
//     if( ! $product->is_in_stock() && ! $product->is_type('variable') ) {
//         $button_text = __("Join the Waitlist", "woocommerce");
//     }
//     return $button_text;
// }


add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' ); 
function woocommerce_custom_single_add_to_cart_text() {
    return __( 'I’m Coming! Register', 'woocommerce' ); 
}

function wc_shop_waitlist_button() {
    echo '<a class="waitlist-button" href="/checkout?add-to-cart=1231">Join the Waitlist</a>';
}
add_action( 'woocommerce_after_add_to_cart_button', 'wc_shop_waitlist_button' );


// Set custom data as custom cart data in the cart item Saves data
// add_filter( 'woocommerce_add_cart_item_data', 'save_custom_data_in_cart_object', 30, 3 );
// function save_custom_data_in_cart_object( $cart_item_data, $product_id, $variation_id ) {
// 	if( ! isset($_GET['billing_trip_ID']) ){

// 		WC()->session->__unset( 'cart_meta', null);
// 		WC()->session->__unset( 'cart_meta_ID', null);
// 		return $cart_item_data; // Exit
// 	}

//     // Get the data from the GET request
    
//     $custom_reference_meta = esc_attr( $_GET['billing_trip_ID'] );
// 	$custom_price          = get_the_title( $custom_reference_meta );

//     // Set the data as custom cart data for the cart item
//     $cart_item_data['custom_data']['custom_price'] = esc_attr( $custom_price  );
//     $cart_item_data['custom_data']['custom_reference_meta'] = esc_attr( $custom_reference_meta );

//     return $cart_item_data;
// }


// Optionally display Custom data in cart and checkout pages SETs Custompass
// add_filter( 'woocommerce_get_item_data', 'custom_data_on_cart_and_checkout', 99, 2 );
// function custom_data_on_cart_and_checkout( $cart_data, $cart_item = null ) {
// 	global $woocommerce;
//     if( isset( $cart_item['custom_data']['custom_price'] ) ){
// 		$cart_data[] = array(
//             'name' => 'Trip Location',
//             'value' => $cart_item['custom_data']['custom_price']
//         );
		
// 		WC()->session->__unset( 'cart_meta');
// 		$trip_name = $cart_item['custom_data']['custom_price'];
// 		WC()->session->set( 'cart_meta', $trip_name);
// 	} 
	

//     if( isset( $cart_item['custom_data']['custom_reference_meta'] ) ) {
// 		$cart_data[] = array(
//             'name' => 'Trip ID#',
//             'value' => $cart_item['custom_data']['custom_reference_meta']
//         );

// 		WC()->session->__unset( 'cart_meta_ID');
// 		$trip_id = $cart_item['custom_data']['custom_reference_meta'];
// 		WC()->session->set( 'cart_meta_ID', $trip_id);
// 	} 
        
		
		
//     return $cart_data;
// }

//ADDS NEW FIELD AUTO POPULATE
// function override_checkout_email_field( $fields  ) {
    
//    $trip_name = WC()->session->get('cart_meta');

//     if(!is_null($trip_name) || !isset($trip_name) ) {
//       $fields['billing']['billing_trip']['default'] = $trip_name;
//     } else {
// 		$fields['billing']['billing_trip']['default'] = 'NEW';
// 	}

// 	$trip_id = WC()->session->get('cart_meta_ID');

//     if(!is_null($trip_id) || !isset($trip_id) ) {
//       $fields['billing']['billing_tripid']['default'] = $trip_id;
//     } else {
// 		$fields['billing']['billing_tripid']['default'] = ' NEW';
// 	}
//     return $fields;
// }

// add_filter( 'woocommerce_checkout_fields' , 'override_checkout_email_field' );

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

add_action('wp_footer', 'jg_format_checkout_billing_phone');
function jg_format_checkout_billing_phone() {
    if ( is_checkout() && ! is_wc_endpoint_url() ) :
    ?>
    <script type="text/javascript">
    jQuery( function($){
        $('#youth_phone').focusout(function() {
            var p = $(this).val();
			p = p.replace(/[^0-9]/g,"");
			var p_length = p.length;
			if(p_length == 10){
				p = p.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
			}else if(p_length == 11){
				p = p.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, "$2-$3-$4");
			}else{
				p = p;
			}
            $(this).val(p);
        });
    });
    </script>
    <?php
    endif;
}


add_filter( 'wc_stripe_elements_options', 'wc_update_locale_in_stripe_element_options' );
function wc_update_locale_in_stripe_element_options( $options ) {
    return array_merge(
        $options,
        array(
            'locale' => 'us',
        )
    );
};



add_action('nectar_hook_after_footer_widget_area', 'wpshout_action_example'); 
function wpshout_action_example() { 
    echo '<div class="disclaimer">';
	echo get_field('footer_disclaimer', 'options'); 
	echo '</div>';
}

// add_action( 'template_redirect', 'misha_redirect_depending_on_product_id' );

// function misha_redirect_depending_on_product_id(){

// 	/* do nothing if we are not on the appropriate page */
// 	if( !is_wc_endpoint_url( 'order-received' ) || empty( $_GET['key'] ) ) {
// 		return;
// 	}
	
// 	$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
// 	$order = wc_get_order( $order_id );

// 	foreach( $order->get_items() as $item ) {
// 		if( $item['product_id'] == 1231 ) {
// 			wp_redirect( '/waitlist-thank-you/' );
// 			exit;
// 		} else {
// 			wp_redirect( '/thank-you/' );
// 			exit;
// 		}
// 	}
	
// }




