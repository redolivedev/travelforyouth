<?php

namespace RoMarketingPro;

class RedirectsImport {

	protected $redirects_array;
	protected $urls_to_redirect;
	protected $urls_to_not_redirect;
	protected $illegal_ip_array;

	public function __construct( $redirects_json = null, $skip_check = 'false' ){

		if( $redirects_json ){
			//Clean out any weird //r characters from the JSON data
			$redirects_json = str_replace( "\\\\r", "", $redirects_json );

			//Clean out any stray backslashes from the JSON data
			$clean_json = stripcslashes( $redirects_json );

			//Now finally decode the JSON
			$this->redirects_array = json_decode( $clean_json );

			//Set up the other arrays
			$this->urls_to_redirect = array();
			$this->urls_to_not_redirect = array();
			$this->illegal_ip_array = array( 
				'/cart/',
				'/checkout/',
				'/wp-admin/',
				'index.php',
				'wp-login.php',
				'admin-ajax.php'
			);

			//Set if URL HTTP error checking should be skipped
			if( $skip_check === 'true' ){
				$this->skip_check = true;
			}else{
				$this->skip_check = false;
			}			
			
			//Set up the URL arrays
			$this->separate_bad_urls();
		}
	}

	function separate_bad_urls(){

		//Shift the header index off of the array
		array_shift( $this->redirects_array );

		$base_url = $this->get_base_url();

		foreach( $this->redirects_array as $redirect ){

			$uri = $this->normalizeURI( $redirect->what_to_redirect );

			$illegal_ip = false;
			foreach( $this->illegal_ip_array as $illegal ){
				if( strrpos( $uri, $illegal ) !== false ){
					$illegal_ip = true;
					break;
				}
			}

			if( $illegal_ip ){
				continue;
			}

			if( ! $this->skip_check ){
				$result = wp_remote_get( $base_url . $uri, array( 'redirection' => 0, 'sslverify' => false ) );

				if( $result['response']['code'] ){
					$code = $result['response']['code'];

					if( $code == 200 || $code == 301 ){
						$redirect->http_code = $code;
						$redirect->what_to_redirect = $uri;
						$this->urls_to_not_redirect[] = $redirect;
					}else{
						$redirect->what_to_redirect = $uri;
						$this->urls_to_redirect[] = $redirect;
					}
				}else{
					$redirect->http_code = 'Unknown';
					$redirect->what_to_redirect = $uri;
					$this->urls_to_not_redirect[] = $redirect;
				}				
			}else{
				$redirect->what_to_redirect = $uri;
				$this->urls_to_redirect[] = $redirect;
			}

		}
	}

	public function addRedirectsToDatabase(){
				
		foreach( $this->urls_to_redirect as $redirect ){
			$this->addRedirectToDatabase( $redirect->what_to_redirect, $redirect->where_to_redirect_to );
		}
	}

	public function addFailedRedirectsToDatabase(){

		foreach( $this->urls_to_not_redirect as $no_redirect ){

			$row_number = get_option( 'options_failed_redirects' );

			if( ! $row_number ){
				$row_number = 0;
			}

			update_option( '_options_failed_redirects_' . $row_number .'_what_to_redirect', 'field_56f0665fd1169' );
			update_option( 'options_failed_redirects_' . $row_number .'_what_to_redirect', $no_redirect->what_to_redirect );

			update_option( '_options_failed_redirects_' . $row_number .'_where_to_redirect_to', 'field_56f0665fd116a' );
			update_option( 'options_failed_redirects_' . $row_number .'_where_to_redirect_to', $no_redirect->where_to_redirect_to );

			update_option( '_options_failed_redirects_' . $row_number .'_failure_reason', 'field_56f06692d116b' );
			update_option( 'options_failed_redirects_' . $row_number .'_failure_reason', 'Code ' . $no_redirect->http_code );

			$row_number++;

			if( $row_number == 1 ){
				update_option( '_options_failed_redirects', 'field_56f0665fd1168' );
			}

			update_option( 'options_failed_redirects', $row_number );
		}

	}

