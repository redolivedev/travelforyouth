<?php

namespace RoMarketing;

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
		/*
		 * REVIEWS PAGE
		 */
		add_settings_section(
			'ro_social_media_reviews_section',
			'Review Collection Page',
			array( __CLASS__, 'ro_social_media_reviews_section_callback'),
			'ro-marketing-reviews'
		);
	}

	private static function add_fields(){

		if( ! RO_MARKETING_PRO_ACTIVE ){
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
		}
	}

	/*
	 * REVIEWS PAGE
	 */
	public static function ro_social_media_reviews_section_callback()
	{
		echo '<hr />Build a page to share with happy customer to leave reviews';
	}

	public static function ro_social_media_reviews_callback()
	{
		echo '<hr />Available in RO Marketing Pro version: <a target="_blank" href="' . RO_MARKETING_PRO_DOWNLOAD_URL . '">RO Marketing Pro</a> ';
	}
}