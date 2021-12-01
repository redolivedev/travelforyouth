<?php

namespace RoWooCommerce;

/**
 * Finds rows in the database where the email and cart_contents are the same and deletes the older row. 
 */
function clear_db_duplicates(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'ro_abandoned_cart';
    
	$results = $wpdb->query(
        "DELETE FROM wp_ro_abandoned_cart WHERE id IN (
            SELECT min FROM (
                SELECT MIN(id) as min, COUNT(email) as count 
                    FROM wp_ro_abandoned_cart 
                    WHERE checkout_at IS NULL
                    GROUP BY email, cart_contents 
                    HAVING count > 1
            ) AS duplicates
        );"
    );
}

/** NOTE: THIS FUNCTION NEEDS TO BE UPDATED WITH THE LATEST CHANGES TO ITS HELPER FUNCTIONS **/
function add_emails_to_mailchimp(){
	global $roWcOptions;

	if( !isset( $roWcOptions['abandoned_cart_mailchimp_key'] ) || !isset( $roWcOptions['abandoned_cart_mailchimp_list'] ) || !isset( $roWcOptions['abandoned_cart_mailchimp_config'] ) || $roWcOptions['abandoned_cart_mailchimp_config'] == 'false' ){
		return false;
	}
	else{
		$mailchimp_key = $roWcOptions['abandoned_cart_mailchimp_key'];
		$mailchimp_list = $roWcOptions['abandoned_cart_mailchimp_list'];
	}

	$results = get_email_addresses();

	if( !$results ){
		return false;
	}

	if( ( $pos = strpos( $mailchimp_key, "-" ) ) !== FALSE ) {
	    $dc = substr( $mailchimp_key, $pos + 1 );
	}
	else{
		return false;
	}	

	foreach( $results as $result ){
		$data = array( 
		'email_address' => $result->email,
		'status' => 'subscribed',
		'merge_fields' => array(
				'EMAILHASH' => $result->hash
			)
		);

		$body = json_encode( $data );

		$args = array(
			'method' => 'PUT',
			'headers' => array(
				'content-type' => 'application/json',
				'Authorization' => 'apikey ' . $mailchimp_key
			),
			'body' => $body
		);

		$list = 'https://' . $dc . '.api.mailchimp.com/3.0/lists/' . $mailchimp_list . '/members/' 
		. md5( strtolower( $result->email ) );


		$add_member = wp_remote_request( $list, $args );
		
		if( $add_member['response']['code'] == '200' ) {
			update_abandoned_cart_db( $result ); 
		} 
	}
}

function send_system_email( $test_email = false ){
	if( ! function_exists( 'RoWooCommerce\money_format' ) ){
		require_once RO_WC_DIR . 'includes/helper-files/backup-money-format.php';
	}

	setlocale( LC_MONETARY, 'en_US.UTF-8' ); //Set locale for money_format function
	$rac_options = set_up_rac_options();

	$initial_result = run_initial_emails( $rac_options, $test_email );

	if( $rac_options['3_day_email'] ){
		$three_day_result = run_three_day_emails( $rac_options, $test_email );
	}

	if( $rac_options['7_day_email'] ){
		$seven_day_result = run_seven_day_emails( $rac_options, $test_email );
	}
    
	if( $test_email ){
		if( $initial_result ){
			$initial_message = 'Initial test email sent!';
		}else{
			$initial_message = 'No initial emails to send.';
		}

		if( isset( $three_day_result ) && $three_day_result ){
			$three_day_message = 'Three day test email sent!';
		}else{
			$three_day_message = 'No three day emails to send.';
		}

		if( isset( $seven_day_result ) && $seven_day_result ){
			$seven_day_message = 'Seven day test email sent!';
		}else{
			$seven_day_message = 'No seven day emails to send.';
		}

		wp_send_json_success( $initial_message . '<br/>' . $three_day_message . '<br/>' . $seven_day_message );
	}
}

