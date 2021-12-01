<?php

namespace RoMarketingPro;

class Scripts{

	public $script;
	public $scripts;
	public $script_meta;
	public $script_available;
	function __construct(){
		add_action( 'wp', array( $this, 'get_eligible_scripts' ), 10 ); //Check if there are any scripts to display
		add_action( 'wp', array( $this, 'maybe_set_up_scripts' ), 50 ); //If there are scripts, run the set up code
	}

	/**
	 * Get all of the scripts and filter out any that are not eligible for this page.
	 */
	public function get_eligible_scripts(){
		$this->get_available_scripts();
		$this->filter_available_scripts();
	}

	/**
	 * Get all of the scripts that are active right now.
	 */
	protected function get_available_scripts(){
		$args = array(
			'post_type' 		=> 'script',
			'posts_per_page' 	=> -1,
			'meta_query' 		=> array(
				'relation' 		=> 'AND',
				array(
					'key'		=> 'script_start_date',
					'value' 	=> date( 'Ymd' ),
					'compare'	=> '<=',
					'type' 		=> 'DATE'
				),
				array(
					'key'		=> 'script_end_date',
					'value' 	=> date( 'Ymd' ),
					'compare'	=> '>=',
					'type' 		=> 'DATE'
				),
			)
		);
		$scripts = new \WP_Query( $args );

		if( $scripts->have_posts() ) {
			$this->scripts = $scripts->posts;
			$this->scripts_available = true;
			return;
		} else {
			$this->scripts_available = false;
			return;
		}
	}

	/**
	 * Filter out any scripts that should not be displayed on this page
	 */
	protected function filter_available_scripts(){
		if( ! $this->scripts_available ) return;

		global $post;

		// Filter out scripts based on specific pages settings
		$scripts_after_specific_pages_filter = array_filter( $this->scripts, function( $script ) use( $post ){
			if( get_field( 'script_display_setting', $script->ID ) === 'specific_pages' ){
				// Check if the current page matches any of the specified pages
				$display_pages = get_field( 'script_display_pages', $script->ID );
				if( $this->pages_match( $display_pages, 'specified_page', $post ) ) return true;

				// Check if the current page matches any of the specified URLs
				$display_pages_url_string = get_field( 'script_display_pages_url_string', $script->ID );
				if( $this->strings_match( $display_pages_url_string, 'specified_string', $_SERVER['REQUEST_URI'] ) ) return true;

				// If the current page didn't match a specified page or url, return false
				return false;
			}else{
				return true;
			}
		});

		// Filter out scripts based on excluded pages settings
		$scripts_after_excluded_pages_filter = array_filter( $scripts_after_specific_pages_filter, function( $script ) use( $post ){

			// Check if the current page matches any of the excluded pages
			$exclude_pages = get_field( 'script_exclude_pages', $script->ID );
			if( $this->pages_match( $exclude_pages, 'excluded_page', $post ) ) return false;

			// Check if the current page matches any of the excluded URLs
			$exclude_pages_url_string = get_field( 'script_exclude_pages_url_string', $script->ID );
			if( $this->strings_match( $exclude_pages_url_string, 'excluded_string', $_SERVER['REQUEST_URI'] ) ) return false;

			// If the current page didn't match an excluded page, return true
			return true;
		});

		// Reorder scripts array so the values start at 0, then return the first value if there is one
		$this->scripts = array_values( $scripts_after_excluded_pages_filter );
	}

	public function maybe_set_up_scripts(){
		if( ! $this->scripts ) return;

		$this->set_up_script_meta_data();

		add_action('wp_head', array( $this, 'ro_print_scripts' ), 1 );
        add_action('wp_footer', array( $this, 'ro_print_scripts_footer' ), 1 );
//		add_action('wp_enqueue_scripts', array( $this, 'ro_include_floating_script_custom_css' ) );
//		add_action('wp_enqueue_scripts', array( $this, 'ro_include_floating_script_default_css' ) );
	}

