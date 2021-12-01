<?php

namespace RoWooCommerce;

/*
 * Gets lifetime value and prints it to page
 */
class RoCustomerLifetimeValue {
	public $db;
	function __construct() {
		global $wpdb;
		$this->db = &$wpdb;
		add_action( 'admin_menu', array( $this, 'ro_add_woocommerce_page' ) );
	}

	function ro_add_woocommerce_page() {
		add_submenu_page( 'woocommerce', 'Lifetime Value', 'Lifetime Value', 'manage_woocommerce', 'ro-customer-lifetime-value', array( $this, 'ro_woocommerce_page' ) );
	}

	function ro_woocommerce_page() {
		$previousValues = $this->get_previous_values();
		$today 			= date('Y-m-d');
		$todaysValue 	= isset( $previousValues[$today] ) ? '$' . $previousValues[$today]['lifetime_value'] : ' not yet set';
		$todaysCount 	= isset( $previousValues[$today] ) ? $previousValues[$today]['more_than_one'] : ' not yet set';
		?>
		<style>
		.widefat tbody tr:nth-child(odd) {
			background: #f3f3f3;
		}
		</style>
		<div class="wrap">
			<h1>Customer Lifetime Value</h1>
			<h3>Current lifetime value is: <strong><?php echo $todaysValue ?></strong></h3>
			<h3>Current number of repeat customers: <strong><?php echo $todaysCount ?></strong></h3>
			<hr />
			
			<h3>Previous Values</h3>
			<table class="widefat">
				<thead>
					<tr>
						<th>Date</th>
						<th>Value</th>
						<th>Number of Repeat Customers</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Date</th>
						<th>Value</th>
						<th>Number of Repeat Customers</th>
					</tr>
				</tfoot>
				<tbody>
					<?php if( $previousValues ) : ?>
						<?php krsort( $previousValues ) ?>
						<?php foreach( $previousValues as $date => $values ) : ?>
							<?php if( ! is_array( $values ) ) : ?>
								<?php $lifetimeValue = $values ?>
								<?php $values = array() ?>
								<?php $values['lifetime_value'] = $lifetimeValue ?>
								<?php $values['more_than_one'] 	= 'Not set' ?>
							<?php endif ?>
							<tr>
								<td><?php echo date('m/d/y', strtotime( $date ) ) ?></td>
								<td>$<?php echo $values['lifetime_value'] ?></td>
								<td><?php echo $values['more_than_one'] ?></td>
							</tr>
						<?php endforeach ?>
					<?php else : ?>
						<tr>
							<td colspan="2">Nothing yet</td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	function get_previous_values() {
		if( $previousValues = get_option( 'ro_customer_lifetime_value' ) ) {
			return $previousValues;
		} else {
			return false;
		}
	}
}

// instantiate the class
$roCustomerLifetimeValue = new RoCustomerLifetimeValue();