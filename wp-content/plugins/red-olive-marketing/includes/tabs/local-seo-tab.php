<?php 

namespace RoMarketing;

class LocalSeoTab{
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
		 * KML Sitemap
		 */
		add_settings_section(
			'ro_kml_sitemap_section',
			'KML Sitemap',
			array( __CLASS__, 'ro_kml_sitemap_section_callback'),
			'ro-marketing-local-seo'
		);

		/*
		 * NAP Builder
		 */
		add_settings_section(
			'ro_nap_builder_section',
			'NAP Builder',
			array( __CLASS__, 'ro_nap_builder_section_callback'),
			'ro-marketing-local-seo'
		);
	}

	private static function add_fields(){

		if( ! RO_MARKETING_PRO_ACTIVE ){
			/*
			 * KML Sitemap
			 */
			add_settings_field(
				'kml_sitemap_settings',
				'Options',
				array( __CLASS__, 'ro_kml_sitemap_settings_callback'),
				'ro-marketing-local-seo',
				'ro_kml_sitemap_section'
			);

			/*
			 * NAP Builder
			 */
			add_settings_field(
				'nap_builder_settings',
				'Options',
				array( __CLASS__, 'ro_nap_builder_settings_callback'),
				'ro-marketing-local-seo',
				'ro_nap_builder_section'
			);
		}
	}

	/*
	 * KML Sitemap
	 */
	public static function ro_kml_sitemap_section_callback()
	{
		echo '<hr />Add local business locations for Locations KML file';
	}

	public static function ro_kml_sitemap_settings_callback()
	{
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}

	/*
	 * NAP Builder
	 */
	public static function ro_nap_builder_section_callback()
	{
		echo '<hr />Add local schema markup to the name, address, and phone number for your business, and insert anywhere on your site using a shortcode';
	}

	public static function ro_nap_builder_settings_callback()
	{
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}
}