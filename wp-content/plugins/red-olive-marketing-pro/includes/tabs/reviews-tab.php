<?php

namespace RoMarketingPro;

class ReviewsTab{
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
		 * REVIEWS PAGE
		 */
		add_settings_field(
			'social_media_reviews',
			'Enable Reviews Page',
			array( __CLASS__, 'ro_social_media_reviews_callback' ),
			'ro-marketing-reviews',
			'ro_social_media_reviews_section'
		);

		add_settings_field(
			'social_media_settings',
			'Options',
			array( __CLASS__, 'ro_social_media_settings_callback'),
			'ro-marketing-reviews',
			'ro_social_media_reviews_section'
		);

		add_settings_field(
			'social_media_data',
			'',
			array( __CLASS__, 'ro_social_media_data_callback'),
			'ro-marketing-reviews',
			'ro_social_media_reviews_section'
		);
	}

	/*
	 * REVIEWS PAGE
	 */
	public static function ro_social_media_reviews_callback()
	{
		$checked_value = isset( self::$options['social_media_reviews'] ) ? checked( self::$options['social_media_reviews'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="social_media_reviews" name="ro_marketing_options[social_media_reviews]" %s />', $checked_value );
	}

	public static function ro_social_media_settings_callback()
	{
		if( function_exists('acf_add_options_page') ){
			printf(
				'<p>
					<a target="_blank" href="%s/wp-admin/admin.php?page=review-collection-page-settings">
						Review Collection Settings Page
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


	public static function ro_social_media_data_callback()
	{
		printf(
			'<input type="hidden" id="social_media_data" name="ro_marketing_options[social_media_data]"
					 />'
		);
	}
}