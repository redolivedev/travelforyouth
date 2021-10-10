<?php

namespace RoMarketing;

class RedirectsTab{
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
		 * REDIRECTS
		 */
		add_settings_section(
			'ro_redirects_section',
			'Easy 301 Redirects Manager',
			array( __CLASS__, 'ro_redirects_section_callback'),
			'ro-marketing-redirects'
		);
	}

	private static function add_fields(){

		if( ! RO_MARKETING_PRO_ACTIVE ){
			/*
			 * REDIRECTS
			 */
			add_settings_field(
				'redirects_settings',
				'Options',
				array( __CLASS__, 'ro_redirects_settings_callback'),
				'ro-marketing-redirects',
				'ro_redirects_section'
			);
		}
	}

	/*
	 * REDIRECTS
	 */
	public static function ro_redirects_section_callback()
	{
		echo '<hr />Add 301 redirects one at a time or via a bulk CSV upload';
	}

	public static function ro_redirects_settings_callback()
	{
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}
}