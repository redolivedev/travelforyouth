<?php

namespace RoWooCommerce;

function ro_wc_preview_email() {
	global $wpdb;
	$woocommerce = WC();
	if ( ! is_admin() ) {
		return false; die;
	}
	$mailer = $woocommerce->mailer();
	$email_options = array();
	foreach ( $mailer->emails as $key => $obj ) {
		$email_options[$key] = $obj->title;
	}

	$orders = $wpdb->get_results( 'SELECT ID,post_title,post_status FROM ' . $wpdb->posts . ' WHERE post_type = "shop_order" ORDER BY ID DESC LIMIT 100' );

	$in_order_id = isset( $_GET['order'] ) ? intval( $_GET['order'] ) : '';
	$in_email_type = isset( $_GET['email_type'] ) ? sanitize_text_field( $_GET['email_type'] ) : '';
	$order_number = is_numeric( $in_order_id ) ? (int) $in_order_id : '';
	$email_class = isset( $email_options[ $in_email_type ] ) ? $in_email_type : '';
	$order = $order_number ? wc_get_order( $order_number ) : false;
	$error = '';
	$email_html = '';
	if ( ! $in_order_id && ! $in_email_type ) {
		$error = '<p>Please select an email type and enter an order #</p>';
	} elseif ( ! $email_class ) {
		$error = '<p>Bad email type. Please select another email type</p>';
	} elseif ( ! $order ) {
		$error = '<p>Bad order #. Please select another order number</p>';
	} else {
		$email = $mailer->emails[$email_class];
		$email->object = $order;
		$email_html = apply_filters( 'woocommerce_mail_content', $email->style_inline( $email->get_content_html() ) );
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<style>
		body {
			padding-top: 60px;
			font-family: sans-serif;
		}
		.form-header {
			font: inherit;
			width: 100%;
			text-align: center;
			top: 0;
			overflow: hidden;
			-moz-transition: all 0.5s ease;
			-o-transition: all 0.5s ease;
			-webkit-transition: all 0.5s ease;
			transition: all 0.5s ease;
			position: absolute;
			bottom: 0;
			height: 60px;
			line-height: 60px;
			width: 100%;
			background-color: rgba(0, 0, 0, 0.1);
		}
		select, input {
			/* styling */
			font-size: 0.8em;
			background-color: white;
			border: thin solid #ccc;
			border-radius: 4px;
			display: inline-block;
			line-height: 1.5em;
			padding: 0.5em 3.5em 0.5em 1em;

			/* reset */

			margin: 0;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			-webkit-appearance: none;
			-moz-appearance: none;
		}

		input {
			padding-right: 1em;
			background: #fff;
		}

		select.minimal {
			background-image:
				linear-gradient(45deg, transparent 50%, gray 50%),
				linear-gradient(135deg, gray 50%, transparent 50%),
				linear-gradient(to right, #ccc, #ccc);
			background-position:
				calc(100% - 20px) calc(1em + 2px),
				calc(100% - 15px) calc(1em + 2px),
				calc(100% - 2.5em) 0.5em;
			background-size:
				5px 5px,
				5px 5px,
				1px 1.5em;
			background-repeat: no-repeat;
		}

		select.minimal:focus {
			background-image:
				linear-gradient(45deg, #aaa 50%, transparent 50%),
				linear-gradient(135deg, transparent 50%, #aaa 50%),
				linear-gradient(to right, #ccc, #ccc);
			background-position:
				calc(100% - 15px) 1em,
				calc(100% - 20px) 1em,
				calc(100% - 2.5em) 0.5em;
			background-size:
				5px 5px,
				5px 5px,
				1px 1.5em;
			background-repeat: no-repeat;
			border-color: #aaa;
			outline: 0;
		}


		select:-moz-focusring {
		  color: transparent;
		  text-shadow: 0 0 0 #000;
		}
		</style>
	</head>
	<body>
		<div class="form-header sticky">
		<form method="get" action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php">
			<input type="hidden" name="action" value="previewemail">
			<select class="minimal" name="email_type">
				<option value="--">Email Type</option>
				<?php
				foreach( $email_options as $class => $label ){
					if ( $email_class && $class == $email_class ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					?>
					<option value="<?php echo $class; ?>" <?php echo $selected; ?> ><?php echo $label; ?></option>
					<?php } ?>
				</select>
			<select class="minimal" name="order">
				<option value="--">Select Order</option>
				<?php
				foreach( $orders as $order ){
					if ( $in_order_id && $order->ID == $in_order_id ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					?>
					<option value="<?php echo $order->ID; ?>" <?php echo $selected; ?> ><?php echo $order->post_title ?>(<?php echo $order->post_status ?>)</option>
					<?php } ?>
				</select>
				<input type="submit" value="Go">
		</form>
	</div>
	<?php
	if ( $error ) {
		echo "<div class='error'>$error</div>";
	} else {
		echo $email_html;
	}
	?>
	</body>
</html>

<?php
	die;
}
add_action('wp_ajax_previewemail', 'RoWooCommerce\ro_wc_preview_email');


function ro_wc_add_link_to_preview( $settings, $current_section = null ) {
	if( ! $current_section ) {
		$settings[] = array(
			'name' => __( 'Preview Email Templates', 'ro-wc' ),
			'type' => 'title',
			'desc' => __( '<a target="_blank" href="/wp-admin/admin-ajax.php?action=previewemail">Preview Email Templates Here</a>', 'ro-wc' ),
			'id' => 'ro_preview_emails'
		);
		return $settings;
	} else {
		return $settings;
	}
}
add_filter( 'woocommerce_get_settings_email', 'RoWooCommerce\ro_wc_add_link_to_preview', 10, 2 );