function run_initial_emails( $rac_options, $test_email = false ){
	$emails = get_email_addresses( 'initial' );

	if( ! $emails ){
		return false;
	}

	$rac_options['interval'] = 'initial';
	$rac_options['db_interval'] = 'sent_at_initial';
	$rac_options['utm_code'] = 'ro_abandoncart1';

	foreach( $emails as $email ){
        // Don't send the initial email until at least an hour has passed
        $created = new \DateTime( $email->created_at );
        $created_plus_hour = $created->modify( '+ 1 hour' );
        $now = new \DateTime();
        if( $now < $created ) continue;

		$cart_array = unserialize( $email->cart_contents );
		$full_return_url = $rac_options['return_url'] . '?returncart=' . $email->hash . '-' . $email->id;
		if( $rac_options['discount_code'] ){
			$full_return_url .= '&coupon=' . $rac_options['discount_code'];
		}

		if( file_exists( get_stylesheet_directory() . '/email-templates/initial-rac-template.php' ) ){
			ob_start();
			require get_stylesheet_directory() . '/email-templates/initial-rac-template.php';
			$rac_options['message'] = ob_get_clean();
		}elseif( file_exists( RO_WC_DIR . 'includes/templates/default-abandoned-cart-template.php' ) ){
			ob_start();
			require RO_WC_DIR . 'includes/templates/default-abandoned-cart-template.php';
			$rac_options['message'] = ob_get_clean();
		}else{
			return false;
		}

		$email_result = send_the_email( $rac_options, $email, $test_email );

		if( $test_email && $email_result ){
			return true;
		}
	}
}

function run_three_day_emails( $rac_options, $test_email = false ){
    $emails = get_email_addresses( '3_day' );

	if( ! $emails ){
		return false;
	}

	$rac_options['interval'] = '3_day';
	$rac_options['db_interval'] = 'sent_at_3day';
	$rac_options['utm_code'] = 'ro_abandoncart2';

	foreach( $emails as $email ){
		$initial = new \DateTime( $email->sent_at_initial );
        $plus_three = $initial->modify( '+3 days' );
        $initial = new \DateTime( $email->sent_at_initial );
		$plus_five = $initial->modify( '+5 days' );
        $today = new \DateTime();
        
        // Don't send email if outside of the acceptable date range
		if( $today <= $plus_three || $today > $plus_five ){
			continue;
		}	
		
		$cart_array = unserialize( $email->cart_contents );
		$full_return_url = $rac_options['return_url'] . '?returncart=' . $email->hash . '-' . $email->id;
		if( $rac_options['3_day_code'] ){
			$full_return_url .= '&coupon=' . $rac_options['3_day_code'];
		}		

		if( file_exists( get_stylesheet_directory() . '/email-templates/three-day-rac-template.php' ) ){
			ob_start();
			require get_stylesheet_directory() . '/email-templates/three-day-rac-template.php';
			$rac_options['message'] = ob_get_clean();
		}elseif( file_exists( RO_WC_DIR . 'includes/templates/default-abandoned-cart-template.php' ) ){
			ob_start();
			require RO_WC_DIR . 'includes/templates/default-abandoned-cart-template.php';
			$rac_options['message'] = ob_get_clean();
		}else{
			return false;
		}

		$email_result = send_the_email( $rac_options, $email, $test_email );

		if( $test_email && $email_result ){
			return true;
		}
	}
}

function run_seven_day_emails( $rac_options, $test_email = false ){
    $emails = get_email_addresses( '7_day' );
    
	if( ! $emails ){
		return false;
	}

	$rac_options['interval'] = '7_day';
	$rac_options['db_interval'] = 'sent_at_7day';
	$rac_options['utm_code'] = 'ro_abandoncart3';

	foreach( $emails as $email ){
		$initial = new \DateTime( $email->sent_at_initial );
        $plus_seven = $initial->modify( '+7 days' );
        $initial = new \DateTime( $email->sent_at_initial );
		$plus_ten = $initial->modify( '+10 days' );
        $today = new \DateTime();
        
        // Don't send email if outside of the acceptable date range
		if( $today <= $plus_seven || $today > $plus_ten ){
			continue;
        }
		
		$cart_array = unserialize( $email->cart_contents );
		$full_return_url = $rac_options['return_url'] . '?returncart=' . $email->hash . '-' . $email->id;
		if( $rac_options['7_day_code'] ){
			$full_return_url .= '&coupon=' . $rac_options['7_day_code'];
		}		

		if( file_exists( get_stylesheet_directory() . '/email-templates/seven-day-rac-template.php' ) ){
			ob_start();
			require get_stylesheet_directory() . '/email-templates/seven-day-rac-template.php';
			$rac_options['message'] = ob_get_clean();
		}elseif( file_exists( RO_WC_DIR . 'includes/templates/default-abandoned-cart-template.php' ) ){
			ob_start();
			require RO_WC_DIR . 'includes/templates/default-abandoned-cart-template.php';
			$rac_options['message'] = ob_get_clean();
		}else{
			return false;
		}		

		$email_result = send_the_email( $rac_options, $email, $test_email );

		if( $test_email && $email_result ){
			return true;
		}
	}
}

