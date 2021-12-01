<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-multistep-checkout
 * @subpackage woocommerce-multistep-checkout/admin
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWMSC_Admin')):
 
class THWMSC_Admin {
	private $plugin_name;
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	
	public function enqueue_styles_and_scripts($hook) {		
		if(strpos($hook, 'page_th_multi_step_checkout') === false) {
			return;
		}

		$debug_mode = apply_filters('thwmsc_debug_mode', false);
		$suffix = $debug_mode ? '' : '.min';
			
		$this->enqueue_styles($suffix); 
		$this->enqueue_scripts($suffix);
	}
	
	private function enqueue_styles($suffix) {
		wp_enqueue_style('jquery-ui-style', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css?ver=1.11.4');
		wp_enqueue_style('woocommerce_admin_styles', THWMSC_WOO_ASSETS_URL.'css/admin.css');
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('thwmsc-admin-style', THWMSC_ASSETS_URL_ADMIN . 'css/thwmsc-admin'. $suffix .'.css', $this->version);
	}

	private function enqueue_scripts($suffix) {
		$steps_for_review = THWMSC_Admin_Settings_Advanced::get_available_all_steps_array();
		$steps_for_review = json_encode($steps_for_review);
		$deps = array('jquery', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery-tiptip', 'woocommerce_admin', 'wc-enhanced-select', 'select2', 'wp-color-picker',);
		wp_enqueue_media();
		
		wp_enqueue_script( 'thwmsc-admin-script', THWMSC_ASSETS_URL_ADMIN . 'js/thwmsc-admin'. $suffix .'.js', $deps, $this->version, false );
		
		$script_var = array(
            'admin_url' => admin_url(),
            'admin_path'=> plugins_url( '/', __FILE__ ),
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'steps_for_review' => $steps_for_review,
        );
		wp_localize_script('thwmsc-admin-script', 'thwmsc_var', $script_var); 
	}
	
	public function admin_menu() {
		$this->screen_id = add_submenu_page('woocommerce', __('WooCommerce Multistep Checkout', 'woocommerce-multistep-checkout'), 
		__('Multistep Checkout', 'woocommerce-multistep-checkout'), 'manage_woocommerce', 'th_multi_step_checkout', array($this, 'output_settings'));
	}
	
	public function add_screen_id($ids){
		$ids[] = 'woocommerce_page_th_multi_step_checkout';
		$ids[] = strtolower( __('WooCommerce', 'woocommerce-multistep-checkout') ) .'_page_th_multi_step_checkout';

		return $ids;
	}
	
	public function plugin_action_links($links) {
		$settings_link = '<a href="'.admin_url('admin.php?page=th_multi_step_checkout').'">'. __('Settings', 'woocommerce-multistep-checkout') .'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	
	public function plugin_row_meta( $links, $file ) {
		if(THWMSC_BASE_NAME == $file) {
			$doc_link = esc_url('https://www.themehigh.com/help-guides/woocommerce-multistep-checkout/');
			$support_link = esc_url('https://www.themehigh.com/help-guides/');
				
			$row_meta = array(
				'docs' => '<a href="'.$doc_link.'" target="_blank" aria-label="'.esc_attr__('View plugin documentation', 'woocommerce-multistep-checkout').'">'.esc_html__('Docs', 'woocommerce-multistep-checkout').'</a>',
				'support' => '<a href="'.$support_link.'" target="_blank" aria-label="'. esc_attr__('Visit premium customer support', 'woocommerce-multistep-checkout') .'">'. esc_html__('Premium support', 'woocommerce-multistep-checkout') .'</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	
	public function output_settings(){
		$tab  = isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'general_settings';
		
		if($tab === 'display_settings'){			
			$display_settings = THWMSC_Admin_Settings_Display::instance();	
			$display_settings->render_page();			
		}else if($tab === 'advanced_settings'){
			$advanced_settings = THWMSC_Admin_Settings_Advanced::instance();	
			$advanced_settings->render_page();
		}else if($tab === 'license_settings'){			
			$license_settings = THWMSC_Admin_Settings_License::instance();	
			$license_settings->render_page();	
		}else{
			$general_settings = THWMSC_Admin_Settings_General::instance();	
			$general_settings->render_page();
		}
	}
}

endif;