	public function addSingleRedirectToDatabase( $redirect ){

		//Get the element number from the weird encoded array given
		preg_match_all("/\[[^\]]*\]/", $redirect, $matches);

		foreach( $matches[0] as &$match ){
			$match = str_replace( array('[', ']'), '', $match);
		}

		$selected_row_number = $matches[0][1];
		$failed_redirects_row_number = get_option( 'options_failed_redirects' );

		//Get the information we need
		$what_to_redirect = get_option( 'options_failed_redirects_' . $selected_row_number .'_what_to_redirect' );
		$where_to = get_option( 'options_failed_redirects_' . $selected_row_number .'_where_to_redirect_to' );

		//Delete the rows for this redirect from the failed redirects section
		delete_option( '_options_failed_redirects_' . $selected_row_number .'_force_redirect' );
		delete_option( 'options_failed_redirects_' . $selected_row_number .'_force_redirect' );
		delete_option( '_options_failed_redirects_' . $selected_row_number .'_failure_reason' );
		delete_option( 'options_failed_redirects_' . $selected_row_number .'_failure_reason' );
		delete_option( '_options_failed_redirects_' . $selected_row_number .'_where_to_redirect_to' );
		delete_option( 'options_failed_redirects_' . $selected_row_number .'_where_to_redirect_to' );
		delete_option( '_options_failed_redirects_' . $selected_row_number .'_what_to_redirect' );
		delete_option( 'options_failed_redirects_' . $selected_row_number .'_what_to_redirect' );

		//Decrement the row number for the failed redirects section
		update_option( 'options_failed_redirects', ( $failed_redirects_row_number - 1 ) );

		//Renumber the failed redirects section
		$this->renumberFailedRedirectsRows( $selected_row_number );

		//Add the information to the redirects section
		$this->addRedirectToDatabase( $what_to_redirect, $where_to );
	}

	function addRedirectToDatabase( $what, $where ){

		$row_number = get_option( 'options_redirects_urls' );

		if( ! $row_number ){
			$row_number = 0;
		}

		update_option( '_options_redirects_urls_' . $row_number .'_what_to_redirect', 'field_55c8df9a25496' );
		update_option( 'options_redirects_urls_' . $row_number .'_what_to_redirect', $what );
		update_option( '_options_redirects_urls_' . $row_number .'_where_to_redirect_to', 'field_55c8dfe025497' );
		update_option( 'options_redirects_urls_' . $row_number .'_where_to_redirect_to', $where );
		
		$row_number++;

		//If this is the first row, add the necessary information to set up the redirects in the database
		if( $row_number == 1 ){
			update_option( '_options_redirects_urls', 'field_55c8df8825495' );
		}

		update_option( 'options_redirects_urls', $row_number );
	}

	function renumberFailedRedirectsRows( $curr_number ){

		$next_number = intval( $curr_number ) + 1;

		$next_row = get_option( '_options_failed_redirects_' . $next_number .'_what_to_redirect' );

		if( ! $next_row ){
			return;
		}

		//Grab the values from the next rows
		$_force = get_option( '_options_failed_redirects_' . $next_number .'_force_redirect' );
		$force = get_option( 'options_failed_redirects_' . $next_number .'_force_redirect' );
		$_failure = get_option( '_options_failed_redirects_' . $next_number .'_failure_reason' );
		$failure = get_option( 'options_failed_redirects_' . $next_number .'_failure_reason' );
		$_where = get_option( '_options_failed_redirects_' . $next_number .'_where_to_redirect_to' );
		$where = get_option( 'options_failed_redirects_' . $next_number .'_where_to_redirect_to' );
		$_what = get_option( '_options_failed_redirects_' . $next_number .'_what_to_redirect' );
		$what = get_option( 'options_failed_redirects_' . $next_number .'_what_to_redirect' );

		//Delete the next rows
		delete_option( '_options_failed_redirects_' . $next_number .'_force_redirect' );
		delete_option( 'options_failed_redirects_' . $next_number .'_force_redirect' );
		delete_option( '_options_failed_redirects_' . $next_number .'_failure_reason' );
		delete_option( 'options_failed_redirects_' . $next_number .'_failure_reason' );
		delete_option( '_options_failed_redirects_' . $next_number .'_where_to_redirect_to' );
		delete_option( 'options_failed_redirects_' . $next_number .'_where_to_redirect_to' );
		delete_option( '_options_failed_redirects_' . $next_number .'_what_to_redirect' );
		delete_option( 'options_failed_redirects_' . $next_number .'_what_to_redirect' );

		//Put them on the curr row
		update_option( '_options_failed_redirects_' . $curr_number .'_force_redirect', $_force );
		update_option( 'options_failed_redirects_' . $curr_number .'_force_redirect', $force );
		update_option( '_options_failed_redirects_' . $curr_number .'_failure_reason', $_failure );
		update_option( 'options_failed_redirects_' . $curr_number .'_failure_reason', $failure );
		update_option( '_options_failed_redirects_' . $curr_number .'_where_to_redirect_to', $_where );
		update_option( 'options_failed_redirects_' . $curr_number .'_where_to_redirect_to', $where );
		update_option( '_options_failed_redirects_' . $curr_number .'_what_to_redirect', $_what );
		update_option( 'options_failed_redirects_' . $curr_number .'_what_to_redirect', $what );

		//Recursively call this function on the next row
		$this->renumberFailedRedirectsRows( $next_number );
	}

