<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_enqueue_style( 'nectar-element-pricing-table' );

extract(shortcode_atts(array(
	"el_class" => '', 
	'style' => 'default'), $atts));
	
	$columns      = explode("[pricing_column", $content);
	$columnsNum   = count($columns);
	$columnsNum   = $columnsNum - 1;
	$column_class = '';
	
	switch ($columnsNum) {
		case '2' :
			$column_class = 'two-cols';
			break;
		case '3' :
			$column_class = 'three-cols';
			break;
		case '4' :
			$column_class = 'four-cols';
			break;	
		case '5' :
			$column_class = 'five-cols';
			break;
	}
	
  echo '<div class="row pricing-table '. $column_class . ' ' . esc_attr($el_class) .'" data-style="'.esc_attr($style).'">' . do_shortcode($content) . '</div>';


?>