	public function set_up_script_meta_data(){
		$script_post_id = $this->script->ID;

		$this->script_meta = array(
			// General script Settings
			'script_padding' 				=> get_field( 'script_padding', $script_post_id ),
			'script_transparency' 			=> get_field( 'script_transparency', $script_post_id ),
			'script_end_date' 				=> get_field( 'script_end_date', $script_post_id ),
			'script_start_date' 			=> get_field( 'script_start_date', $script_post_id ),
			'show_script_border' 			=> get_field( 'show_script_border', $script_post_id ),
			'script_display_side' 			=> get_field( 'script_display_side', $script_post_id ),
			'script_border_color' 			=> get_field( 'script_border_color', $script_post_id ),
			'script_border_width' 			=> get_field( 'script_border_width', $script_post_id ),
			'script_background_color' 		=> get_field( 'script_background_color', $script_post_id ),
			'script_distance_from_top' 	=> get_field( 'script_distance_from_top', $script_post_id ),
			'script-mobile-prompt-text' 	=> get_field( 'script_mobile_prompt_text', $script_post_id ),
			'script_custom_css' 			=> strip_tags ( get_field( 'script_custom_css', $script_post_id ) ),
			'script_custom_css_mobile' 	=> strip_tags ( get_field( 'script_custom_css_mobile', $script_post_id ) ),

			// script Line 1 Settings
			'script_line_1_type' 			=> get_field( 'script_line_1_type', $script_post_id ),
			'script_line_1_shortcode' 		=> do_shortcode( get_field( 'script_line_1_shortcode', $script_post_id ) ),
			'script_line_1_text' 			=> get_field( 'script_line_1_text', $script_post_id ),
			'script_line_1_link' 			=> get_field( 'script_line_1_link', $script_post_id ),
			'script_line_1_text_color' 	=> get_field( 'script_line_1_text_color', $script_post_id ),
			'script_line_1_link_color' 	=> get_field( 'script_line_1_link_color', $script_post_id ),

            // script Line 2 Settings
            'script_line_2_type' 			=> get_field( 'script_line_2_type', $script_post_id ),
			'script_line_2_shortcode' 		=> do_shortcode( get_field( 'script_line_2_shortcode', $script_post_id ) ),
			'script_line_2_text' 			=> get_field( 'script_line_2_text', $script_post_id ),
			'script_line_2_link' 			=> get_field( 'script_line_2_link', $script_post_id ),
			'script_line_2_text_color' 	=> get_field( 'script_line_2_text_color', $script_post_id ),
			'script_line_2_link_color' 	=> get_field( 'script_line_2_link_color', $script_post_id )
        );

        $this->script_meta['script_background_color'] = $this->build_rgba_value();
	}

	/**
	 * Checks to see if the current_page matches any of the pages in the specified_pages array
	 */
	protected function pages_match( $specified_pages, $value_index, $current_page ){
		if( ! $specified_pages || ! is_array( $specified_pages ) || ! $current_page ) return false;

		$matching_pages = array_filter( $specified_pages, function( $specified_page ) use( $current_page, $value_index ){
			return $specified_page[ $value_index ]->ID === $current_page->ID;
		});

		return( ! empty( $matching_pages ) );
	}

	/**
	 * Checks to see if the current_page_url contains the specified_url_string
	 */
	protected function strings_match( $specified_urls, $value_index, $current_url ){
		if( ! $specified_urls || ! is_array( $specified_urls ) ) return false;

		$matching_strings = array_filter( $specified_urls, function( $specified_url ) use( $current_url, $value_index ){

			// If the specified URL is just an empty string, return false. It should not be allowed to match the current URL.
			if( ! $specified_url[ $value_index ] ) return false;

			// Use '/' as the second argument of preg_quote to make sure it escapes forward slashes
			return preg_match( '/' . preg_quote( $specified_url[ $value_index ], '/' ) . '/', $current_url );
		});

		return ( ! empty( $matching_strings ) );
	}

	/**
	 * Builds the arguments for a CSS RGBA value.
	 * Converts the hex value to RGB values and then adds the opacity.
	 */
	public function build_rgba_value(){
		list( $r, $g, $b ) = sscanf( $this->script_meta['script_background_color'], "#%02x%02x%02x" );

		return $r . ', ' . $g . ', ' . $b . ', ' . (float)( $this->script_meta['script_transparency'] * .01 );
	}

//	public function ro_include_floating_script_custom_css(){
//		wp_register_script( 'floating_script_css', RO_MARKETING_PRO_URL . 'assets/js/floating-script-css.js', array('jquery') );
//		wp_localize_script( 'floating_script_css', 'script_meta', $this->script_meta );
//		wp_enqueue_script( 'floating_script_css' );
//	}

	public function ro_print_scripts(){
	    if ($this->scripts_available) {
	        foreach($this->scripts as $script) {
	            $footer_or_header = get_field("header_or_footer_script", $script->ID);
	            if ($footer_or_header == 'header') {
                    $src = get_field('script', $script->ID);
                    print($src);
                }
            }
        }
	}

    public function ro_print_scripts_footer(){
        if ($this->scripts_available) {
            foreach($this->scripts as $script) {
                $footer_or_header = get_field("header_or_footer_script", $script->ID);
                if ($footer_or_header == 'footer') {
                    $src = get_field('script', $script->ID);
                    print($src);
                }
            }
        }
    }

	/**
	 * Load the styles from a template file to allow the CSS values to be dynamically set in PHP.
	 */
//	public function ro_include_floating_script_default_css(){
//		ob_start();
//		require RO_MARKETING_PRO_DIR . 'includes/floating-script/templates/floating-script-default-css.php';
//		echo ob_get_clean();
//	}
}

function ro_load_scripts(){
	if( class_exists( 'acf' ) ) {
		new Scripts();
	}
}
add_action( 'plugins_loaded', 'RoMarketingPro\ro_load_scripts' );
