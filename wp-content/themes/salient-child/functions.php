<?php 

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles', 100);

function salient_child_enqueue_styles() {
		
		$nectar_theme_version = nectar_get_theme_version();
		wp_enqueue_style( 'salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version );
		
    if ( is_rtl() ) {
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
		}
}
function ro_scripts() {
	wp_enqueue_style(  'ro_sass',  get_stylesheet_directory_uri(). '/dist/css/app.css', array(), '1', 'all' );	
	wp_enqueue_script(  'ro_js',  get_stylesheet_directory_uri(). '/dist/js/app.js', array('jquery'), '1', true );
}
add_action('wp_enqueue_scripts', 'ro_scripts', 200);
?>

