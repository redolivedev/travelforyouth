<?php

namespace RoMarketingPro;

class SettingsTab{
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
		 * SETTINGS
		 */
		add_settings_section(
			'ro_settings_section',
			'Settings',
			array( __CLASS__, 'ro_settings_section_callback' ),
			'ro-marketing-settings'
		);
	}

	private static function add_fields(){
		/*
		 * SETTINGS
		 */
		add_settings_field(
			'stop_comment_spam',
			'Stop Comment Spam',
			array( __CLASS__, 'ro_stop_comment_spam_callback' ),
			'ro-marketing-settings',
			'ro_settings_section'
		);

		add_settings_field(
            'enable_media_file_renaming',
            'Enable file renaming in the media library',
            array( __CLASS__, 'ro_enable_media_file_renaming_callback' ),
            'ro-marketing-settings',
            'ro_settings_section'
        );

        add_settings_field(
			'clear_expired_sessions',
			'Clear expired sessions automatically',
			array( __CLASS__, 'ro_clear_expired_sessions_callback' ),
			'ro-marketing-settings',
			'ro_settings_section'
		);

		add_settings_field(
			'force_https',
			'Force HTTPS Everywhere',
			array( __CLASS__, 'ro_force_https_callback' ),
			'ro-marketing-settings',
			'ro_settings_section'
		);

		add_settings_field(
			'external_links',
			'Open External Links in a New Tab',
			array( __CLASS__, 'external_links_callback' ),
			'ro-marketing-settings',
			'ro_settings_section'
		);
	}

	/*
	 * SETTINGS
	 */
	public static function ro_settings_section_callback()
	{
		echo '<hr />Comment spam protection / Maintenance / Force SSL';
	}

	public static function ro_stop_comment_spam_callback()
	{
		$checked_value = isset( self::$options['stop_comment_spam'] ) ? checked( self::$options['stop_comment_spam'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="stop_comment_spam" name="ro_marketing_options[stop_comment_spam]" %s />', $checked_value );
	}

	public static function ro_enable_media_file_renaming_callback()
    {
    	$checked_value = isset( self::$options['enable_media_file_renaming'] ) ? checked( self::$options['enable_media_file_renaming'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="enable_media_file_renaming" name="ro_marketing_options[enable_media_file_renaming]" %s />', $checked_value );
    }

	public static function ro_clear_expired_sessions_callback()
	{
		$checked_value = isset( self::$options['clear_expired_sessions'] ) ? checked( self::$options['clear_expired_sessions'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="clear_expired_sessions" name="ro_marketing_options[clear_expired_sessions]" %s />', $checked_value );
	}

	public static function ro_force_https_callback()
	{
		$checked_value = isset( self::$options['force_https'] ) ? checked( self::$options['force_https'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="force_https" name="ro_marketing_options[force_https]" %s />', $checked_value );
	}

	public static function external_links_callback()
	{
		$checked_value = isset( self::$options['external_links'] ) ? checked( self::$options['external_links'], true, false ) : false;
		printf( '<input type="checkbox" value="1" id="external_links" name="ro_marketing_options[external_links]" %s />', $checked_value );
	}

}
