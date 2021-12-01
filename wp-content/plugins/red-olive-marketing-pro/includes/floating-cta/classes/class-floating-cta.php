<?php 

namespace RoMarketingPro;

class FloatingCTA{

	public $cta;
	public $ctas;
	public $cta_meta;
	public $ctas_available;
	function __construct(){
		add_action( 'wp', array( $this, 'get_eligible_ctas' ), 10 ); //Check if there are any CTAs to display
		add_action( 'wp', array( $this, 'maybe_set_up_cta' ), 50 ); //If there are CTAs, run the set up code
	}

	/**
	 * Get all of the CTAs and filter out any that are not eligible for this page. 
	 */
	public function get_eligible_ctas(){
		$this->get_available_ctas();
		$this->filter_available_ctas();	
	}

	/**
	 * Get all of the CTAs that are active right now. 
	 */
	protected function get_available_ctas(){
		$args = array(
			'post_type' 		=> 'floating-cta',
			'posts_per_page' 	=> -1,
			'meta_query' 		=> array(
				'relation' 		=> 'AND',
				array(
					'key'		=> 'cta_start_date',
					'value' 	=> date( 'Ymd' ),
					'compare'	=> '<=',
					'type' 		=> 'DATE'
				),
				array(
					'key'		=> 'cta_end_date',
					'value' 	=> date( 'Ymd' ),
					'compare'	=> '>=',
					'type' 		=> 'DATE'
				),
			)
		);
		$ctas = new \WP_Query( $args );

		if( $ctas->have_posts() ) {
			$this->ctas = $ctas->posts;
			$this->ctas_available = true;
			return;
		} else {
			$this->ctas_available = false;
			return;
		}
	}

	/**
	 * Filter out any CTAs that should not be displayed on this page
	 */
	protected function filter_available_ctas(){
		if( ! $this->ctas_available ) return;

		global $post;

		// Filter out CTAs based on specific pages settings
		$ctas_after_specific_pages_filter = array_filter( $this->ctas, function( $cta ) use( $post ){
			if( get_field( 'cta_display_setting', $cta->ID ) === 'specific_pages' ){
				// Check if the current page matches any of the specified pages
				$display_pages = get_field( 'cta_display_pages', $cta->ID );
				if( $this->pages_match( $display_pages, 'cta_specified_page', $post ) ) return true;

				// Check if the current page matches any of the specified URLs
				$display_pages_url_string = get_field( 'cta_display_pages_url_string', $cta->ID );
				if( $this->strings_match( $display_pages_url_string, 'cta_specified_string', $_SERVER['REQUEST_URI'] ) ) return true;

				// If the current page didn't match a specified page or url, return false
				return false;
			}else{
				return true;
			}
		});

		// Filter out CTAs based on excluded pages settings
		$ctas_after_excluded_pages_filter = array_filter( $ctas_after_specific_pages_filter, function( $cta ) use( $post ){

			// Check if the current page matches any of the excluded pages
			$exclude_pages = get_field( 'cta_exclude_pages', $cta->ID );
			if( $this->pages_match( $exclude_pages, 'cta_excluded_page', $post ) ) return false;

			// Check if the current page matches any of the excluded URLs
			$exclude_pages_url_string = get_field( 'cta_exclude_pages_url_string', $cta->ID );
			if( $this->strings_match( $exclude_pages_url_string, 'cta_excluded_string', $_SERVER['REQUEST_URI'] ) ) return false;

			// If the current page didn't match an excluded page, return true
			return true;
		});

		// Reorder ctas array so the values start at 0, then return the first value if there is one
		$ctas = array_values( $ctas_after_excluded_pages_filter );
		if( isset( $ctas[0] ) ){
			$this->cta = $ctas[0];
		}else{
			$this->cta = false;
		}
	}

	public function maybe_set_up_cta(){
		if( ! $this->cta ) return;

		$this->set_up_cta_meta_data();

		add_action('wp_footer', array( $this, 'ro_include_floating_cta_scripts' ) );
		add_action('wp_enqueue_scripts', array( $this, 'ro_include_floating_cta_custom_css' ) );
		add_action('wp_enqueue_scripts', array( $this, 'ro_include_floating_cta_default_css' ) );
	}

