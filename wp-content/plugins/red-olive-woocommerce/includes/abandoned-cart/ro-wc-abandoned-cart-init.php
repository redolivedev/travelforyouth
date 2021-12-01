<?php

namespace RoWooCommerce;

global $abandoned_cart_table_version;
$abandoned_cart_table_version = '1.9';

function ro_wc_activation_init(){
	global $wpdb;
	global $abandoned_cart_table_version;

	$table_name = $wpdb->prefix . 'ro_abandoned_cart';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		email varchar(80) DEFAULT '' NOT NULL,
		hash varchar(80) DEFAULT '' NOT NULL,
		cart_contents text NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		sent_at_initial datetime,
		sent_at_3day datetime,
		sent_at_7day datetime,
		checkout_at datetime,
        order_id varchar(20),
		PRIMARY KEY  (id)
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	update_option( 'abandoned_cart_table_version', $abandoned_cart_table_version, false );
}
register_activation_hook( __FILE__, 'RoWooCommerce\ro_wc_activation_init' );

function check_abandoned_cart_table_version(){

	global $roWcOptions;

	if( !isset( $roWcOptions['abandoned_cart_enabled'] ) || !$roWcOptions['abandoned_cart_enabled'] ){
		return;
	}

	global $abandoned_cart_table_version;

	if( get_option( 'abandoned_cart_table_version') != $abandoned_cart_table_version ){
		ro_wc_activation_init();
	}
}
add_action( 'plugins_loaded', 'RoWooCommerce\check_abandoned_cart_table_version' );

function ro_activate_abandoned_cart_email_cron(){
	global $roWcOptions;

	if( !isset( $roWcOptions['abandoned_cart_enabled'] ) || !$roWcOptions['abandoned_cart_enabled'] ){
		return;
	}

	if ( ! wp_next_scheduled( 'ro_abandoned_cart_email_cron_hook' ) ) {
		wp_schedule_event( time(), 'hourly', 'ro_abandoned_cart_email_cron_hook' );
	}

	/*  NOTE: THIS IS FOR TESTING -- Force the cron to run on every page load */
	// ro_abandoned_cart_email_cron(); //@DEBUG
	/*  Ends here */
}
add_action( 'init', 'RoWooCommerce\ro_activate_abandoned_cart_email_cron' );

function ro_abandoned_cart_email_cron(){
	global $roWcOptions;

	if( !isset( $roWcOptions['abandoned_cart_enabled'] ) || !$roWcOptions['abandoned_cart_enabled'] ){
		return;
    }

    require RO_WC_DIR . 'includes/abandoned-cart/ro-wc-abandoned-cart-process-emails.php';
    
    clear_db_duplicates();

	if( 'system' == $roWcOptions['abandoned_cart_notification_type'] ){
		send_system_email();
	}
	else if( 'mailchimp' == $roWcOptions['abandoned_cart_notification_type'] ){
		add_emails_to_mailchimp();
	}
}
add_action( 'ro_abandoned_cart_email_cron_hook', 'RoWooCommerce\ro_abandoned_cart_email_cron' );

/**
 * Add a Cart Recovered column to Orders table.
 */
function add_recovered_cart_column_to_orders_page( $columns ){
    $updated_columns = array();
    
    foreach( $columns as $key => $column ){
        $updated_columns[$key] = $column;
        if( $key == 'order_status' ){
            $updated_columns['recovered'] = 'RO Cart Recovered';
        }
    }

    return $updated_columns;
}
add_action( 'manage_edit-shop_order_columns', 'RoWooCommerce\add_recovered_cart_column_to_orders_page' );

/**
 * Set Recovered Cart column to be sortable.
 */
function add_sortable_recovered_cart_column_to_orders_page( $sortable_columns ){
    $sortable_columns['recovered'] = 'recovered';

    return $sortable_columns;
}
add_action( 'manage_edit-shop_order_sortable_columns', 'RoWooCommerce\add_sortable_recovered_cart_column_to_orders_page' );

/**
 * Modify query when sorting by Recovered Carts.
 */
function sort_recovered_cart_column( $query ){
    if( ! is_admin() ) return;
    if( ! $query->is_main_query() ) return;
    if( ! $order_by = $query->get( 'orderby' ) ) return;
    if( 'recovered' !== $order_by ) return;

    $query->set( 'meta_key', 'ro_cart_recovered' );
    $query->set('orderby','meta_value');
}
add_action( 'pre_get_posts', 'RoWooCommerce\sort_recovered_cart_column' );

/**
 * Add content to the Recovered column for each order in the list.
 */
function add_recovered_order_info( $column ){
    global $wpdb;
    global $post;
    $table = $wpdb->prefix . 'ro_abandoned_cart';

    if( $column !== 'recovered' ) return;

    echo get_post_meta( $post->ID, 'ro_cart_recovered', true );
}
add_action( 'manage_shop_order_posts_custom_column', 'RoWooCommerce\add_recovered_order_info' );

function my_custom_woocommerce_admin_reports( $reports ) {
    include_once(\WC()->plugin_path().'/includes/admin/reports/class-wc-admin-report.php');
    $recovered_carts = array(
        'recovered_carts' => array(
            'title'         => 'RO Recovered Carts',
            'description'   => 'Carts recovered through the RO WooCommerce recover abandoned cart feature.',
            'hide_title'    => true,
            'callback'      => 'RoWooCommerce\recovered_carts_callback',
        ),
    );

    $reports['orders']['reports'] = array_merge( $reports['orders']['reports'], $recovered_carts );
    return $reports;
}
add_filter( 'woocommerce_admin_reports', 'RoWooCommerce\my_custom_woocommerce_admin_reports' );

function recovered_carts_callback(){
    require_once RO_WC_DIR . 'includes/abandoned-cart/ro-wc-abandoned-cart-report.php';
    $report = new RO_WC_Report_Recovered_Carts();
    $report->output_report();
}

/**
 * Uncomment to display query for order reports
 */
// function testing($query){
//     echo '<pre>'; print_r( $query ); echo '</pre>'; die; //@DEBUG
// }
// add_filter('woocommerce_reports_get_order_report_query', 'RoWooCommerce\testing');
