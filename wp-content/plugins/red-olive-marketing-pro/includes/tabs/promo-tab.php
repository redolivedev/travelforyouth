<?php

namespace RoMarketingPro;

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
	}

	private static function add_fields(){
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

	/*
	 * FLOATING CTA
	 */
	public static function ro_floating_cta_settings_callback()
	{
		if( function_exists('acf_add_options_page') ){
			printf(
				'<p><a target="_blank" href="%s/wp-admin/edit.php?post_type=floating-cta">Floating CTA Settings</a></p>', get_site_url()
			);
		}else{
			printf(
				'<p><strong><em>This plugin requires the Advanced Custom Fields Pro plugin.</em></strong></p>'
			);
		}
	}

	/*
	 * SITE WIDE BANNER
	 */
	public static function ro_site_wide_banner_settings_callback()
	{
		if( function_exists('acf_add_options_page') ){
			printf(
				'<p><a target="_blank" href="%s/wp-admin/admin.php?page=ro-site-wide-banner">Site Wide Banner Settings</a></p>', get_site_url()
			);
		}else{
			printf(
				'<p><strong><em>This plugin requires the Advanced Custom Fields Pro plugin.</em></strong></p>'
			);
		}
	}

	/*
	 * POP UP ADS
	 */
	public static function ro_pop_up_ads_settings_callback()
	{
		printf(
			'<p><a target="_blank" href="%s/wp-admin/edit.php?post_type=pop-up">Pop Up Boxes Settings</a></p>', get_site_url()
		);
	}
}