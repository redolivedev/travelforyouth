<?php

namespace RoMarketingPro;

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
	}

	private static function add_fields(){
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

	/*
	 * REDIRECTS
	 */
	public static function ro_redirects_settings_callback()
	{
		if( function_exists('acf_add_options_page') ){
			printf(
				'<p>
					<a target="_blank" href="%s/wp-admin/admin.php?page=ro-redirects">
						Redirects Manager Settings Page
					</a>
				</p>', 
				get_site_url()
			);
		}else{
			printf(
				'<p><strong><em>This plugin requires the Advanced Custom Fields Pro plugin.</em></strong></p>'
			);
		}
	}
}