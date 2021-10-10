<?php

namespace RoMarketing;

class GeneralTab{

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
		/**
		 * GOOGLE ANALYTICS
		 */
		add_settings_section(
			'ro_google_section',
			'Google Analytics',
			array( __CLASS__, 'ro_google_section_callback' ),
			'ro-marketing-general'
		);

		add_settings_section(
			'ro_google_section_404_dimension',
			'',
			'',
			'ro-marketing-general'
		);

		/**
		 * GOOGLE TAG MANAGER
		 */
		add_settings_section(
			'ro_google_tag_manager_section',
			'Google Tag Manager',
			array( __CLASS__, 'ro_google_tag_manager_section_callback' ),
			'ro-marketing-general'
		);

		if( RO_MARKETING_PRO_ACTIVE ){
			/**
			 * LICENSE
			 */
			add_settings_section(
				'marketing_pro_license',
				'Red Olive Marketing Pro License Key',
				array( __CLASS__, 'put_an_hr' ),
				'ro-marketing-general'
			);
		}

		/**
		 * SUPPORT
		 */
		add_settings_section(
			'ro_support_section',
			'Support',
			array( __CLASS__, 'ro_support_section_callback' ),
			'ro-marketing-general'
		);
	}

	private static function add_fields(){

		/**
		 * GOOGLE ANALYTICS
		 */

		add_settings_field(
			'google_analytics',
			'Enable Google Analytics',
			array( __CLASS__, 'google_analytics_callback' ),
			'ro-marketing-general',
			'ro_google_section'
		);

		add_settings_field(
			'google_analytics_account_id',
			'Google Analytics Account ID',
			array( __CLASS__, 'google_analytics_account_id_callback' ),
			'ro-marketing-general',
			'ro_google_section'
		);

		if( ! RO_MARKETING_PRO_ACTIVE ){
			add_settings_field(
				'google_analytics_404_dimension_number',
				'404 Dimension Number',
				array( __CLASS__, 'google_analytics_404_dimension_number_callback' ),
				'ro-marketing-general',
				'ro_google_section_404_dimension'
			);
		}

		/**
		 * GOOGLE TAG MANAGER
		 */
		add_settings_field(
			'google_tag_manager',
			'Enable Google Tag Manager',
			array( __CLASS__, 'google_tag_manager_callback' ),
			'ro-marketing-general',
			'ro_google_tag_manager_section'
		);

		
		add_settings_field(
			'google_tag_manager_account_id',
			'Google Tag Manager ID',
			array( __CLASS__, 'google_tag_manager_id_callback' ),
			'ro-marketing-general',
			'ro_google_tag_manager_section'
		);

		/**
		 * SUPPORT
		 */
		if( ! RO_MARKETING_PRO_ACTIVE ){
			add_settings_field(
				'ro_plugin_support',
				'Red Olive Marketing',
				array( __CLASS__, 'ro_plugin_support_callback' ),
				'ro-marketing-general',
				'ro_support_section'
			);
		}
	}
	
	/**
	 * GENERAL FUNCTIONS
	 */
	public static function put_an_hr(){
        print '<hr />';
    }

	/**
	 * GOOGLE TAG MANAGER CALLBACKS
	 */
	public static function ro_google_tag_manager_section_callback()
	{
		echo '<hr />Add Google Tag Manager tags to every page';
	}

	public static function google_tag_manager_callback()
	{
		$checked_value = isset( self::$options['google_tag_manager'] ) ? checked( self::$options['google_tag_manager'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="google_tag_manager" name="ro_marketing_options[google_tag_manager]" %s />', $checked_value );
	}

	public static function google_tag_manager_id_callback()
	{
		printf(
			'<input type="text" id="google_tag_manager_account_id" placeholder="GTM-XXXX" name="ro_marketing_options[google_tag_manager_account_id]" value="%s" />',
			isset( self::$options['google_tag_manager_account_id'] ) ? esc_attr( self::$options['google_tag_manager_account_id']) : ''
		);
	}

	/**
	 * GOOGLE ANALYTICS CALLBACKS
	 */
	public static function ro_google_section_callback()
	{
		echo '<hr />Add Universal Google Analytics tags to every page';
	}

	public static function google_analytics_callback()
	{
		$checked_value = isset( self::$options['google_analytics'] ) ? checked( self::$options['google_analytics'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="google_analytics" name="ro_marketing_options[google_analytics]" %s />', $checked_value );
	}

	public static function google_analytics_account_id_callback()
	{
		printf(
			'<input type="text" id="google_analytics_account_id" placeholder="UA-XXXXX-X" name="ro_marketing_options[google_analytics_account_id]" value="%s" />',
			isset( self::$options['google_analytics_account_id'] ) ? esc_attr( self::$options['google_analytics_account_id']) : ''
		);
	}

	public static function google_analytics_404_dimension_number_callback()
	{
		echo 'Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}

	/**
	 * SUPPORT CALLBACKS
	 */
	public static function ro_support_section_callback()
	{
		echo '<hr />';
	}

	public static function ro_plugin_support_callback()
	{
		echo '
			<div class="logo-container" style="display:table;">
                <p style="display:table-cell; vertical-align: middle; padding-right: 5px;">
                    <img style="max-height: 30px;" src="https://assets.redolive.io/img/redolive-logo.png">
                </p>
                <p style="display:table-cell; vertical-align: middle;">For support and information on Red Olive Marketing Pro, click <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '/contact/">HERE</a></p>
            </div>
		';
	}
}