function get_email_addresses( $interval = false ){
	if( ! $interval ){
		return false;
	}

	global $wpdb;
    $table_name = $wpdb->prefix . 'ro_abandoned_cart';
    
    $today = (string)date('Y-m-d');

	if( $interval == 'initial' ){
		$column_query = 'sent_at_initial IS NULL';
	}elseif( $interval == '3_day' ){
        $column_query = '
        sent_at_initial IS NOT NULL 
        AND sent_at_initial NOT LIKE "%' . $today . '%" 
        AND sent_at_3day IS NULL';
	}elseif( $interval == '7_day' ){
        $column_query = '
        sent_at_initial IS NOT NULL 
        AND sent_at_3day IS NOT NULL 
        AND sent_at_3day NOT LIKE "%' . $today . '%" 
        AND sent_at_7day IS NULL';
	}

	$results = $wpdb->get_results(	
		"SELECT * FROM " . $table_name . " WHERE " . $column_query . " AND checkout_at IS NULL"
	);

	if( ! $results ){
		return false;
	}

	return $results;
}

function send_the_email( $rac_options, $email, $test_email ){
	if( $test_email ){
		if( wp_mail( $test_email, $rac_options['subject'], $rac_options['message'], $rac_options['headers'] ) ){
			return true;
		}else{
			return false;
		}		
	}
	else{ //Real email call
		if( wp_mail( $email->email, $rac_options['subject'], $rac_options['message'], $rac_options['headers'] ) ){
			update_abandoned_cart_db( $email, $rac_options );
		}
	}
}

