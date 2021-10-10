<?php

namespace RoMarketing;

class QATab{
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
		 * TRACK DUCK
		 */
		add_settings_section(
			'ro_trackduck_section',
			'Track Duck',
			array( __CLASS__, 'ro_trackduck_section_callback' ),
			'ro-marketing-qa'
		);
	}

	private static function add_fields(){
		/*
		 * TRACK DUCK
		 */
		add_settings_field(
			'add_track_duck',
			'Add Track Duck',
			array( __CLASS__, 'ro_add_track_duck_callback' ),
			'ro-marketing-qa',
			'ro_trackduck_section'
		);

		add_settings_field(
			'track_duck_id',
			'Track Duck ID',
			array( __CLASS__, 'ro_track_duck_id_callback' ),
			'ro-marketing-qa',
			'ro_trackduck_section'
		);
	}

	/*
	 * TRACK DUCK
	 */
	public static function ro_trackduck_section_callback()
	{
		echo '<hr />Configure Track Duck settings';
	}

	public static function ro_add_track_duck_callback()
	{
		$checked_value = isset( self::$options['add_track_duck'] ) ? checked( self::$options['add_track_duck'], true, false) : false;
		printf( '<input type="checkbox" value="1" id="add_track_duck" name="ro_marketing_options[add_track_duck]" %s />', $checked_value );
	}

	public static function ro_track_duck_id_callback()
	{
		printf(
			'<input type="text" id="track_duck_id" placeholder="xxxxxxxxxxxxxxxxx" name="ro_marketing_options[track_duck_id]" value="%s" />',
			isset( self::$options['track_duck_id'] ) ? esc_attr( self::$options['track_duck_id']) : ''
		);
		?>
		<br /><img style="width:100%;max-width:500px;" src="<?php echo RO_MARKETING_URL . 'assets/img/track-duck-id.png'; ?>" />
		<?php
	}
}