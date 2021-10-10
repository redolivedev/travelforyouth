<?php

namespace RoMarketing;

final class RoMarketingOptions
{
	protected $options = array();

    /**
     * Call this method to get singleton
     *
     * @return UserFactory
     */
    public static function get_instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new self();
        }
        return $inst;
    }

    public function get_options(){
    	if( ! $this->options ) $this->options = get_option( 'ro_marketing_options' );
    	return get_option( 'ro_marketing_options' );
    }

    /**
     * Private constructor so nobody else can instance it
     */
    private function __construct(){}

    /**
     * Private clone so nobody else can instance it
     */
    private function __clone(){}


    /**
     * Throw exception on wakeup attempt
     */
    public function __wakeup(){
        throw new Exception('Cannot unserialize singleton');
    }

    /**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public static function sanitize( $input ){
		$new_input = array();

		// sanitize the array
		foreach ($input as $key => $value) {
		    $new_input[$key] = sanitize_text_field( $value );
		}

		// figure out which checkboxes are checked
		$checkboxArray = array( 'call_tracking', 'google_analytics', 'google_experiment_tag', 'standard_remarketing', 'dynamic_remarketing', 'bing_ads', 'enable_media_file_renaming', 'stop_comment_spam', 'clear_expired_sessions', 'force_https', 'social_media_reviews', 'add_crazy_egg', 'enable_mailchimp_widget', 'linkedin_insight', 'ro_facebook_pixel' );
		foreach( $checkboxArray as $checkboxField ) {
		    $new_input[$checkboxField] = isset( $input[$checkboxField] ) && $input[$checkboxField] ? 1 : 0;
		}

		$new_input['header_scripts'] = ! empty( $input['header_scripts'] ) ? $input['header_scripts'] : null;
		$new_input['footer_scripts'] = ! empty( $input['footer_scripts'] ) ? $input['footer_scripts'] : null;

		return $new_input;
	}
}
