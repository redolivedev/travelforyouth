<?php

namespace RoMarketingPro;

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
	}

	private static function add_fields(){
		/**
		 * GOOGLE ANALYTICS
		 */
		add_settings_field(
			'google_analytics_404_dimension_number',
			'404 Dimension Number',
			array( __CLASS__, 'google_analytics_404_dimension_number_callback' ),
			'ro-marketing-general',
			'ro_google_section_404_dimension'
		);

		add_settings_field(
			'google_analytics_404_dimension_report',
			'404 Dimension Report',
			array( __CLASS__, 'google_analytics_404_dimension_report_callback' ),
			'ro-marketing-general',
			'ro_google_section_404_dimension'
		);

		/**
		 * LICENSE KEY
		 */
		add_settings_field(
            'marketing_license_key',
            'Enter your license key',
            array( __CLASS__, 'marketing_license_key_callback' ),
            'ro-marketing-general',
            'marketing_pro_license'
        );

        add_settings_field(
            'marketing_license_key_button',
            '',
            array( __CLASS__, 'marketing_license_key_button_callback' ),
            'ro-marketing-general',
            'marketing_pro_license'
        );

		/**
		 * SUPPORT
		 */
		add_settings_field(
			'ro_plugin_support',
			'Red Olive Marketing',
			array( __CLASS__, 'ro_plugin_support_callback' ),
			'ro-marketing-general',
			'ro_support_section'
		);
	}

	/**
	 * GOOGLE ANALYTICS CALLBACKS
	 */
	public static function google_analytics_404_dimension_number_callback()
	{
		printf(
			'<input type="number" id="google_analytics_404_dimension_number" placeholder="#" name="ro_marketing_options[google_analytics_404_dimension_number]" value="%d" />',
			isset( self::$options['google_analytics_404_dimension_number'] ) ? esc_attr( self::$options['google_analytics_404_dimension_number']) : 1
		);
	}

	public static function google_analytics_404_dimension_report_callback()
	{
		printf(
			'<ol>
				<li>Go to GA > Admin > Property > Custom Definitions > Custom Dimensions > New Custom Dimension > Name it "404 Pages" > Note the Dimension # and Set it Above</li>
				<li>Click <a href="https://analytics.google.com/analytics/web/template?uid=BgF-mHb_RrKE19BjmkA7Nw" target="_blank">HERE</a> to set up the Google Analytics Custom 404 Report</li>
			</ol>'
		);
	}

	/**
	 * LICENSE KEY CALLBACKS
	 */
	public static function marketing_license_key_callback(){
		printf(
            '<input type="text" size="35" id="marketing_license_key" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxx" name="ro_marketing_options[marketing_license_key]" value="%s" />',
            isset( self::$options['marketing_license_key'] ) ? esc_attr( self::$options['marketing_license_key']) : ''
        );
	}

	public static function marketing_license_key_button_callback(){
		$license = isset( self::$options['marketing_license_key'] ) ? self::$options['marketing_license_key'] : '';
		$status  = get_option( 'red_olive_marketing_pro_license_status' );
		?>
		<div class="wrap">
			<form method="post" action="options.php">
				<table class="form-table">
					<tbody>
						<?php if( false !== $license ) { ?>
							<tr valign="top">
								<td>
									<?php if( $status !== false && $status == 'valid' ): ?>
										<span style="color:green;font-size:20px;">
											<span style="color:green;font-size:30px;"class="dashicons dashicons-yes"></span>
											<?php _e('active'); ?>
										</span>
										<?php wp_nonce_field( 'red_olive_marketing_pro_nonce', 'red_olive_marketing_pro_nonce' ); ?>
										<input type="submit" class="button-secondary" name="red_olive_marketing_pro_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
									<?php else: ?>
										<span style="color:red;font-size:20px;">
											<span style="color:red;font-size:30px;"class="dashicons dashicons-no"></span>
											<?php _e('not activated'); ?>
										</span>
										<?php wp_nonce_field( 'red_olive_marketing_pro_nonce', 'red_olive_marketing_pro_nonce' ); ?>
										<input type="submit" class="button-secondary js-ro-marketing-activate" name="red_olive_marketing_pro_license_activate" value="<?php _e('Activate License'); ?>"/>
									<?php endif; ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
		</div>
		<?php
	}

	/**
	 * SUPPORT CALLBACKS
	 */
	public static function ro_plugin_support_callback()
	{
		echo '
			<div class="logo-container" style="display:table;">
                <p style="display:table-cell; vertical-align: middle; padding-right: 5px;">
                    <img style="max-height: 30px;" src="https://assets.redolive.io/img/redolive-logo-pro.png">
                </p>
                <p style="display:table-cell; vertical-align: middle;">Thank you for purchasing Red Olive Marketing Pro. For support and information click <a target="_blank" href="https://www.redolive.io/contact/">HERE</a></p>
            </div>
		';
	}
}