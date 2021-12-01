<?php

namespace RoWooCommerce;

class ProductExport {

	//Instance of wpdb
	protected $db;

	//Array for the products
	protected $product_data;

	public function __construct(){
		add_action( 'wp', array( $this, 'init' ) );
	}

	public function init(){
		global $wpdb;
		$this->db = $wpdb;
		
		$this->product_data = array();

		$this->getProductData();
		$this->convertToCSV();
		die;
	}

	protected function getProductData(){
		$products = $this->db->get_results('
			SELECT ID, post_title, post_content 
			FROM ' . $this->db->posts . ' 
			WHERE post_type = "product" 
			AND post_status = "publish"
		');

		if( !$products ){
			die('No products');
		}

		//Add first row. This will be the title row
		$this->product_data[] = array(
            'Post_ID',
            'GTIN',
            'Woo_SKU',
            'Woo_Title',
            'Woo_Description',
            'Shopping_Title',
            'Shopping_Description'
        );

		foreach( $products as $key => $product ){

			//Instantiate a new WC_Product for this product
			$wc_product = new \WC_Product( $product->ID );

			//Create an array for the product information
			$product_array = array();

			$product_array['Post ID'] = $product->ID;
            $product_array['GTIN'] = get_post_meta( $product->ID, '_ro_google_product_gtin', true ) 
                ? get_post_meta( $product->ID, '_ro_google_product_gtin', true ) 
                : '';
            $product_array['SKU'] = $wc_product->get_sku();
			$temp_title = strip_tags( html_entity_decode( $product->post_title ) );
			$product_array['Title'] = str_replace( array( "\n\r", "\n", "\r" ), '', $temp_title );
			$temp_description = strip_tags( html_entity_decode( $product->post_content ) );
			$product_array['Description'] = str_replace( array( "\n\r", "\n", "\r" ), '', $temp_description );

            $product_array['Shopping Title'] = get_post_meta( $product->ID, '_ro_google_product_title', true ) 
                ? html_entity_decode( get_post_meta( $product->ID, '_ro_google_product_title', true ) )
                : '';

            $product_array['Shopping Description'] = get_post_meta( $product->ID, '_ro_google_product_description', true ) 
                ? html_entity_decode( get_post_meta( $product->ID, '_ro_google_product_description', true ) )
                : '';

			$this->product_data[] = $product_array;
		}
	}

	protected function convertToCSV(){

		//Set up the header
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'. get_bloginfo( 'name' ).'-'. time() .'.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');

		//Set it to output to the browser (since it's a csv file, the browser will download it)
		$output = fopen('php://output', 'w');

		foreach( $this->product_data as $product ){	
			fputcsv( $output, $product );
		}

		fclose( $output );
	}
}
$product_export = new ProductExport();
