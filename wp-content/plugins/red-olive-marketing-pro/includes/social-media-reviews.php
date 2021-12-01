<?php

namespace RoMarketingPro;

//Include the jQuery
function ro_include_social_media_script(){
	wp_enqueue_script('front_end_social_media_reviews_script', 
				plugin_dir_url(dirname(__FILE__)) . 'assets/js/frontEndSocialMediaReviews.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'RoMarketingPro\ro_include_social_media_script');

//Include the CSS
function ro_include_social_media_styles(){
	wp_enqueue_style('social_media_styles', plugin_dir_url(dirname(__FILE__)) . 'assets/css/social-media-styles.css');
}
add_action('wp_enqueue_scripts', 'RoMarketingPro\ro_include_social_media_styles');

function ro_add_default_icons(){
	global $wpdb; 

	//Check if the facebook Social Media Icon is already in the media library. 
	$icon_check = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title='ro-fb'" );

	if( $icon_check ){
		return;
	}	

	//If it's not in there, add the icons to the media library.
	$icons = array( 'ro-angie', 'ro-fb', 'ro-gplus', 'ro-yelp' );

	foreach( $icons as $icon ){
		ro_add_image_to_library( dirname( plugin_dir_path(__FILE__) ) . "/assets/img/$icon.jpg" );
	}	

}
add_action( 'init', 'RoMarketingPro\ro_add_default_icons' );

/**
 * A class that adds a new page template to WordPress
 */
class RoPluginPageTemplate{

	private static $instance;

	protected $templates;

	public static function get_instance(){
		if(null == self::$instance){
			self::$instance = new RoPluginPageTemplate();
		}

		return self::$instance;
	}

	private function __construct(){
		$this->templates = array( RO_MARKETING_PRO_DIR . 'includes/templates/ro-blank.php' => 'RO Blank' );

		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
			// 4.6 and older
			add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'register_project_templates' ));

		} else {
			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);
		}

		//Add template as an option in save post data
		add_filter( 'wp_insert_post_data', array( $this, 'register_project_templates' ));

		//Allow WordPress to find template when it is selected
		add_filter( 'template_include', array( $this, 'view_project_template' ));
	}

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	public function register_project_templates( $atts ){

		//Create unique key for the themes cache
		$cache_key = 'page_templates-' . md5(get_stylesheet_directory() );

		//Retrieve the cache list if it exists
		$templates = wp_get_theme()->get_page_templates();
		if( empty( $templates )){
			$templates = array();
		}

		//Remove the old cache
		wp_cache_delete( $cache_key, 'themes' );

		//Add template to list of templates
		$templates = array_merge( $templates, $this->templates );

		//Add new cache so wordpress can use it for listing available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1880 );

		return $atts;
	}

	public function view_project_template( $template ){

		global $post;

		//Check to make sure that this is a page
		if(!$post || !isset($this->templates[get_post_meta( $post->ID, '_wp_page_template', true )])){
			return $template;
		}

		//Specify the location of the page template file
		$file = get_post_meta( $post->ID, '_wp_page_template', true );

		//Make sure file exists
		if( file_exists( $file )){
			return $file;
		}

		return $template;
	}

}
add_action( 'plugins_loaded', array( 'RoMarketingPro\RoPluginPageTemplate', 'get_instance' ));

function ro_social_media_reviews_output( $atts ) {

	$version_info = get_field( 'social_media_review_set', 'options' );

	if( $version_info ){
		foreach( $version_info as $vi ){
			if( $vi['set_name'] == $atts['name'] ){
				$smr_set = $vi;
				break;		
			}				
		}
	}

	if( !isset( $smr_set ) || !$smr_set || !$smr_set['social_media_site'] ){
		return;
	}

	if( isset( $smr_set['text_color'] ) && $smr_set['text_color'] ){
		$text_color = 'style="color:' . $smr_set['text_color'] . ';"';
	}
	else{
		$text_color = '';
	}

	if( isset( $smr_set['text_and_icon_padding'] ) && $smr_set['text_and_icon_padding'] ){
		$padding = $smr_set['text_and_icon_padding'];
	}
	else{
		$padding = '0';
	}

	//Containing element
	$output = '
		<div id="ro-social-container">
			<div id="text-container" ' . $text_color . '>
				<h1>
					REVIEW US
				</h1>
				<p class="review-text">
					PLEASE TAKE A FEW MOMENTS TO REVIEW YOUR EXPERIENCE
				</p>
			</div>
			<div id="social-media-container" style="padding-top:' . $padding . 'px;">';

	//Iterate through each social media element and create its link
	foreach($smr_set['social_media_site'] as $index => $sms){

		//Distinguish default icon locations from new icon locations
		$imgsrc = '';
		if(0 === strpos($sms['icon_url'], 'assets')){
			$imgsrc = plugins_url( '../' . $sms['icon_url'], __FILE__ );
		}
		else {
			$imgsrc = $sms['icon_url'];
		}

		$output .= '
			<div class="social-review-col" id="' . clean_value( $sms['name'] ) . '" >
				<div id="icon" style="text-align:center;"> 
					<a href="' . $sms['url'] . '" target="_blank">
						<img style="margin:0px;" src="' . $imgsrc . '">
					</a>
					<p>
						<a ' . $text_color . ' class="review-link" href="' . $sms['url'] . '" target="_blank">' .
						$sms['name']
					. '</a>
					</p>
				</div>
			</div>					
		';

		if( ( $index + 1 ) % 4 == 0 ){
			$output .= '
				<div style="clear:both;"></div>
			';
		}
	}

	if( isset( $smr_set['background_color'] ) && $smr_set['background_color'] ){
		$bg_color = $smr_set['background_color'];
	}
	else{
		$bg_color = 'false';
	}

	$output .= '
			</div>
			<div style="clear:both;"></div>
		</div>
		<input type="hidden" id="bg-color" value="' . $bg_color . '">
	';

	return $output;

}
add_shortcode('review_collection_page', 'RoMarketingPro\ro_social_media_reviews_output');

function clean_value( $value ){
	return strtolower( preg_replace( "/[^A-Za-z0-9]/", '', $value ) );
}

/**
 * Create the image attachment and return the new media upload id.
 *
 * @since 1.0.0
 * @see http://codex.wordpress.org/Function_Reference/wp_insert_attachment#Example
 */
function ro_add_image_to_library( $file ) {

	$filename = basename( $file );
	$upload_file = wp_upload_bits( $filename, null, file_get_contents( $file ) );
	if ( !$upload_file['error'] ) {
		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
			'post_content' => '',
			'post_status' => 'inherit'
		);
		$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
		if ( !is_wp_error( $attachment_id ) ) {
			require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
			wp_update_attachment_metadata( $attachment_id,  $attachment_data );
		}
	}	
} 