<?php

namespace RoMarketing;

class PPCTab{
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
		 * GOOGLE ADWORDS REMARKETING
		 */
		add_settings_section(
			'ro_remarketing_section',
			'Google AdWords Remarketing',
			array( __CLASS__, 'ro_remarketing_section_callback' ),
			'ro-marketing-ppc'
		);

		/*
		 * BING ADS
		 */
		add_settings_section(
			'ro_bing_section',
			'Bing Ads',
			array( __CLASS__, 'ro_bing_section_callback' ),
			'ro-marketing-ppc'
		);

		/*
		 * LINKEDIN INSIGHT TAG
		 */
		add_settings_section(
			'ro_linkedin_section',
			'LinkedIn Insight Tag',
			array( __CLASS__, 'ro_linkedin_section_callback' ),
			'ro-marketing-ppc'
		);
	}

	private static function add_fields(){
		/*
		 * GOOGLE ADWORDS REMARKETING
		 */
		add_settings_field(
			'standard_remarketing',
			'Enable AdWords Remarketing <br />
			<span style="font-weight:normal;color:gray;">
				(Required for Dynamic Remarketing)
			</span>',
			array(__CLASS__, 'ro_standard_remarketing_callback'),
			'ro-marketing-ppc',
			'ro_remarketing_section'
		);

		add_settings_field(
			'dynamic_remarketing_conversion_id',
			'AdWords Remarketing Tag Conversion ID',
			array( __CLASS__, 'ro_dynamic_remarketing_conversion_id_callback' ),
			'ro-marketing-ppc',
			'ro_remarketing_section'
		);

		/*
		 * BING ADS
		 */
		add_settings_field(
			'bing_ads',
			'Enable Bing Ads Tracking',
			array( __CLASS__, 'ro_bing_ads_callback' ),
			'ro-marketing-ppc',
			'ro_bing_section'
		);

		add_settings_field(
			'bing_ads_account_id',
			'BING UET Tag ID',
			array( __CLASS__, 'ro_bing_ads_account_id_callback' ),
			'ro-marketing-ppc',
			'ro_bing_section'
		);

		/*
		 * LINKEDIN INSIGHT TAG
		 */
		add_settings_field(
			'linkedin_insight',
			'Enable LinkedIn Insight Tag',
			array( __CLASS__, 'linkedin_insight_callback' ),
			'ro-marketing-ppc',
			'ro_linkedin_section'
		);

		add_settings_field(
			'linkedin_insight_partner_id',
			'LinkedIn Partner ID',
			array( __CLASS__, 'linkedin_insight_partner_id_callback' ),
			'ro-marketing-ppc',
			'ro_linkedin_section'
		);
	}

	/*
	 * GOOGLE ADWORDS REMARKETING
	 */
	public static function ro_remarketing_section_callback()
	{
		echo '<hr />Add AdWords Remarketing tag to every page';
	}

	public static function ro_standard_remarketing_callback()
	{
		$checked_value = isset( self::$options['standard_remarketing'] ) ? checked( self::$options['standard_remarketing'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="standard_remarketing" name="ro_marketing_options[standard_remarketing]" %s />', $checked_value );
	}

	public static function ro_dynamic_remarketing_conversion_id_callback()
	{
		printf(
			'<input type="text" id="dynamic_remarketing_conversion_id" placeholder="1234567890" name="ro_marketing_options[dynamic_remarketing_conversion_id]" value="%s" />',
			isset( self::$options['dynamic_remarketing_conversion_id'] ) ? esc_attr( self::$options['dynamic_remarketing_conversion_id']) : ''
		);
	}

	/*
	 * BING ADS
	 */
	public static function ro_bing_section_callback()
    {
    	echo '<hr />Add Bing Ads Tracking to every page';
    }

	public static function ro_bing_ads_callback()
	{
		$checked_value = isset( self::$options['bing_ads'] ) ? checked( self::$options['bing_ads'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="bing_ads" name="ro_marketing_options[bing_ads]" %s />', $checked_value );
	}

	public static function ro_bing_ads_account_id_callback()
	{
		printf(
			'<input type="text" id="bing_ads_account_id" placeholder="xxxxxxxx" name="ro_marketing_options[bing_ads_account_id]" value="%s" />',
			isset( self::$options['bing_ads_account_id'] ) ? esc_attr( self::$options['bing_ads_account_id']) : ''
		);
	}

	/*
	 * LINKEDIN INSIGHT TAG
	 */
	public static function ro_linkedin_section_callback()
	{
		echo '<hr />Add LinkedIn Insight tag to every page';
	}

	public static function linkedin_insight_callback()
	{
		$checked_value = isset( self::$options['linkedin_insight'] ) ? checked( self::$options['linkedin_insight'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="linkedin_insight" name="ro_marketing_options[linkedin_insight]" %s />', $checked_value );
	}

	public static function linkedin_insight_partner_id_callback()
	{
		printf(
			'<input type="text" id="linkedin_insight_partner_id" placeholder="xxxxx" name="ro_marketing_options[linkedin_insight_partner_id]" value="%s" />',
			isset( self::$options['linkedin_insight_partner_id'] ) ? esc_attr( self::$options['linkedin_insight_partner_id']) : ''
		);
	}
	
}