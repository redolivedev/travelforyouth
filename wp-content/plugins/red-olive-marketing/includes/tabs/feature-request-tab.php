<?php 

namespace RoMarketing;

class FeatureRequestTab{
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
		 * FEATURE REQUEST
		 */
		add_settings_section(
			'ro_feature_request_section',
			'Request a Feature',
			array( __CLASS__, 'ro_feature_request_section_callback'),
			'ro-marketing-feature-request'
		);
	}

	private static function add_fields(){

	}

	/*
	 * FEATURE REQUEST
	 */
	public static function ro_feature_request_section_callback()
	{
		echo '<hr />Send us an email with your feature request: <a href="mailto:features@redolive.com?subject=RO%20Marketing%20Feature%20Request">features@redolive.com</a>
			<br/>OR submit a request via our forum <a target="_blank" href="' . RO_PLUGIN_SITE_URL . 'support/type/feature-requests/">HERE</a>';
	}
}