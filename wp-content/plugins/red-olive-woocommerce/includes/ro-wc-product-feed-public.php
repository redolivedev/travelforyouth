<?php

namespace RoWooCommerce;

class SimpleXMLExtended extends \SimpleXMLElement {
	public function addCData($cdata_text) {
	    $node = dom_import_simplexml($this);
	    $node_owner = $node->ownerDocument;
	    $node->appendChild($node_owner->createCDATASection($cdata_text));
	  }
}

/*
 * Add product feed
 */

class ProductFeed {
	// array of products
	protected $product_array;

	// array of additional images to append to the xml later
	protected $addit_images;

	// reference to wpdb
	public $db;

	function __construct() {
		global $wpdb;
		$this->db = $wpdb;

		$this->getProductData();
		$this->saveXML();
	}

	protected function getProductData() {

		if( defined( 'RO_MEMORY_INCREASE' ) && RO_MEMORY_INCREASE ){
			ini_set( 'memory_limit', RO_MEMORY_INCREASE );
		}

		if( defined( 'RO_EXECUTION_TIME_INCREASE' ) && RO_EXECUTION_TIME_INCREASE ){
			ini_set( 'max_execution_time', RO_EXECUTION_TIME_INCREASE );
		}

		$this->product_array = array(
			'channel' => array(
				'title'		 	=> get_bloginfo(),
				'link'		  	=> get_site_url(),
				'description'   => 'This is the WooCommerce Product List RSS feed'
			)
		);

		// Get variable products and simple products
		$products = $this->db->get_results(
			'SELECT * FROM ' . $this->db->posts . '
			WHERE (
				post_type = "product_variation"
				AND post_status = "publish"
			)
			OR (
				post_type = "product"
				AND post_status = "publish"
				AND ID NOT IN (
					SELECT DISTINCT post_parent
					FROM ' . $this->db->posts . '
					WHERE post_type = "product_variation"
				)
			)'
		);

		if( ! $products ) die( 'no products' );

		// Set up global variables
		$feed_options 			        = get_option('ro_wc_options');

		$global_brand 					= isset( $feed_options['feeds_global_brand'] ) 
											? $feed_options['feeds_global_brand'] 
											: '';
		$global_category 				= isset( $feed_options['feeds_global_category'] ) 
											? $feed_options['feeds_global_category'] 
											: '';
		$global_product_type 			= isset( $feed_options['feeds_global_product_type'] ) 
											? $feed_options['feeds_global_product_type'] 
											: '';
		$global_condition				= isset( $feed_options['feeds_global_condition'] ) 
											? $feed_options['feeds_global_condition'] 
                                            : '';
        $global_age_group				        = isset( $feed_options['feeds_global_age_group'] ) 
											? $feed_options['feeds_global_age_group'] 
											: '';
		$global_gender					= isset( $feed_options['feeds_global_gender'] ) 
											? $feed_options['feeds_global_gender'] 
											: '';
		$global_size_type					= isset( $feed_options['feeds_global_size_type'] ) 
											? $feed_options['feeds_global_size_type'] 
                                            : '';
        $global_size					= isset( $feed_options['feeds_global_size'] ) 
											? $feed_options['feeds_global_size'] 
                                            : '';
        $global_color					= isset( $feed_options['feeds_global_color'] ) 
											? $feed_options['feeds_global_color'] 
											: '';
		$global_enable_custom_labels	= isset( $feed_options['feeds_enable_global_custom_labels'] ) 
											? $feed_options['feeds_enable_global_custom_labels'] 
											: '';
		$global_cust_lbl_1				= isset( $feed_options['feeds_global_custom_label_1'] ) 
											? $feed_options['feeds_global_custom_label_1'] 
											: '';
		$global_cust_lbl_2				= isset( $feed_options['feeds_global_custom_label_2'] ) 
											? $feed_options['feeds_global_custom_label_2'] 
											: '';
		$global_cust_lbl_3				= isset( $feed_options['feeds_global_custom_label_3'] ) 
											? $feed_options['feeds_global_custom_label_3'] 
											: '';
		$global_cust_lbl_4				= isset( $feed_options['feeds_global_custom_label_4'] ) 
											? $feed_options['feeds_global_custom_label_4'] 
											: '';
		$global_description				= isset( $feed_options['feeds_global_description'] ) 
											? $feed_options['feeds_global_description'] 
											: '';
		$global_min_price				= isset( $feed_options['feeds_global_min_price'] ) 
											? $feed_options['feeds_global_min_price'] 
											: '';

		$i=0;
		foreach( $products as $product ) {
			$variation = false;
			$variations = false;
			$parent_url = false;

			try{
				if( $product->post_type == 'product_variation' ){
					$the_product = new \WC_Product_Variation( $product->ID );
				}else{
					$the_product = new \WC_Product( $product->ID );
				}
			}catch( Exception $e ){
				continue;
			}

			//If this is a variable product, set up additional variables
			if( get_class( $the_product ) == 'WC_Product_Variation' ){
				$variation = true;
				$variations = $the_product->get_variation_attributes();

				//Check for new or old version of WooCommerce
				if( method_exists( $the_product, 'get_parent_id' ) ){
					$parent_url = get_permalink( $the_product->get_parent_id() );
					$parent_product = new \WC_Product( $the_product->get_parent_id() );
					$product = get_post( $parent_product->get_id() );
				}else{
					$parent_url = get_permalink( @$the_product->parent->id );
					$parent_product = new \WC_Product( @$the_product->parent->id );
					$product = @$parent_product->post;
				}
			}

			//Check if this product is false or has been marked as excluded. If so, skip it
			if( ! $product || get_post_meta( $product->ID, '_ro_google_product_exclude', true ) ){
				continue;
			}

			//Check for new or old version of WooCommerce
			if( method_exists( $the_product, 'get_gallery_image_ids' ) ){
				$gallery = $the_product->get_gallery_image_ids();
			}else{
				$gallery = $the_product->get_gallery_attachment_ids();
			}


			//If this product has additonal images in its gallery, add them to the addit_images array
			if( count($gallery) > 0 ){
				$curr_images = array();
				foreach( $gallery as $g ){
					$img_arr = wp_get_attachment_image_src( $g, 'large' );
					$curr_images[] = $img_arr[0];
				}
				$this->addit_images[$the_product->get_sku()] = $curr_images;
			}

			//Determine which product title to use
			if( get_post_meta( $product->ID, '_ro_google_product_title', true ) ){
				$title = get_post_meta( $product->ID, '_ro_google_product_title', true );
			}else {
				$title = $product->post_title;
			}

			//Set up the product image
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'large' );

			//Make sure title is no longer than 150 characters
			if( strlen($title) > 149 ){
				$title = substr( $title, 0, 149 );
			}

			//Determine which product description to use
			if( get_post_meta( $product->ID, '_ro_google_product_description', true)  ){
				$description = get_post_meta( $product->ID, '_ro_google_product_description', true );
			}
			else if( $product->post_content ){
				$description = $product->post_content;
			}
			else if( $product->post_excerpt ){
				$description = $product->post_excerpt;
			}
			else{
				$description = $global_description;
			}

			//Set up variation variables if this product is a variation
			if( $variation ){
				$title .= ' -';
				$variation_url = $parent_url . '?';
				foreach( $variations as $index => $var ){
					$title .= ' ' . ucfirst( $var );
					$variation_url .= $index . '=' . $var . '&amp;';
				}
				$variation_url = preg_replace( '/&amp;$/', '', $variation_url );

				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $the_product->get_id() ), 'large' );

