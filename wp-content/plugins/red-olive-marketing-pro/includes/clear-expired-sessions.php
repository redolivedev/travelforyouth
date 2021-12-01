<?php
namespace RoMarketingPro;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/*
 * Add the clear expired session cron and run it
 */

class ClearExpiredSessions {

	function __construct() {
		global $wpdb;
		$this->db = &$wpdb;
		$this->set_up_cron();
	}

	function set_up_cron() {
		if( ! wp_next_scheduled( 'ro_clear_expired_transients' ) ) wp_schedule_event( strtotime( 'midnight tomorrow'), 'daily', 'ro_clear_expired_transients' );
		add_action( 'ro_clear_expired_transients', array( $this, 'ro_clear_expired_transients_callback') );
	}

	function ro_clear_expired_transients_callback() {

		// find the session ids that are expired
		$wcSesions = $this->db->get_results( 'SELECT option_name,option_value FROM ' . $this->db->options . ' WHERE option_name LIKE ("_wp_session_expires_%")' );

		$now = time();

		$deleteSessions = array();

		foreach ( $wcSesions as $wcSesion ) {
			if( $wcSesion->option_value < $now ) $deleteSessions[] = substr( $wcSesion->option_name, 20 );
		}

		// delete the expired sessions
		foreach( $deleteSessions as $deleteSession ) {
			$this->db->query( 'DELETE FROM ' . $this->db->options . ' WHERE option_name="_wp_session_'. $deleteSession .'"');
			$this->db->query( 'DELETE FROM ' . $this->db->options . ' WHERE option_name="_wp_session_expires_'. $deleteSession .'"');
		}
	}

}

$roClearExpiredSessions = new ClearExpiredSessions();
