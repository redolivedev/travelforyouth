<?php

namespace RoWooCommerce;

/*
 * Run the function to get lifetime value when it needs to
 */
class RoCustomerLifetimeValuePublic {

	/*
	 * Reference to $wpdb
	 */
	public $db;

	/*
	 * Keeps customer data
	 */
	public $customers;

	/*
	 * How many customers have more than one order
	 */
	public $moreThanOneOrder;

	function __construct() {

		global $wpdb;
		$this->db = &$wpdb;
		$this->moreThanOneOrder = array();

		$this->check_scheduled_cron();
		add_action( 'ro_customer_lifetime_value', array( $this, 'ro_check_customer_lifetime_value' ) );
	}

	// set the cron
	function check_scheduled_cron() {
		if ( ! wp_next_scheduled( 'ro_customer_lifetime_value' ) ) {
			wp_schedule_event( time(), 'daily', 'ro_customer_lifetime_value' );
		}
	}

	function ro_check_customer_lifetime_value() {
		$today										= date('Y-m-d');

		$lifetimeValues 							= get_option('ro_customer_lifetime_value') ? get_option('ro_customer_lifetime_value') : array();
		$lifetimeValues[$today] 					= array();
		$lifetimeValues[$today]['lifetime_value'] 	= $this->get_average_customer_value();
		$lifetimeValues[$today]['more_than_one']	= $this->get_customers_with_more_than_one_order();

		update_option( 'ro_customer_lifetime_value', $lifetimeValues );
	}

	// update the option

	function get_average_customer_value() {
		$orders = $this->db->get_results( 'SELECT DISTINCT post_id,meta_value FROM ' . $this->db->postmeta . ' WHERE meta_key="_billing_email"' );
		foreach ($orders as $orderId) {
			if( isset( $this->customers[$orderId->meta_value] ) ) {
				$this->customers[$orderId->meta_value] += $this->db->get_var( 'SELECT meta_value FROM ' . $this->db->postmeta . ' WHERE post_id=' . $orderId->post_id . ' AND meta_key="_order_total"' );
				$this->moreThanOneOrder[$orderId->meta_value] = true;
			} else {
				$this->customers[$orderId->meta_value] = $this->db->get_var( 'SELECT meta_value FROM ' . $this->db->postmeta . ' WHERE post_id=' . $orderId->post_id . ' AND meta_key="_order_total"' );
			}
		}

		return number_format( array_sum( $this->customers ) / count( $this->customers ), 2 );
	}

	function get_customers_with_more_than_one_order() {
		return count( $this->moreThanOneOrder );
	}

}

$roCustomerLifetimeValuePublic = new RoCustomerLifetimeValuePublic();