				if( ! $image ){
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'large' );
				}
			}

            $url 			= $variation && $variation_url != '?' 
                            ? $variation_url 
                            : get_permalink( $product->ID );
            $brand 			= get_post_meta( $product->ID, '_ro_google_product_brand', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_brand', true ) 
                            : $global_brand;
            $category 	 	= get_post_meta( $product->ID, '_ro_google_product_cat', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_cat', true ) 
                            : $global_category;
            $product_type 	= get_post_meta( $product->ID, '_ro_google_product_type', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_type', true ) 
                            : $global_product_type;
            $condition		= get_post_meta( $product->ID, '_ro_google_product_condition', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_condition', true ) 
                            : $global_condition;
            $age_group		= get_post_meta( $product->ID, '_ro_google_product_age_group', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_age_group', true ) 
                            : $global_age_group;
            $gender			= get_post_meta( $product->ID, '_ro_google_product_gender', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_gender', true ) 
                            : $global_gender;
            $size_type		= get_post_meta( $product->ID, '_ro_google_product_size_type', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_size_type', true ) 
                            : $global_size_type;
            $size		    = get_post_meta( $product->ID, '_ro_google_product_size', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_size', true ) 
                            : $global_size;
            $color			= get_post_meta( $product->ID, '_ro_google_product_color', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_color', true ) 
                            : $global_color;                            
            $gtin			= get_post_meta( $product->ID, '_ro_google_product_gtin', true ) 
                            ? get_post_meta( $product->ID, '_ro_google_product_gtin', true ) 
                            : '';
            $price 			= $the_product->get_price() 
                            ? $the_product->get_price() 
                            : '0.00';

			if( $global_min_price ){
				//Filter out any non-monetary characters
				$min_price = filter_var( $global_min_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND );

				$min_price = number_format( $min_price, 2);
				$prod_price = number_format( $price, 2);

				//If this product is below the min price, continue to the next product
				if( $min_price > $prod_price ){
					continue;
				}
			}

			/** Determine which custom label to use based on whether custom labels are configured **/

			if( $global_enable_custom_labels || $i < 1000 ){
				$cust_lbl_0 	= '$'. number_format( $price, 2) .' | '. $title;
			}elseif( $i > 999 && $i < 2000 ){
				$cust_lbl_0 	= '';
				$cust_lbl_1		= '$'. number_format( $price, 2) .' | '. $title;
			}elseif( $i > 1999 && $i < 3000 ){
				$cust_lbl_0 	= '';
				$cust_lbl_1 	= '';
				$cust_lbl_2		= '$'. number_format( $price, 2) .' | '. $title;
			}elseif( $i > 2999 && $i < 4000 ){
				$cust_lbl_0 	= '';
				$cust_lbl_1 	= '';
				$cust_lbl_2 	= '';
				$cust_lbl_3		= '$'. number_format( $price, 2) .' | '. $title;
			}elseif( $i > 3999 ){
				$cust_lbl_0 	= '';
				$cust_lbl_1 	= '';
				$cust_lbl_2 	= '';
				$cust_lbl_3 	= '';
				$cust_lbl_4		= '$'. number_format( $price, 2) .' | '. $title;
			}

			if( $global_enable_custom_labels ){
				$cust_lbl_1		= get_post_meta( $product->ID, '_ro_google_product_cust_lbl_1', true ) ? get_post_meta( $product->ID, '_ro_google_product_cust_lbl_1', true ) : $global_cust_lbl_1;
				$cust_lbl_2		= get_post_meta( $product->ID, '_ro_google_product_cust_lbl_2', true ) ? get_post_meta( $product->ID, '_ro_google_product_cust_lbl_2', true ) : $global_cust_lbl_2;
				$cust_lbl_3		= get_post_meta( $product->ID, '_ro_google_product_cust_lbl_3', true ) ? get_post_meta( $product->ID, '_ro_google_product_cust_lbl_3', true ) : $global_cust_lbl_3;
				$cust_lbl_4		= get_post_meta( $product->ID, '_ro_google_product_cust_lbl_4', true ) ? get_post_meta( $product->ID, '_ro_google_product_cust_lbl_4', true ) : $global_cust_lbl_4;
			}
			
			$availability	= $the_product->is_in_stock() ? 'in stock' : 'out of stock';
			$sku 			= $the_product->get_sku() ? htmlspecialchars( $the_product->get_sku() ) : $the_product->get_id();

			/**
			 * Make sure variables are not too long
			 */
			$custom_lables = array( &$cust_lbl_0, &$cust_lbl_1, &$cust_lbl_2, &$cust_lbl_3, &$cust_lbl_4 );
			$variables = array(
				'id' 					=> &$sku,
				'title' 				=> &$title,
				'description' 			=> &$description,
				'product_type' 			=> &$product_type,
				'link' 					=> &$url,
				'image_link' 			=> &$image[0],
				'mpn' 					=> &$sku,
				'brand' 				=> &$brand
			);

			$length_array = array(
				'id' 					=> 50,
				'title' 				=> 150,
				'description' 			=> 5000,
				'product_type' 			=> 750,
				'link' 					=> 2000,
				'image_link' 			=> 2000,
				'mpn' 					=> 70,
				'brand' 				=> 70
			);

			foreach( $variables as $index => $variable ){
				if( strlen( $variable ) > $length_array[$index] - 1 ){
					$variables[$index] = substr( $variable, 0, $length_array[$index] - 1 );
				}
			}

			foreach( $custom_lables as $index => $cust_lbl ){
				if( strlen( $cust_lbl ) > 100 ){
					$custom_lables[$index] = substr( $cust_lbl, 0, 99 );
				}
			}

			/**
			 * Set up product feed array
			 */
			$this->product_array['channel'][] = array(
				'title'                     => '<![CDATA[ ' . htmlspecialchars( $title ) . ' ]]>',
				'link'                      => $url,
				'g:id'                      => $sku,
				'description'               => '<![CDATA[ ' . htmlspecialchars( strip_tags( $description ) ). ' ]]>',
				'g:image_link'              => $image[0],
				'g:price'                   => number_format( $price, 2) . ' ' . get_woocommerce_currency(),
				'g:availability'            => '<![CDATA[ '. $availability .' ]]>',
				'g:condition'               => '<![CDATA[ '. $condition .' ]]>',
				'g:age_group'               => '<![CDATA[ '. $age_group .' ]]>',
				'g:brand'                   => '<![CDATA[ '. $brand .' ]]>',
				'g:product_type'            => '<![CDATA[ '. $product_type .' ]]>',
				'g:google_product_category' => '<![CDATA[ '. $category .' ]]>',
				'g:gender'					=> '<![CDATA[ '. $gender .' ]]>',
				'g:size_type'               => '<![CDATA[ '. $size_type .' ]]>',
				'g:size'                    => '<![CDATA[ '. $size .' ]]>',
				'g:color'                   => '<![CDATA[ '. $color .' ]]>',
                'g:mpn'                     => '<![CDATA[ '. $sku .' ]]>',
                'g:gtin'                    => '<![CDATA[ '. $gtin .' ]]>',
				'g:custom_label_0'          => '<![CDATA[ '. htmlspecialchars( $cust_lbl_0 ) .' ]]>',
				'g:custom_label_1'			=> '<![CDATA[ '. htmlspecialchars( $cust_lbl_1 ) .' ]]>',
				'g:custom_label_2'			=> '<![CDATA[ '. htmlspecialchars( $cust_lbl_2 ) .' ]]>',
				'g:custom_label_3'			=> '<![CDATA[ '. htmlspecialchars( $cust_lbl_3 ) .' ]]>',
				'g:custom_label_4'			=> '<![CDATA[ '. htmlspecialchars( $cust_lbl_4 ) .' ]]>'
			);
			$i++;
		}
	}

	protected function sendHeaders(){
		header("Content-type: text/xml");
	}

	protected function buildXML( $data, &$xml_data ) {

		foreach( $data as $key => $value ) {
			if( is_array($value) ) {
				if( is_numeric($key) ){
					$key = 'item';
					// $key = 'item'.$key; //dealing with <0/>..<n/> issues
				}
				$subnode = $xml_data->addChild($key);
				$this->buildXML($value, $subnode);
			} else {
				if( strpos( $key, 'g:' ) !== false ){
					if( strpos($value, 'CDATA') !== false ){
						$value = str_replace( '<![CDATA[ ', '', $value );
						$value = str_replace( ' ]]>', '', $value );

						$xml_child = $xml_data->addChild( $key, null, "http://base.google.com/ns/1.0" );
						$xml_child->addCData( $value );
					}
					else {
						$xml_data->addChild( $key, $value, "http://base.google.com/ns/1.0" );
					}
				}
				else {
					if( strpos( $value, 'CDATA' ) !== false ){
						$value = str_replace( '<![CDATA[ ', '', $value );
						$value = str_replace( ' ]]>', '', $value );

						$xml_data->addChild( $key )->addCData( $value );
					}
					else {
						$xml_data->addChild( $key, esc_attr( $value ) );
				 	}
				}

				//Check if this product has additonal images and append them to the xml
				if( $key == 'g:id' && $this->addit_images ){
					if( $value && array_key_exists( $value, $this->addit_images ) ){
						foreach( $this->addit_images[$value] as $ai ){
							$xml_child = $xml_data->addChild( 'g:additional_image_link', null, "http://base.google.com/ns/1.0" );
							$xml_child->addCData( $ai );
						}
					}
				}
			}
		}
	}

	protected function saveXML() {
		// creating object of SimpleXMLElement
		$xml_data = new SimpleXMLExtended('<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0" version="2.0"></rss>');

		// function call to convert array to xml
		$this->buildXML( $this->product_array, $xml_data );

		//saving generated xml file;
		// $result = $xml_data->asXML('google-feed.xml');

		// printing generateed xml file
		$this->sendHeaders();
		echo $xml_data->asXML();
		die;
	}
}

function ro_run_public_product_feed(){
	$product_feed = new ProductFeed;
}
add_action( 'init', 'RoWooCommerce\ro_run_public_product_feed' );
