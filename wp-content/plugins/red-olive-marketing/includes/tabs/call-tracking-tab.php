<?php

namespace RoMarketing;

class CallTrackingTab{

	protected static $options;

	public static function init(){
		self::set_singleton();
		self::add_sections();
		self::add_fields();
	}

	private static function set_singleton(){
		$options_singleton = RoMarketingOptions::get_instance();
		self::$options = $options_singleton->get_options();
	}

	private static function add_sections(){
		/*
		 * CALL TRACKING
		 */
		add_settings_section(
			'ro_call_tracking_section',
			'CallTrackingMetrics',
			array( __CLASS__, 'ro_call_tracking_section_callback' ),
			'ro-marketing-call-tracking'
		);
	}

	private static function add_fields(){
		/*
		 * CALL TRACKING
		 */
		add_settings_field(
			'call_tracking',
			'Enable CallTrackingMetrics',
			array( __CLASS__, 'call_tracking_callback' ),
			'ro-marketing-call-tracking',
			'ro_call_tracking_section'
		);

		add_settings_field(
			'call_tracking_account_id',
			'CallTrackingMetrics Account ID',
			array( __CLASS__, 'call_tracking_account_id_callback' ),
			'ro-marketing-call-tracking',
			'ro_call_tracking_section'
		);

		add_settings_field(
			'call_tracking_metrics_sign_up',
			'CallTrackingMetrics Sign Up',
			array( __CLASS__, 'call_tracking_metrics_sign_up_callback' ),
			'ro-marketing-call-tracking',
			'ro_call_tracking_section'
		);
	}

	/*
	 * CALL TRACKING
	 */
	public static function ro_call_tracking_section_callback()
	{
		echo '<hr />Add call tracking script to every page';
	}
	
	public static function call_tracking_callback()
	{
		$checked_value = isset( self::$options['call_tracking'] ) ? checked( self::$options['call_tracking'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="call_tracking" name="ro_marketing_options[call_tracking]" %s />', $checked_value );
	}

	public static function call_tracking_account_id_callback()
	{
		printf(
			'<input type="text" id="call_tracking_account_id" placeholder="xxxxx" name="ro_marketing_options[call_tracking_account_id]" value="%s" />',
			isset( self::$options['call_tracking_account_id'] ) ? esc_attr( self::$options['call_tracking_account_id']) : ''
		);
	}

	public static function call_tracking_metrics_sign_up_callback()
	{
		printf(
			'<p>Sign up for a CallTrackingMetrics account via Red Olive\'s discounted rates <a target="_blank" href="https://calltracking.redolive.com/signup_pages/SUP70F9FE0230025EBFD8157CBCBD1056F769F177441647017A/signup">HERE</a></p>'
		);
	}
}