function set_up_rac_options(){
	global $roWcOptions;

	//Assign all of the variables from the settings section in the back end
	$rac_options = array(
		'from_name' 		=> isset( $roWcOptions['abandoned_cart_from_name'] ) && 
								$roWcOptions['abandoned_cart_from_name'] ? 
								$roWcOptions['abandoned_cart_from_name'] : get_bloginfo( 'name' ),
		'from_email' 		=> isset( $roWcOptions['abandoned_cart_from_email'] ) && 
								$roWcOptions['abandoned_cart_from_email'] ? 
								$roWcOptions['abandoned_cart_from_email'] : get_bloginfo('admin_email'),
		'return_url' 		=> isset( $roWcOptions['abandoned_cart_return_url'] ) && 
								$roWcOptions['abandoned_cart_return_url'] ? 
								$roWcOptions['abandoned_cart_return_url'] : get_site_url() . '/cart/',
		'logo_url' 			=> isset( $roWcOptions['abandoned_cart_logo_url'] ) && 
								$roWcOptions['abandoned_cart_logo_url'] ? 
								$roWcOptions['abandoned_cart_logo_url'] : false,
		'logo_width'		=> isset( $roWcOptions['abandoned_cart_logo_width'] ) && 
								$roWcOptions['abandoned_cart_logo_width'] ? 
								$roWcOptions['abandoned_cart_logo_width'] : false,
		'logo_height'		=> isset( $roWcOptions['abandoned_cart_logo_height'] ) && 
								$roWcOptions['abandoned_cart_logo_height'] ? 
								$roWcOptions['abandoned_cart_logo_height'] : false,
		'button_text'		=>	isset( $roWcOptions['abandoned_cart_button_text'] ) && 
								$roWcOptions['abandoned_cart_button_text'] ? 
								$roWcOptions['abandoned_cart_button_text'] : 'View Cart',
		'subject'			=> isset( $roWcOptions['abandoned_cart_email_subject'] ) && 
								$roWcOptions['abandoned_cart_email_subject'] ? 
								$roWcOptions['abandoned_cart_email_subject'] : 
								'Come Back! You Have Items in Your Cart!',
		'email_message'		=> isset( $roWcOptions['abandoned_cart_email_message'] ) && 
								$roWcOptions['abandoned_cart_email_message'] ? 
								$roWcOptions['abandoned_cart_email_message'] : 
								'You left some things behind in your cart. We set them aside while you took care of the rest of your shopping. Swing back by and pick up where you left off!',
		'cart_discount'		=> isset( $roWcOptions['abandoned_cart_discount'] ) && 
								$roWcOptions['abandoned_cart_discount'] ? 
								$roWcOptions['abandoned_cart_discount'] : '',
		'discount_code'		=> isset( $roWcOptions['abandoned_cart_discount_code'] ) && 
								$roWcOptions['abandoned_cart_discount_code'] ? 
								$roWcOptions['abandoned_cart_discount_code'] : '',
		'3_day_email'		=> isset( $roWcOptions['abandoned_cart_3_day_enabled'] ) && 
								$roWcOptions['abandoned_cart_3_day_enabled'] ? 
								true : false,	
		'3_day_discount'	=> isset( $roWcOptions['abandoned_cart_3_day_discount'] ) && 
								$roWcOptions['abandoned_cart_3_day_discount'] ? 
								$roWcOptions['abandoned_cart_3_day_discount'] : '',
		'3_day_code'		=> isset( $roWcOptions['abandoned_cart_3_day_discount_code'] ) && 
								$roWcOptions['abandoned_cart_3_day_discount_code'] ? 
								$roWcOptions['abandoned_cart_3_day_discount_code'] : '',
		'7_day_email'		=> isset( $roWcOptions['abandoned_cart_7_day_enabled'] ) && 
								$roWcOptions['abandoned_cart_7_day_enabled'] ? 
								true : false,
		'7_day_discount'	=> isset( $roWcOptions['abandoned_cart_7_day_discount'] ) && 
								$roWcOptions['abandoned_cart_7_day_discount'] ? 
								$roWcOptions['abandoned_cart_7_day_discount'] : '',
		'7_day_code'		=> isset( $roWcOptions['abandoned_cart_7_day_discount_code'] ) && 
								$roWcOptions['abandoned_cart_7_day_discount_code'] ? 
								$roWcOptions['abandoned_cart_7_day_discount_code'] : '',
	);

	if( ! $rac_options['discount_code'] || ! $rac_options['cart_discount'] ){
		$rac_options['discount_code'] = '';
		$rac_options['cart_discount'] = '';
	}

	if( ! $rac_options['3_day_code'] || ! $rac_options['3_day_discount'] ){
		$rac_options['3_day_code'] = '';
		$rac_options['3_day_discount'] = '';
	}

	if( ! $rac_options['7_day_code'] || ! $rac_options['7_day_discount'] ){
		$rac_options['7_day_code'] = '';
		$rac_options['7_day_discount'] = '';
	}

	if( ! $rac_options['logo_url'] || ! $rac_options['logo_width'] || ! $rac_options['logo_height'] ){
		$rac_options['logo_valid'] = false;
	}

	$rac_options['headers'] = "From: " . $rac_options['from_name'] . " <" . $rac_options['from_email'] . ">" . "\r\n";
	$rac_options['headers'] .= "MIME-Version: 1.0" . "\r\n";
	$rac_options['headers'] .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
	$rac_options['headers'] .= 'X-Mailer: PHP/' . phpversion();

	return $rac_options;
}

function update_abandoned_cart_db( $email, $rac_options ){
	global $wpdb;
	$table_name = $wpdb->prefix . 'ro_abandoned_cart';

	$wpdb->update(
		$table_name,
		array(
			$rac_options['db_interval'] => date('Y-m-d H:i:s')
		),
		array(
			'ID' => $email->id
		),
		array(
			'%s'
		),
		array(
			'%d'
		)
	);
}

/**
 * DEBUGGING FUNCTION FOR WHEN WP_MAIL FAILS
 */
function mail_failed( $wp_error ){
	echo '<pre>'; print_r( $wp_error ); echo '</pre>'; die; //@DEBUG
}
// add_action( 'wp_mail_failed', 'RoWooCommerce\mail_failed' ); //Uncomment to use
