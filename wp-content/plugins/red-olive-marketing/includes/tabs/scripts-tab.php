<?php

namespace RoMarketing;

class ScriptsTab{
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
		 * SCRIPTS
		 */
		add_settings_section(
			'ro_scripts_section',
			'Scripts',
			array( __CLASS__, 'ro_scripts_section_callback' ),
			'ro-marketing-scripts'
		);
	}

	private static function add_fields(){
		/*
		 * SCRIPTS
		 */
		add_settings_field(
			'header_scripts',
			'Header Scripts<br><span style="font-weight:normal">Adds scripts and tags before the &lt;/head&gt; tag</span>',
			array( __CLASS__, 'ro_header_scripts_callback' ),
			'ro-marketing-scripts',
			'ro_scripts_section'
		);

		add_settings_field(
			'footer_scripts',
			'Footer Scripts<br><span style="font-weight:normal">Adds scripts and tags before the &lt;/body&gt; tag</span>',
			array( __CLASS__, 'ro_footer_scripts_callback' ),
			'ro-marketing-scripts',
			'ro_scripts_section'
		);
	}

	/*
	 * SCRIPTS
	 */
	public static function ro_scripts_section_callback()
	{
		echo '<hr />Add header and footer scripts to every page';
	}

	public static function ro_header_scripts_callback()
	{
		?><textarea id="header_scripts" name="ro_marketing_options[header_scripts]" style="width:100%" rows="15"><?php echo ! empty( self::$options['header_scripts'] ) ? self::$options['header_scripts'] : false ?></textarea><?php
	}

	public static function ro_footer_scripts_callback()
	{
		?><textarea id="footer_scripts" name="ro_marketing_options[footer_scripts]" style="width:100%" rows="15"><?php echo ! empty( self::$options['footer_scripts'] ) ? self::$options['footer_scripts'] : false ?></textarea><?php
	}
}