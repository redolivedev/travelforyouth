<?php

namespace RoWooCommerce;

class UrlProducts {

	protected $product_url_key = 'sku';	
	protected $product_quantity_key = 'quant';
	protected $remove_product_key = 'removed_item';

	/*
	 * Reference to global woocommerce object and product factory
	 */
	protected $wc;
	protected $pf;

	public function __construct() {
		$this->wc = WC();
		$this->pf = new \WC_Product_Factory();
		$this->check_url();
	}

	protected function check_url() {
		if( isset( $_GET[$this->remove_product_key] ) && $_GET[$this->remove_product_key] ){
			return;
		}

		if( isset( $_GET[$this->product_url_key] ) && $_GET[$this->product_url_key] ){
			$skus = explode( ',', $_GET[$this->product_url_key] );
			$quants = explode( ',', $_GET[$this->product_quantity_key] );

			if( count( $skus ) != count( $quants ) ){
				return;
			}

			$skus_to_add = array();
			foreach( $skus as $index => $sku ){
				$sku_obj = new \stdClass;
				$sku_obj->sku = $sku;
				$sku_obj->qty = $quants[$index];

				$skus_to_add[] = $sku_obj;
			}

			$this->check_skus( $skus_to_add );
		}
	}

	protected function check_skus( $skus ){
		$skus_in_cart = $this->get_skus_in_cart();
		foreach( $skus as $sku ){
			if( in_array( $sku->sku, $skus_in_cart ) ){
				continue;
			}

			$this->add_sku_to_cart( $sku );
		}

		$this->add_missing_attribute_info();
	}

	protected function get_skus_in_cart(){
		$skus_in_cart = array();
		foreach( $this->wc->cart->get_cart() as $item ){
			if( isset( $item['variation_id'] ) && $item['variation_id'] ){
				$product = $this->pf->get_product( $item['variation_id'] );	
			}else{
				$product = $this->pf->get_product( $item['product_id'] );
			}

			if( $product->get_sku() ){
				$skus_in_cart[] = $product->get_sku();
			}
		}

		return $skus_in_cart;
	}

	protected function add_sku_to_cart( $sku ){
		$product_id = wc_get_product_id_by_sku( $sku->sku );
		$product = $this->pf->get_product( $product_id );

		if( get_class( $product ) == 'WC_Product_Variation' ){
			$parent_id = $product->get_parent_id();
			$this->wc->cart->add_to_cart( $parent_id, $sku->qty, $product_id );
		}else{
			$this->wc->cart->add_to_cart( $product_id, $sku->qty );
		}
	}

	/**
	 * This function adds the product variation attributes if they are missing
	 */
	protected function add_missing_attribute_info(){
		foreach( $this->wc->cart->cart_contents as &$cart_item ){
			if( ! isset( $cart_item['variation_id'] ) || ! $cart_item['variation_id'] ){
				continue;
			}

			if( ! empty( $cart_item['variation'] ) ){
				continue;
			}
			
			$product = $this->pf->get_product( $cart_item['variation_id'] );

			if( $product->is_type( 'variation' ) ){
				$cart_item['variation'] = $product->get_variation_attributes();
			}
		}
		$this->wc->cart->set_session();
	}
}
function ro_url_products(){
	$url_products = new UrlProducts;
}
add_action( 'wp_loaded', 'RoWooCommerce\ro_url_products' );