	public function delete_all_redirects(){
		global $wpdb;

		$delete = $wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE  option_name LIKE "%options_failed_redirects%" OR option_name LIKE "%options_redirects_urls%"' );		
	}

	public function alphabetize_redirects(){
		$redirects_array = $this->get_all_redirects();

		if( ! $redirects_array || count( $redirects_array ) < 2 ){
			return;
		}

		usort( $redirects_array, array( $this, 'cmp' ) ); //Sorts array by property of the objects it contains

		foreach( $redirects_array as $index => $redirect ){
			update_option( 'options_redirects_urls_' . $index .'_what_to_redirect', $redirect->what );
			update_option( 'options_redirects_urls_' . $index .'_where_to_redirect_to', $redirect->where );
		}
	}

	public function export_redirects(){
		$redirects_array = $this->get_all_redirects();
		
		if( ! $redirects_array ){
			return;
		}

		//Add first row
		$redirect_data[] = array( 'what_to_redirect', 'where_to_redirect_to' );

		//Add the rest of the rows
		foreach( $redirects_array as $redirect ){

			//Create an array to store the redirect information
			$redirect_array = array();

			$redirect_array['what_to_redirect'] = $redirect->what;
			$redirect_array['where_to_redirect_to'] = $redirect->where;

			$redirect_data[] = $redirect_array;
		}

		//Set up the header
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'. get_bloginfo( 'name' ).'-'. time() .'.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');

		//Set it to output to the browser (since it's a csv file, the browser will download it)
		$output = fopen('php://output', 'w');

		foreach( $redirect_data as $the_redirect ){	
			fputcsv( $output, $the_redirect );
		}

		fclose( $output );
		die;
	}


	/***********************
	Helper Functions
	************************/
	function get_all_redirects(){
		$total_rows = get_option( 'options_redirects_urls' );

		if( ! $total_rows || $total_rows < 1 ){
			return false;
		}

		$redirects_array = array();
		$current_row = 0;
		while( $current_row < $total_rows ){
			$what = get_option( 'options_redirects_urls_' . $current_row .'_what_to_redirect' );
			$where = get_option( 'options_redirects_urls_' . $current_row .'_where_to_redirect_to' );

			$redirect = new \stdClass;
			$redirect->what = $what;
			$redirect->where = $where;

			$redirects_array[] = $redirect;
			$current_row++;
		}

		return $redirects_array;
	}

	function cmp( $a, $b ){
		return strcmp( $a->what, $b->what );
	}

	function normalizeURI( $uri ){
		if( ! $this->startsWithSlash( $uri ) ){
			$uri = '/' . $uri;
		}

		if( ! $this->endsWithSlash( $uri ) ){
			$uri = $uri . '/';
		}

		//Check if the URI is a file
		$dot_pos = strrpos( $uri, '.' );
		$ext = $dot_pos === false ? '' : substr( $uri, $dot_pos + 1 );

		if( ! $ext ){
			return $uri;
		}

		if( strlen( $ext ) > 2 ){
			return preg_replace( '#/$#', '', $uri ); //Using # for the delimiter instead of '/'
		}

		return $uri;
	}

	function get_base_url(){
		$protocol = 'http://';

		if( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ){
			$protocol = 'https://';
		}

		return $protocol . $_SERVER['HTTP_HOST'];
	}

	function startsWithSlash( $uri ) {
	    return substr( $uri, 0, 1 ) === '/';
	}

	function endsWithSlash( $uri ) {
	    return substr( $uri, -1, 1 ) === '/';
	}
}