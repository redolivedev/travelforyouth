<?php

namespace RoMarketing;

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
		/*
		 * GOOGLE EXPERIMENT TAG
		 */
		add_settings_section(
			'ro_google_experiment_tag_section',
			'Google Analytics Content Experiments',
			array( __CLASS__, 'ro_google_experiment_tag_callback' ),
			'ro-marketing-cro'
		);

		add_settings_section(
			'ro_google_experiment_tag_section_target_devices',
			'',
			'',
			'ro-marketing-cro'
		);

		/*
		 * A/B TESTING
		 * This section is mostly empty so that it appears to be part of the Google Experiment Tag section
		 */
		add_settings_section(
			'ro_ab_testing_section',
			'On-Site Text Variation Creator',
			array( __CLASS__, 'ro_ab_testing_section_callback' ),
			'ro-marketing-cro'
		);

		/*
		 * GOOGLE OPTIMIZE TAG
		 */
		add_settings_section(
			'ro_google_optimize_tag_section',
			'Google Optimize',
			array( __CLASS__, 'ro_google_optimize_tag_callback' ),
			'ro-marketing-cro'
		);

		/*
		 * CRAZY EGG
		 */
		add_settings_section(
			'ro_crazy_egg_tag_section',
			'Crazy Egg',
			array( __CLASS__, 'ro_crazy_egg_tag_callback' ),
			'ro-marketing-cro'
		);
	}

	private static function add_fields(){
		/*
		 * GOOGLE EXPERIMENT TAG
		 */
		add_settings_field(
			'google_experiment_tag',
			'Enable Content Experiments Tag',
			array( __CLASS__, 'ro_enable_google_experiment_tag_callback' ),
			'ro-marketing-cro',
			'ro_google_experiment_tag_section'
		);

		add_settings_field(
			'google_experiment_tag_id',
			'Content Experiments Tag ID',
			array( __CLASS__, 'ro_google_experiment_tag_id_callback' ),
			'ro-marketing-cro',
			'ro_google_experiment_tag_section'
		);

		//Only show this section if pro version is not installed
		if( ! RO_MARKETING_PRO_ACTIVE ){
			add_settings_field(
				'google_experiment_tag_target_devices',
				'Content Experiments Target Devices',
				array( __CLASS__, 'ro_google_experiment_tag_target_devices_callback' ),
				'ro-marketing-cro',
				'ro_google_experiment_tag_section_target_devices'
			);
		}

		if( ! RO_MARKETING_PRO_ACTIVE ){
			/*
			 * AB Testing
			 */
			add_settings_field(
				'ro_ab_testing',
				'Options',
				array( __CLASS__, 'ro_ab_testing_field_callback' ),
				'ro-marketing-cro',
				'ro_ab_testing_section'
			);
		}

		/*
		 * GOOGLE OPTIMIZE TAG
		 */
		add_settings_field(
			'google_optimize_tag',
			'Enable Google Optimize Tag',
			array( __CLASS__, 'ro_enable_google_optimize_tag_callback' ),
			'ro-marketing-cro',
			'ro_google_optimize_tag_section'
		);

		add_settings_field(
			'google_optimize_tag_id',
			'Google Optimize Container ID',
			array( __CLASS__, 'ro_google_optimize_tag_id_callback' ),
			'ro-marketing-cro',
			'ro_google_optimize_tag_section'
		);

		/*
		 * CRAZY EGG
		 */
		add_settings_field(
			'add_crazy_egg',
			'Add Crazy Egg Tag',
			array( __CLASS__, 'ro_add_crazy_egg_callback' ),
			'ro-marketing-cro',
			'ro_crazy_egg_tag_section'
		);

		add_settings_field(
			'add_crazy_egg_tag_id',
			'Crazy Egg Account Number',
			array( __CLASS__, 'ro_add_crazy_egg_tag_id_callback' ),
			'ro-marketing-cro',
			'ro_crazy_egg_tag_section'
		);
	}

	/*
	 * GOOGLE EXPERIMENT TAG
	 */
	public static function ro_google_experiment_tag_callback()
	{
		echo '<hr />Add Google Analytics Content Experiments Tag to be called on the first page visited by the user';
	}

	public static function ro_enable_google_experiment_tag_callback()
	{
		$checked_value = isset( self::$options['google_experiment_tag'] ) ? checked( self::$options['google_experiment_tag'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="google_experiment_tag" name="ro_marketing_options[google_experiment_tag]" %s />', $checked_value );
	}

	public static function ro_google_experiment_tag_id_callback()
	{
		printf(
			'<input type="text" id="google_experiment_tag_id" placeholder="UA-XXXXX-X" name="ro_marketing_options[google_experiment_tag_id]" value="%s" />',
			isset( self::$options['google_experiment_tag_id'] ) ? esc_attr( self::$options['google_experiment_tag_id']) : ''
		);
	}

	public static function ro_google_experiment_tag_target_devices_callback()
	{
		self::$options['google_experiment_tag_target_devices'] = '';
		echo 'Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a>';
	}

	/*
	 * ON-SIZE TEXT VARIATION CREATOR
	 */
	public static function ro_ab_testing_section_callback()
	{
		echo '<hr />Easily create text variations on pages';
	}

	public static function ro_ab_testing_field_callback()
	{
		echo 'Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a>';
	}

	/*
	 * GOOGLE OPTIMIZE TAG
	 */
	public static function ro_google_optimize_tag_callback()
	{
		echo '<hr />Add the Google Optimize tags to every page';
	}

	public static function ro_enable_google_optimize_tag_callback()
	{
		$checked_value = isset( self::$options['google_optimize_tag'] ) ? checked( self::$options['google_optimize_tag'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="google_optimize_tag" name="ro_marketing_options[google_optimize_tag]" %s />', $checked_value );
	}

	public static function ro_google_optimize_tag_id_callback()
	{
		printf(
			'<input type="text" id="google_optimize_tag_id" placeholder="GTM-XXXXXXX" name="ro_marketing_options[google_optimize_tag_id]" value="%s" />',
			isset( self::$options['google_optimize_tag_id'] ) ? esc_attr( self::$options['google_optimize_tag_id']) : ''
		);
	}

	/*
	 * Crazy Egg
	 */
	public static function ro_crazy_egg_tag_callback()
	{
		echo '<hr />Add Crazy Egg tag to every page';
	}

	/*
	 * CRAZY EGG
	 */
	public static function ro_add_crazy_egg_callback()
	{
		$checked_value = isset( self::$options['add_crazy_egg'] ) ? checked( self::$options['add_crazy_egg'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="add_crazy_egg" name="ro_marketing_options[add_crazy_egg]" %s />', $checked_value );
	}

	public static function ro_add_crazy_egg_tag_id_callback()
	{
		printf(
			'<input type="text" id="add_crazy_egg_tag_id" placeholder="XXXXXXXX" name="ro_marketing_options[add_crazy_egg_tag_id]" value="%s" />',
			isset( self::$options['add_crazy_egg_tag_id'] ) ? esc_attr( self::$options['add_crazy_egg_tag_id']) : ''
		);
	}

}