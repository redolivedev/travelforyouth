<?php 

namespace RoMarketing;

class LiveChatTab{
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
		/**
		 * LIVE CHAT
		 */
		add_settings_section(
			'ro_live_chat_section',
			'LiveChat',
			array( __CLASS__, 'ro_live_chat_section_callback' ),
			'ro-marketing-live-chat'
		);

		/**
		 * OLARK
		 */
		add_settings_section(
			'ro_olark_section',
			'Olark',
			array( __CLASS__, 'ro_olark_section_callback' ),
			'ro-marketing-live-chat'
		);
	}

	private static function add_fields(){
		/*
		 * LIVE CHAT
		 */
		add_settings_field(
			'enable_live_chat',
			'Enable LiveChat',
			array( __CLASS__, 'enable_live_chat_callback' ),
			'ro-marketing-live-chat',
			'ro_live_chat_section'
		);

		add_settings_field(
			'live_chat_license_number',
			'LiveChat License Number',
			array( __CLASS__, 'live_chat_license_number_callback' ),
			'ro-marketing-live-chat',
			'ro_live_chat_section'
		);

		/*
		 * OLARK
		 */
		add_settings_field(
			'enable_olark',
			'Enable Olark',
			array( __CLASS__, 'enable_olark_callback' ),
			'ro-marketing-live-chat',
			'ro_olark_section'
		);

		add_settings_field(
			'olark_site_id',
			'Olark Site ID',
			array( __CLASS__, 'olark_site_id_callback' ),
			'ro-marketing-live-chat',
			'ro_olark_section'
		);
	}

	/*
	 * LIVE CHAT
	 */
	public static function ro_live_chat_section_callback()
	{
		echo '<hr />Add LiveChat script to every page.';
	}

	public static function enable_live_chat_callback()
	{
		$checked_value = isset( self::$options['enable_live_chat'] ) ? checked( self::$options['enable_live_chat'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="enable_live_chat" name="ro_marketing_options[enable_live_chat]" %s />', $checked_value );
	}

	public static function live_chat_license_number_callback()
	{
		printf(
			'<input type="text" id="live_chat_license_number" placeholder="XXXXXXX" name="ro_marketing_options[live_chat_license_number]" value="%s" />',
			isset( self::$options['live_chat_license_number'] ) ? esc_attr( self::$options['live_chat_license_number']) : ''
		);
	}

	/*
	 * OLARK
	 */
	public static function ro_olark_section_callback()
	{
		echo '<hr />Add Olark script to every page.';
	}

	public static function enable_olark_callback()
	{
		$checked_value = isset( self::$options['enable_olark'] ) ? checked( self::$options['enable_olark'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="enable_olark" name="ro_marketing_options[enable_olark]" %s />', $checked_value );
	}

	public static function olark_site_id_callback()
	{
		printf(
			'<input type="text" id="olark_site_id" placeholder="XXXX-XXX-XX-XXXX" name="ro_marketing_options[olark_site_id]" value="%s" />',
			isset( self::$options['olark_site_id'] ) ? esc_attr( self::$options['olark_site_id']) : ''
		);
	}

}