<?php

namespace RoMarketingPro;

function handle_redirects() {
	global $wpdb;

	//Check to see if the REQUEST_URI ( with or without URL parameters ) matches a redirect URI
	$request_uri_no_params = explode( '?', $_SERVER['REQUEST_URI'] );
	$query = "SELECT option_name, option_value
FROM {$wpdb->options}
WHERE option_name like 'options_redirects_url_%_what_to_redirect'
AND
      (
            option_value = %s
          OR
            option_value = %s
      )
";

    if ($redirect = $wpdb->get_results($wpdb->prepare(
        $query,
        [
            $_SERVER['REQUEST_URI'],
            $request_uri_no_params[0]
        ]
    ))) {


		//DEBUG SNIPPET - Uncomment the line below to print out which redirect row is being matched
		//echo '<pre>'; print_r( $redirect ); echo '</pre>'; die; //@DEBUG
		//END DEBUG SNIPPET

		preg_match( '/options_redirects_urls_([0-9]+)_what_to_redirect/', $redirect[0]->option_name, $number );
		if( is_array( $number ) ) {
		    $query = "SELECT option_value
		    FROM {$wpdb->options}
		    WHERE option_name = 'options_redirects_urls_%d_where_to_redirect_to'";

		    $redirectTo = $wpdb->get_var(
		       $wpdb->prepare(
		           $query,
                  [
                      $number[1]
                  ]
               )
            );

			//Append URL parameters
			$param_string = '?';
			foreach( $_GET as $param => $value ){
				$param_string .= $param . '=' . $value . '&';
			}
			$param_string = rtrim( $param_string, '&' );

			//Make sure the URL did not originally contain URL parameters then make sure there are parameters to add
			if( strpos( $redirect[0]->option_value, '?' ) === false && strlen( $param_string ) > 1  ){
				$redirectTo .= $param_string;
			}

			wp_redirect( $redirectTo, 301 );
			die;
		}
	}
}
add_action( 'init', 'RoMarketingPro\handle_redirects' );

function ro_check_for_export_variable(){
	if( isset( $_GET['ro_redirect_export'] ) && $_GET['ro_redirect_export'] ){
		require_once 'redirects-import.php';
		$redirects_import = new RedirectsImport();
		$redirects_import->export_redirects();
	}
}
add_action( 'plugins_loaded', 'RoMarketingPro\ro_check_for_export_variable' );
