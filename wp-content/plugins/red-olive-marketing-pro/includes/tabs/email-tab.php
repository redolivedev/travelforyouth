<?php

namespace RoMarketingPro;

class EmailTab{
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
		 * MAILCHIMP WIDGET
		 */
		add_settings_field(
			'enable_mailchimp_widget',
			'Enable MailChimp Widget',
			array( __CLASS__, 'ro_enable_mailchimp_widget_callback' ),
			'ro-marketing-email',
			'ro_mailchimp_widget_section'
		);

		add_settings_field(
            'mailchimp_widget_api_key',
            'Mailchimp API Key<br />(You can get that here: <a href="https://admin.mailchimp.com/account/api/" target="_blank">https://admin.mailchimp.com/account/api/</a>)',
            array( __CLASS__, 'ro_mailchimp_widget_api_key_callback' ),
            'ro-marketing-email',
            'ro_mailchimp_widget_section'
        );

        add_settings_field(
            'mailchimp_widget_list_id',
            'Mailchimp List ID',
            array( __CLASS__, 'ro_mailchimp_widget_list_id_callback' ),
            'ro-marketing-email',
            'ro_mailchimp_widget_section'
        );
	}

	/*
	 * MAILCHIMP WIDGET
	 */
	public static function ro_enable_mailchimp_widget_callback()
	{
		$checked_value = isset( self::$options['enable_mailchimp_widget'] ) ? checked( self::$options['enable_mailchimp_widget'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="enable_mailchimp_widget" name="ro_marketing_options[enable_mailchimp_widget]" %s />', $checked_value );
	}

	public static function ro_mailchimp_widget_api_key_callback()
	{
		printf(
            '<input type="text" id="mailchimp_widget_api_key" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-xxx" name="ro_marketing_options[mailchimp_widget_api_key]" value="%s" />',
            isset( self::$options['mailchimp_widget_api_key'] ) ? esc_attr( self::$options['mailchimp_widget_api_key']) : ''
        );
	}

	public static function ro_mailchimp_widget_list_id_callback()
	{
		printf(
            '<input type="text" id="mailchimp_widget_list_id" placeholder="xxxxxxx" name="ro_marketing_options[mailchimp_widget_list_id]" value="%s" />',
            isset( self::$options['mailchimp_widget_list_id'] ) ? esc_attr( self::$options['mailchimp_widget_list_id']) : ''
        );
	}

}