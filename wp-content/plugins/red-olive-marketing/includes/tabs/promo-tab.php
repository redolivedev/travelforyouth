<?php

namespace RoMarketing;

class PromoTab{
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
		 * FLOATING CTA
		 */
		add_settings_section(
			'ro_floating_cta_section',
			'Floating CTA/Contact Form',
			array( __CLASS__, 'ro_floating_cta_section_callback'),
			'ro-marketing-promo'
		);

		/*
		 * SITE WIDE BANNER
		 */
		add_settings_section(
			'ro_site_wide_banner_section',
			'Site Wide Banner',
			array( __CLASS__, 'ro_site_wide_banner_section_callback'),
			'ro-marketing-promo'
		);

		/*
		 * POP UP ADS
		 */
		add_settings_section(
			'ro_pop_up_ads_section',
			'Pop Up Boxes',
			array( __CLASS__, 'ro_pop_up_ads_section_callback'),
			'ro-marketing-promo'
		);
	}

	private static function add_fields(){

		if( ! RO_MARKETING_PRO_ACTIVE ){
			/*
			 * FLOATING CTA
			 */
			add_settings_field(
				'floating_cta_settings',
				'Options',
				array( __CLASS__, 'ro_floating_cta_settings_callback'),
				'ro-marketing-promo',
				'ro_floating_cta_section'
			);

			/*
			 * SITE WIDE BANNER
			 */
			add_settings_field(
				'site_wide_banner_settings',
				'Options',
				array( __CLASS__, 'ro_site_wide_banner_settings_callback'),
				'ro-marketing-promo',
				'ro_site_wide_banner_section'
			);

			/*
			 * POP UP ADS
			 */
			add_settings_field(
				'pop_up_ads_settings',
				'Options',
				array( __CLASS__, 'ro_pop_up_ads_settings_callback'),
				'ro-marketing-promo',
				'ro_pop_up_ads_section'
			);
		}
	}

	/*
	 * FLOATING CTA
	 */
	public static function ro_floating_cta_section_callback()
	{
		echo '<hr />Add a fixed position CTA box, contact form, or any other shortcode site-wide';
	}

	public static function ro_floating_cta_settings_callback()
	{
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}

	/*
	 * SITE WIDE BANNER
	 */
	public static function ro_site_wide_banner_section_callback()
	{
		echo '<hr />Add a header promotional/announcement banner site wide';
	}

	public static function ro_site_wide_banner_settings_callback()
	{
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}

	/*
	 * POP UP ADS
	 */
	public static function ro_pop_up_ads_section_callback()
	{
		echo '<hr />Add a pop-up message to tell your visitors about deals and events';
	}

	public static function ro_pop_up_ads_settings_callback()
	{
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}
}