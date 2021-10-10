<?php

namespace RoMarketing;

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

		if( ! RO_MARKETING_PRO_ACTIVE ){
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
		}
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
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}

	public static function ro_enable_media_file_renaming_callback()
    {
    	echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
    }

    public static function ro_clear_expired_sessions_callback()
    {
    	echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
    }

    public static function ro_force_https_callback()
	{
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}
}