<?php

namespace RoMarketingPro;

class CROTab{
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
		 * GOOGLE EXPERIMENT TAG
		 */
		add_settings_field(
			'google_experiment_tag_target_devices',
			'Content Experiments Target Devices',
			array( __CLASS__, 'ro_google_experiment_tag_target_devices_callback' ),
			'ro-marketing-cro',
			'ro_google_experiment_tag_section_target_devices'
		);
		
		//Hooks into experiment tag section set up by the free version
		add_settings_field(
			'ro_ab_testing',
			'Options',
			array( __CLASS__, 'ro_ab_testing_field_callback' ),
			'ro-marketing-cro',
			'ro_ab_testing_section'
		);
	}

	/*
	 * GOOGLE EXPERIMENT TAG
	 */
	public static function ro_google_experiment_tag_target_devices_callback()
	{
		$options = array(
			'desktop',
			'tablet',
			'mobile',
			'tablet_and_phone',
			'all'
		);
		if( ! isset( self::$options['google_experiment_tag_target_devices'] ) ) self::$options['google_experiment_tag_target_devices'] = 'all';

		foreach( $options as $option ) : ?>
			<input type="radio" id="google_experiment_tag_target_devices_<?php echo $option ?>" name="ro_marketing_options[google_experiment_tag_target_devices]" value="<?php echo $option ?>" <?php checked( isset( self::$options['google_experiment_tag_target_devices'] ) && self::$options['google_experiment_tag_target_devices'] == $option ) ?> />
			<label for="google_experiment_tag_target_devices_<?php echo $option ?>"><?php echo ucwords( str_replace( '_', ' ', $option ) ) ?></label>
		<?php
		endforeach;
	}

	/*
	 * ON-SIZE TEXT VARIATION CREATOR
	 */
	public static function ro_ab_testing_field_callback()
	{
		printf(
			'<p><a target="_blank" href="%s/wp-admin/admin.php?page=on-site-text-variation-creator">On-site Text Variation Creator</a></p>', get_site_url()
		);
	}
}