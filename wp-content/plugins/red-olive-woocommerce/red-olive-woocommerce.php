<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * Plugin Name: RO WooCommerce
 * Plugin URI: https://www.redolive.io/ro-woocommerce/
 * Description: A plugin to help with woocommerce functions
 * Version: 1.13.5
 * Author: Red Olive
 * License: Proprietary
 */

define( 'RO_WOOCOMMERCE_VERSION', '1.13.5' ); /** NOTE: Make sure to update this version number too **/
define( 'RO_WOOCOMMERCE_FILE', __FILE__ );
define( 'RO_WC_URL', plugin_dir_url( __FILE__ ) );
define( 'RO_WC_DIR', plugin_dir_path( __FILE__ ) );
define( 'RO_WC_BASENAME', plugin_basename( __FILE__ ) );
if( ! defined( 'RO_PLUGIN_SITE_URL' ) ) define( 'RO_PLUGIN_SITE_URL', 'https://www.redolive.io/' );
define( 
	'RO_WC_WOOCOMMERCE_ACTIVE', 
	in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) 
);

global $roWcOptions;
$roWcOptions = get_option( 'ro_wc_options' );

require_once RO_WC_DIR . 'init.php';