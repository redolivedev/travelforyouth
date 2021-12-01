<?php

namespace RoWooCommerce;

class RoAutocompleteAddresses{
	protected $api_key;

	function __construct(){
		$this->init();
	}

	public function init(){
		global $roWcOptions;
		
		if( isset( $roWcOptions['google_api_key'] ) && $roWcOptions['google_api_key'] ){
			$this->api_key = $roWcOptions['google_api_key'];
		}else{
			$this->api_key = 'AIzaSyAgN2SYjPPDqMD0mzeNJuGlgxHmA8Is518';
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'ro_add_autocomplete_scripts' ) );
	}

	public function ro_add_autocomplete_scripts(){
		if( ! is_checkout() ){
			return;
		}

		//Enqueue header script
		wp_enqueue_script( 'address-autocomplete-script', RO_WC_URL . 'assets/js/autocomplete.js', array('jquery') );

		//Enqueue footer script
		wp_enqueue_script( 
			'google-autocomplete', 
			'https://maps.googleapis.com/maps/api/js?v=3&libraries=places&key=' . $this->api_key, 
			true 
		);
	}
}

new RoAutocompleteAddresses();