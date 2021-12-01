<?php

namespace RoWooCommerce;

/**
 * Run the function to add Google Customer Reviews pop-up
 */
class RoGoogleCustomerReviews{

    public function __construct(){
        add_action( 'woocommerce_thankyou', array( $this, 'google_customer_review' ) );
    }

    public function google_customer_review( $order_id ){
        $ro_wc_options = get_option( 'ro_wc_options' );
        $order = new \WC_Order( $order_id );

        // Create array of GTIN objects to be used in the script
        $items = $order->get_items();
        $gtin_array = array();
        foreach( $items as $item ){
            if( get_post_meta( $item->get_product_id(), '_ro_google_product_gtin', true ) ){
                $gtin_object = new \stdClass();
                $gtin_object->gtin = get_post_meta( $item->get_product_id(), '_ro_google_product_gtin', true );
                $gtin_array[] = $gtin_object;
            }
        }
        
        // If this pop-up hasn't been displayed yet, run it
        if( $order && ! get_post_meta( $order_id, 'ro_google_customer_review', true ) ) :
            ?>
                <script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>

                <script>
                    window.renderOptIn = function() {
                        window.gapi.load('surveyoptin', function() {
                            window.gapi.surveyoptin.render({
                                // REQUIRED FIELDS
                                "merchant_id": <?php echo $ro_wc_options['merchant_id']; ?>,
                                "order_id": "<?php echo $order_id; ?>",
                                "email": "<?php echo $order->get_billing_email(); ?>",
                                "delivery_country": "<?php echo $order->get_billing_country(); ?>",
                                "estimated_delivery_date": "<?php echo $this->get_estimated_delivery_date(); ?>",

                                // OPTIONAL FIELDS
                                <?php if( ! empty( $gtin_array ) ): ?>
                                    "products": <?php echo json_encode( $gtin_array ); ?>
                                <?php endif; ?>
                            });
                        });
                    }
                </script>
            <?php
            // update the post meta so it doesn't run again
            update_post_meta( $order_id, 'ro_google_customer_review', '1' );
        endif;
    }

    protected function get_estimated_delivery_date(){
        if( isset( $ro_wc_options['estimated_delivery_period'] ) && $ro_wc_options['estimated_delivery_period'] ){
            $days = $ro_wc_options['estimated_delivery_period'];
        }else{
            $days = 5;
        }

        return date( 'Y-m-d', strtotime( '+' . $days . ' day' ) );
    }
}

$roGoogleCustomerReviews = new RoGoogleCustomerReviews;
