<?php

namespace RoWooCommerce;

class ProductImport {

	protected $product_array;

	public function __construct($product_json){

		//Clean out any weird //r characters from the JSON data
		$product_json = str_replace( "\\\\r", "", $product_json );

		//Clean out any stray backslashes from the JSON data
		$clean_json = stripcslashes($product_json);

		//Now finally decode the JSON
		$this->product_array = json_decode($clean_json);
	}

	public function getImportResult(){

		//Shift the header index off of the array
		array_shift( $this->product_array );
		
		//Update the database with the values from the CSV
		foreach( $this->product_array as $product ){
			update_post_meta( $product->Post_ID, '_ro_google_product_gtin', $product->GTIN );
			update_post_meta( $product->Post_ID, '_ro_google_product_title', $product->Shopping_Title );
			update_post_meta( $product->Post_ID, '_ro_google_product_description', $product->Shopping_Description );
		}

		return true;
	}	
}
