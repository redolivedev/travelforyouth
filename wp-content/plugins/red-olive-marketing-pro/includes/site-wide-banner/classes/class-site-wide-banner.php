<?php 

namespace RoMarketingPro;

class SiteWideBanner{

	protected $banner_meta;
	
	function __construct(){

		$this->banner_meta = array(
			'enable_banner' => get_field('enable_banner', 'options'),
			'sticky' => get_field('sticky', 'options'),
			'banner_background_color' => get_field('banner_background_color', 'options'),
			'banner_start_date' => get_field('banner_start_date', 'options'),
			'banner_end_date' => get_field('banner_end_date', 'options'),
			'custom_css' => strip_tags ( get_field('custom_css', 'options') ),
			'custom_css_mobile' => strip_tags ( get_field('custom_css_mobile', 'options') ),
			'left_col_image_text' => get_field('left_col_image_text', 'options'),
			'left_col_image' => get_field('left_col_image', 'options'),
			'left_col_image_over_text' => get_field('left_col_image_over_text', 'options'),
			'left_col_image_over_text_color' => get_field('left_col_image_over_text_color', 'options'),
			'left_col_text' => get_field('left_col_text', 'options'),
			'left_col_text_color' => get_field('left_col_text_color', 'options'),
			'left_col_background_color' => get_field('left_col_background_color', 'options'),
			'center_col_text' => get_field('center_col_text', 'options'),
			'center_col_text_color' => get_field('center_col_text_color', 'options'),
			'center_col_secondary_text' => get_field('center_col_secondary_text', 'options'),
			'center_col_secondary_text_color' => get_field('center_col_secondary_text_color', 'options'),
			'right_col_text' => get_field('right_col_text', 'options'),
			'right_col_text_color' => get_field('right_col_text_color', 'options'),
			'right_col_button_background_color' => get_field('right_col_button_background_color', 'options'),
			'right_col_button_link' => get_field('right_col_button_link', 'options'),
			'right_col_button_border_radius' => get_field('right_col_button_radius', 'options') ? get_field('right_col_button_radius', 'options') : '25px',
		);

		if( $this->ro_banner_is_active() ){ 
			add_action('wp_enqueue_scripts', array( $this, 'ro_include_sitewide_banner_custom_css' ) );
			add_action('wp_enqueue_scripts', array( $this, 'ro_include_sitewide_banner_default_css' ) );
			add_action('wp_footer', array( $this, 'ro_include_sitewide_banner_scripts' ) );
		}

	}

	public function ro_banner_is_active(){

		// Check if banner is enabled and dates are set
		if( $this->banner_meta['enable_banner'] && $this->banner_meta['banner_start_date'] && $this->banner_meta['banner_end_date'] ):
			
			$now = strtotime( date('F j, Y') );
			$startDate = strtotime( $this->banner_meta['banner_start_date'] );
			$endDate = strtotime( $this->banner_meta['banner_end_date'] );

			// Check if today is within the start and end dates
			if( $startDate <= $now && $endDate >= $now ){
				return true;
			}else{
				return false;
			}

		else:
			return false;
		endif;

	}

	public function ro_include_sitewide_banner_default_css(){
		wp_enqueue_style( 'sitewide_banner_default_css', plugin_dir_url( dirname( dirname( dirname( __FILE__ ) ) ) ) . 'assets/css/sitewide-banner-default.css' );
	}

	public function ro_include_sitewide_banner_custom_css(){

		wp_register_script( 'sitewide_banner_css', plugin_dir_url( dirname( dirname( dirname( __FILE__ ) ) ) ) . 'assets/js/sitewide-banner-css.js', array('jquery') );
		wp_localize_script( 'sitewide_banner_css', 'banner_meta', $this->banner_meta );
		wp_enqueue_script( 'sitewide_banner_css' );

	}

	public function ro_include_sitewide_banner_scripts(){

		wp_register_script( 'sitewide_banner', plugin_dir_url( dirname( dirname( dirname( __FILE__ ) ) ) ) . 'assets/js/sitewide-banner.js', array('jquery') );
		wp_localize_script( 'sitewide_banner', 'banner_meta', $this->banner_meta );
		wp_enqueue_script( 'sitewide_banner' );

	}

}

function ro_load_site_wide_banner(){
	if( class_exists( 'acf' ) ) {
		$sitewideBanner = new SiteWideBanner;
	}
}
add_action( 'wp_loaded', 'RoMarketingPro\ro_load_site_wide_banner' );
