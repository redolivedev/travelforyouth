<?php

namespace RoMarketing;

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
		/*
		 * MAILCHIMP WIDGET
		 */
		add_settings_section(
			'ro_mailchimp_widget_section',
			'MailChimp Widget',
			array( __CLASS__, 'ro_mailchimp_widget_section_callback'),
			'ro-marketing-email'
		);
	}

	private static function add_fields(){

		if( ! RO_MARKETING_PRO_ACTIVE ){
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
		}
	}

	/*
	 * MAILCHIMP WIDGET
	 */
	public static function ro_mailchimp_widget_section_callback()
	{
		echo '<hr />
			Add users to a MailChimp mailing list.
			<br>Use the following shortcode and modify the text within double quotes ("edit this") as desired:
			<br><br><span style="background-color:white;"><strong>[ro_mailchimp_widget button-text="Subscribe" "placeholder-text="Enter Your Email Here" success-message="Thank you for Subscribing to our Email List"]</strong></span>
			<br><br>Add the shortcode on your site wherever you want the newsletter signup form to appear<br>
			<p class="js-mailchimp-creds-error" style="display:none;color:red;">Your MailChimp API key is not working. Please refresh the page. If this alert appears again, double check your credentials.</p>';
	}

	public static function ro_enable_mailchimp_widget_callback()
	{
		echo 'Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}
}