	public function set_up_cta_meta_data(){
		$cta_post_id = $this->cta->ID;

		$this->cta_meta = array(
			// General CTA Settings
			'cta_padding' 				=> get_field( 'cta_padding', $cta_post_id ),
			'cta_transparency' 			=> get_field( 'cta_transparency', $cta_post_id ),
			'cta_end_date' 				=> get_field( 'cta_end_date', $cta_post_id ),
			'cta_start_date' 			=> get_field( 'cta_start_date', $cta_post_id ),
			'show_cta_border' 			=> get_field( 'show_cta_border', $cta_post_id ),
			'cta_display_side' 			=> get_field( 'cta_display_side', $cta_post_id ),
			'cta_border_color' 			=> get_field( 'cta_border_color', $cta_post_id ),
			'cta_border_width' 			=> get_field( 'cta_border_width', $cta_post_id ),
			'cta_background_color' 		=> get_field( 'cta_background_color', $cta_post_id ),
			'cta_distance_from_top' 	=> get_field( 'cta_distance_from_top', $cta_post_id ),
			'cta-mobile-prompt-text' 	=> get_field( 'cta_mobile_prompt_text', $cta_post_id ),
			'cta_custom_css' 			=> strip_tags ( get_field( 'cta_custom_css', $cta_post_id ) ),
			'cta_custom_css_mobile' 	=> strip_tags ( get_field( 'cta_custom_css_mobile', $cta_post_id ) ),

			// CTA Line 1 Settings
			'cta_line_1_type' 			=> get_field( 'cta_line_1_type', $cta_post_id ),
			'cta_line_1_shortcode' 		=> do_shortcode( get_field( 'cta_line_1_shortcode', $cta_post_id ) ),
			'cta_line_1_text' 			=> get_field( 'cta_line_1_text', $cta_post_id ),
			'cta_line_1_link' 			=> get_field( 'cta_line_1_link', $cta_post_id ),
			'cta_line_1_text_color' 	=> get_field( 'cta_line_1_text_color', $cta_post_id ),
			'cta_line_1_link_color' 	=> get_field( 'cta_line_1_link_color', $cta_post_id ),
			
            // CTA Line 2 Settings
            'cta_line_2_type' 			=> get_field( 'cta_line_2_type', $cta_post_id ),
			'cta_line_2_shortcode' 		=> do_shortcode( get_field( 'cta_line_2_shortcode', $cta_post_id ) ),
			'cta_line_2_text' 			=> get_field( 'cta_line_2_text', $cta_post_id ),
			'cta_line_2_link' 			=> get_field( 'cta_line_2_link', $cta_post_id ),
			'cta_line_2_text_color' 	=> get_field( 'cta_line_2_text_color', $cta_post_id ),
			'cta_line_2_link_color' 	=> get_field( 'cta_line_2_link_color', $cta_post_id )
        );
        
        $this->cta_meta['cta_background_color'] = $this->build_rgba_value();
	}

	/**
	 * Checks to see if the current_page matches any of the pages in the specified_pages array
	 */
	protected function pages_match( $specified_pages, $value_index, $current_page ){
		if( ! $specified_pages || ! is_array( $specified_pages ) || ! $current_page ) return false;

		$matching_pages = array_filter( $specified_pages, function( $specified_page ) use( $current_page, $value_index ){
			return $specified_page[ $value_index ] === $current_page->ID;
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
		list( $r, $g, $b ) = sscanf( $this->cta_meta['cta_background_color'], "#%02x%02x%02x" );

		return $r . ', ' . $g . ', ' . $b . ', ' . (float)( $this->cta_meta['cta_transparency'] * .01 ); 
	}

	public function ro_include_floating_cta_custom_css(){
		wp_register_script( 'floating_cta_css', RO_MARKETING_PRO_URL . 'assets/js/floating-cta-css.js', array('jquery') );
		wp_localize_script( 'floating_cta_css', 'cta_meta', $this->cta_meta );
		wp_enqueue_script( 'floating_cta_css' );
	}

	public function ro_include_floating_cta_scripts(){
		wp_register_script( 'floating_cta', RO_MARKETING_PRO_URL . 'assets/js/floating-cta.js', array('jquery') );
		wp_localize_script( 'floating_cta', 'cta_meta', $this->cta_meta );
		wp_enqueue_script( 'floating_cta' );
	}

	/**
	 * Load the styles from a template file to allow the CSS values to be dynamically set in PHP. 
	 */
	public function ro_include_floating_cta_default_css(){
		ob_start();
		require RO_MARKETING_PRO_DIR . 'includes/floating-cta/templates/floating-cta-default-css.php';
		echo ob_get_clean();
	}
}

function ro_load_floating_cta(){
	if( class_exists( 'acf' ) ) {
		$floatingCTA = new FloatingCTA;
	}
}
add_action( 'plugins_loaded', 'RoMarketingPro\ro_load_floating_cta' );
