<?php

namespace RoMarketingPro;

function ro_import_redirects_csv() {
	if( $_POST['csv_data'] ){
		require_once 'redirects-import.php';
		$redirects_import = new RedirectsImport( $_POST['csv_data'], $_POST['skip_check'] );
		$redirects_import->addRedirectsToDatabase();
		$redirects_import->addFailedRedirectsToDatabase();
		wp_send_json_success( 'CSV Data Added!' );
	}
	else {
		wp_send_json_error( 'No CSV Data' );
	}
}
add_action( 'wp_ajax_ro_import_redirects_csv', 'RoMarketingPro\ro_import_redirects_csv' );

function ro_force_redirect(){
	if( $_POST['redirect_data'] ){
		require_once 'redirects-import.php';
		$single_redirect = new RedirectsImport();
		$single_redirect->addSingleRedirectToDatabase( $_POST['redirect_data'] );
		wp_send_json_success( 'Single Redirect Added' );
	} else{
		wp_send_json_error( 'No Redirect Data' );
	}
	
}
add_action( 'wp_ajax_ro_force_redirect', 'RoMarketingPro\ro_force_redirect' );

function ro_alphabetize_redirects(){
	require_once 'redirects-import.php';
	$alphabetize_redirects = new RedirectsImport();
	$alphabetize_redirects->alphabetize_redirects();
	wp_send_json_success( 'Redirects Alphabetized!' );
}
add_action( 'wp_ajax_ro_alphabetize_redirects', 'RoMarketingPro\ro_alphabetize_redirects' );

function ro_delete_all_redirects(){
	require_once 'redirects-import.php';
	$delete_all_redirects = new RedirectsImport();
	$delete_all_redirects->delete_all_redirects();
	wp_send_json_success( 'All Redirects Deleted!' );
}
add_action( 'wp_ajax_ro_delete_all_redirects', 'RoMarketingPro\ro_delete_all_redirects' );