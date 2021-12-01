<?php

namespace RoWooCommerce;

class GeneralTab{

	protected static $options;

	public static function init(){
		self::set_singleton();
		self::add_sections();
		self::add_fields();
	}

	private static function set_singleton(){
		$options_singleton = RoWooCommerceOptions::get_instance();
		self::$options = $options_singleton->get_options();
	}

	private static function add_sections(){
		/**
		 * GOOGLE ANALYTICS ECOMMERCE
		 */
		add_settings_section(
            'google_analytics_ecommerce', // ID
            'Google Analytics Ecommerce Tracking', // Title
            array( __CLASS__, 'put_an_hr' ), // Callback
            'ro-wc-general' // Page
        );

		/**
		 * LIFETIME VALUE
		 */
		add_settings_section(
            'ro_lifetime_value',
            'Customer Lifetime Value',
            array( __CLASS__, 'put_an_hr' ),
            'ro-wc-general'
        );

        /**
		 * LICENSE
		 */
		add_settings_section(
			'wc_license',
			'Red Olive WooCommerce License Key',
			array( __CLASS__, 'put_an_hr' ),
			'ro-wc-general'
		);

        /**
		 * SUPPORT
		 */
		add_settings_section(
			'ro_support_section',
			'Support',
			array( __CLASS__, 'put_an_hr' ),
			'ro-wc-general'
		);
	}

	private static function add_fields(){

		/**
		 * GOOGLE ANALYTICS ECOMMERCE
		 */
		add_settings_field(
            'google_analytics', // ID
            'Enable Google Analytics Ecommerce Tracking<br/>(requires GA to be enabled via RO Marketing "General" tab)', // Title
            array( __CLASS__, 'google_analytics_callback' ), // Callback
            'ro-wc-general', // Page
            'google_analytics_ecommerce' // Section
        );

		/**
		 * LIFETIME VALUE
		 */ 
		add_settings_field(
            'lifetime_value',
            'Enable Lifetime Value<br /><a target="_blank" href="' . admin_url( '/admin.php?page=ro-customer-lifetime-value' ) . '">View Report</a>',
            array( __CLASS__, 'lifetime_value_callback' ),
            'ro-wc-general',
            'ro_lifetime_value'
        );

        /**
		 * LICENSE KEY
		 */
		add_settings_field(
            'wc_license_key',
            'Enter your license key',
            array( __CLASS__, 'wc_license_key_callback' ),
            'ro-wc-general',
            'wc_license'
        );

        add_settings_field(
            'wc_license_key_button',
            '',
            array( __CLASS__, 'wc_license_key_button_callback' ),
            'ro-wc-general',
            'wc_license'
        );

		/**
		 * SUPPORT
		 */
        add_settings_field(
        	'ro_plugin_support',
        	'Red Olive WooCommerce',
        	array( __CLASS__, 'ro_plugin_support_callback' ),
        	'ro-wc-general',
        	'ro_support_section'
        );
	}

	/**
	 * GENERAL FUNCTIONS
	 */
	public static function put_an_hr(){
        print '<hr />';
    }

	/**
	 * GOOGLE ANALYTICS ECOMMERCE CALLBACKS
	 */
    public static function google_analytics_callback(){
        printf(
            '<input type="checkbox" id="google_analytics" name="ro_wc_options[google_analytics]" %s />',
            checked( self::$options['google_analytics'], true, false )
        );
    }

    /**
	 * LIFETIME VALUE CALLBACKS
	 */ 
    public static function lifetime_value_callback(){
        printf(
            '<input type="checkbox" id="lifetime_value" name="ro_wc_options[lifetime_value]" %s />',
            checked( self::$options['lifetime_value'], true, false )
        );
    }

    /**
	 * LICENSE KEY CALLBACKS
	 */
	public static function wc_license_key_callback(){
		printf(
            '<input type="text" size="35" id="wc_license_key" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxx" name="ro_wc_options[wc_license_key]" value="%s" />',
            isset( self::$options['wc_license_key'] ) ? esc_attr( self::$options['wc_license_key']) : ''
        );
	}

	public static function wc_license_key_button_callback(){
		$license = isset( self::$options['wc_license_key'] ) ? self::$options['wc_license_key'] : '';
		$status  = get_option( 'red_olive_woocommerce_license_status' );
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
										<?php wp_nonce_field( 'red_olive_woocommerce_nonce', 'red_olive_woocommerce_nonce' ); ?>
										<input type="submit" class="button-secondary" name="red_olive_woocommerce_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
									<?php else: ?>
										<span style="color:red;font-size:20px;">
											<span style="color:red;font-size:30px;"class="dashicons dashicons-no"></span>
											<?php _e('not activated'); ?>
										</span>
										<?php wp_nonce_field( 'red_olive_woocommerce_nonce', 'red_olive_woocommerce_nonce' ); ?>
										<input type="submit" class="button-secondary js-ro-wc-activate" name="red_olive_woocommerce_license_activate" value="<?php _e('Activate License'); ?>"/>
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
                    <img style="max-height: 30px;" src="https://assets.redolive.io/img/redolive-logo-woocommerce.png">
                </p>
                <p style="display:table-cell; vertical-align: middle;">Thank you for purchasing Red Olive WooCommerce. For support and information click <a target="_blank" href="https://www.redolive.io/contact/">HERE</a></p>
            </div>
		';
	}
}
