// For all product variations (on a variable product)
add_action( 'woocommerce_after_add_to_cart_button', 'after_add_to_cart_button_action_callback', 0 );
function after_add_to_cart_button_action_callback() {
    global $product;

    if( $product->is_type('variable') ) :

    $data = [];

    // Loop through variation Ids
    foreach( $product->get_visible_children() as $variation_id ){
		$variation = wc_get_product( $variation_id );
        $data[$variation_id] = $variation->is_in_stock();
    }
    $outofstock_text = __("Join the Waitlist", "woocommerce");
	
    ?>
    <script type="text/javascript">
    jQuery(function($){
        var b = 'button.single_add_to_cart_button',
            t = $(b).text();

        $('form.variations_form').on('show_variation hide_variation found_variation', function(){
            $.each(<?php echo json_encode($data); ?>, function(j, r){
                var i = $('input[name="variation_id"]').val();
                if(j == i && i != 0 && !r ) {
					$(b).html('<?php echo $outofstock_text; ?>').after('<a href="/checkout?add-to-cart=1231" class="space">Join the Waitlist</a>');
                    // $(b).html('<?php echo $outofstock_text; ?>').after('<a href="/checkout?add-to-cart=1231&billing_trip_ID='+j+'" class="space">Join the Waitlist</a>');
                    return false;
                } else {
                    $(b).html(t);
                }
            });
        });
    });
    </script>
    <?php
    endif;
}