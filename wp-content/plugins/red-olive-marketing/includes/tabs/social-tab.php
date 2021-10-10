<?php

namespace RoMarketing;

class SocialTab{
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
		 * FACEBOOK PIXEL
		 */
		add_settings_section(
			'ro_facebook_pixel_section',
			'Facebook Conversion Tracking Pixel',
			array( __CLASS__, 'ro_facebook_pixel_section_callback' ),
			'ro-marketing-social'
		);
	}

	private static function add_fields(){
		/*
		 * FACEBOOK PIXEL
		 */
		add_settings_field(
			'ro_facebook_pixel',
			'Enable Facebook Pixel',
			array(__CLASS__, 'ro_facebook_pixel_callback'),
			'ro-marketing-social',
			'ro_facebook_pixel_section'
		);

		add_settings_field(
			'ro_facebook_pixel_id',
			'Facebook Pixel ID',
			array( __CLASS__, 'ro_facebook_pixel_id_callback' ),
			'ro-marketing-social',
			'ro_facebook_pixel_section'
		);
	}

	/*
	 * FACEBOOK PIXEL
	 */
	public static function ro_facebook_pixel_section_callback()
	{
		echo '<hr />Add Facebook conversion tracking pixel on every page';
	}

	public static function ro_facebook_pixel_callback()
	{
		$checked_value = isset( self::$options['ro_facebook_pixel'] ) ? checked( self::$options['ro_facebook_pixel'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="ro_facebook_pixel" name="ro_marketing_options[ro_facebook_pixel]" %s />', $checked_value );
	}

	public static function ro_facebook_pixel_id_callback()
	{
		printf(
			'<input type="text" id="ro_facebook_pixel_id" placeholder="xxxxxxxxx" name="ro_marketing_options[ro_facebook_pixel_id]" value="%s" />',
			isset( self::$options['ro_facebook_pixel_id'] ) ? esc_attr( self::$options['ro_facebook_pixel_id']) : ''
		);
	}	
}