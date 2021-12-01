<?php 

namespace RoMarketingPro;

class LocalSeoTab{
	protected static $options;

	public static function init(){
		self::add_sections();
		self::set_singleton();
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

	/*
	 * KML Sitemap
	 */
	public static function ro_kml_sitemap_settings_callback()
	{
		if( function_exists('acf_add_options_page') ){
			printf(
				'<p><a target="_blank" href="%s/wp-admin/admin.php?page=ro-kml-sitemap">KML Sitemap Settings Page</a></p>', get_site_url()
			);
		}else{
			printf(
				'<p><strong><em>This plugin requires the Advanced Custom Fields Pro plugin.</em></strong></p>'
			);
		}
	}

	/*
	 * NAP Builder
	 */
	public static function ro_nap_builder_settings_callback()
	{
		if( function_exists('acf_add_options_page') ){
			printf(
				'<p><a target="_blank" href="%s/wp-admin/admin.php?page=ro-nap-builder">NAP Builder Settings Page</a></p>', get_site_url()
			);
		}else{
			printf(
				'<p><strong><em>This plugin requires the Advanced Custom Fields Pro plugin.</em></strong></p>'
			);
		}
	}
}