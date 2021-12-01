<?php

namespace RoWooCommerce;

class EmailTab{

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
		 * ADD EMAIL TO MAILCHIMP AFTER CHECKOUT SUCCESS
		 */
		add_settings_section(
            'ro_add_email_to_mailchimp', // ID
            'Add Email to MailChimp After Checkout Success', // Title
            array( __CLASS__, 'ro_add_email_to_mailchimp_callback' ), // Callback
            'ro-wc-email' // Page
        );

        /**
         * EMAIL TEMPLATE PREVIEW
         */
        add_settings_section(
            'ro_wc_email_preview',
            'Email Templates',
            array( __CLASS__, 'ro_wc_email_preview_callback' ),
            'ro-wc-email'
        );

        /**
         * ABANDONED CART
         */
        add_settings_section(
            'ro_wc_abandoned_cart',
            'Send Abandoned Cart Emails',
            array( __CLASS__, 'put_an_hr' ),
            'ro-wc-email'
        );
	}

	private static function add_fields(){

		/**
		 * ADD EMAIL TO MAILCHIMP AFTER CHECKOUT SUCCESS
		 */
		add_settings_field(
            'add_email_to_mailchimp_api_key', // ID
            'Mailchimp API Key<br />(You can get that here: <a href="https://admin.mailchimp.com/account/api/" target="_blank">https://admin.mailchimp.com/account/api/</a>)', // Title
            array( __CLASS__, 'add_email_to_mailchimp_api_key_callback' ), // Callback
            'ro-wc-email', // Page
            'ro_add_email_to_mailchimp' // Section
        );

        add_settings_field(
            'add_email_to_mailchimp_list_id',
            'Mailchimp List ID',
            array( __CLASS__, 'add_email_to_mailchimp_list_id_callback' ),
            'ro-wc-email',
            'ro_add_email_to_mailchimp'
        );

        /**
         * EMAIL TEMPLATE PREVIEW
         */
        add_settings_field(
            'email_preview_link',
            'Preview Email Templates',
            array( __CLASS__, 'email_preview_link_callback' ),
            'ro-wc-email',
            'ro_wc_email_preview'
        );

        /**
         * ABANDONED CART
         */
        add_settings_field(
            'abandoned_cart_enabled',
            'Enable Abandoned Cart Functionality',
            array( __CLASS__, 'abandoned_cart_enabled_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_notification_type',
            'Email Notification Type',
            array( __CLASS__, 'abandoned_cart_notification_type_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_test_email',
            'Test Email<br />
            (Enable Abandoned Cart Functionality must be checked)',
            array( __CLASS__, 'abandoned_cart_test_email_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_from_name',
            'From Name<br />
            (Appears as the name on the email sent to the customer)',
            array( __CLASS__, 'abandoned_cart_from_name_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_from_email',
            'From Email<br />
            (The no-reply email address which sends the abandonment email)',
            array( __CLASS__, 'abandoned_cart_from_email_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_return_url',
            'Cart URL<br />
            (The URL the user will land on when clicking the email link)',
            array( __CLASS__, 'abandoned_cart_return_url_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_discount',
            'Cart Discount Message<br />
            (The amount off the cart)',
            array( __CLASS__, 'abandoned_cart_discount_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_discount_code',
            'Cart Discount Code<br />
            (The code for the discount to be applied to the cart)',
            array( __CLASS__, 'abandoned_cart_discount_code_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_3_day_enabled',
            'Enable 3 Day Email<br />
            (Send an email three days after cart abandoned)',
            array( __CLASS__, 'abandoned_cart_3_day_enabled_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_3_day_cart_discount',
            '3 Day Cart Discount Message',
            array( __CLASS__, 'abandoned_cart_3_day_discount_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_3_day_discount_code',
            '3 Day Cart Discount Code<br />
            (The code for the discount to be applied to the cart)',
            array( __CLASS__, 'abandoned_cart_3_day_discount_code_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_7_day_enabled',
            'Enable 7 Day Email<br />
            (Send an email seven days after cart abandoned)',
            array( __CLASS__, 'abandoned_cart_7_day_enabled_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_7_day_cart_discount',
            '7 Day Cart Discount Message',
            array( __CLASS__, 'abandoned_cart_7_day_discount_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_7_day_discount_code',
            '7 Day Cart Discount Code<br />
            (The code for the discount to be applied to the cart)',
            array( __CLASS__, 'abandoned_cart_7_day_discount_code_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_email_subject',
            'Email Subject',
            array( __CLASS__, 'abandoned_cart_email_subject_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_email_message',
            'Email Message',
            array( __CLASS__, 'abandoned_cart_email_message_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_logo_url',
            'Logo URL<br />
            (Will appear at the bottom of the email next to the site name)',
            array( __CLASS__, 'abandoned_cart_logo_url_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_logo_width',
            'Logo Width<br />
            <em>(This must be provided if including a logo for the email)</em><br />
            <span style="text-decoration:underline;">Recommended width: 200</span>',
            array( __CLASS__, 'abandoned_cart_logo_width_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_logo_height',
            'Logo Height<br />
            <em>(This must be provided if including a logo for the email)</em><br />
            <span style="text-decoration:underline;">Recommended height: 200</span>',
            array( __CLASS__, 'abandoned_cart_logo_height_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        add_settings_field(
            'abandoned_cart_button_text',
            'Button Text',
            array( __CLASS__, 'abandoned_cart_button_text_callback' ),
            'ro-wc-email',
            'ro_wc_abandoned_cart'
        );

        /** 
         * NOTE: MailChimp Not Completely Implemented Yet
         */
        // add_settings_field(
        //     'abandoned_cart_mailchimp_section',
        //     '<span style="text-decoration:underline;">Mailchimp Notification Settings</span>',
        //     array( __CLASS__, 'put_an_hr' ),
        //     'ro-wc-email',
        //     'ro_wc_abandoned_cart'
        // );

        // add_settings_field(
        //     'abandoned_cart_mailchimp_key',
        //     'Mailchimp API Key<br />(You can get that here: <a href="https://admin.mailchimp.com/account/api/" target="_blank">https://admin.mailchimp.com/account/api/</a>)',
        //     array( __CLASS__, 'abandoned_cart_mailchimp_key_callback' ),
        //     'ro-wc-email',
        //     'ro_wc_abandoned_cart'
        // );

        // add_settings_field(
        //     'abandoned_cart_mailchimp_list',
        //     'Mailchimp List ID',
        //     array( __CLASS__, 'abandoned_cart_mailchimp_list_callback' ),
        //     'ro-wc-email',
        //     'ro_wc_abandoned_cart'
        // );

        // add_settings_field(
        //     'abandoned_cart_mailchimp_config',
        //     'Mailchimp Configuration<br />
        //     <em>(This step is required for emails to be added to your MailChimp list)</em>',
        //     array( __CLASS__, 'abandoned_cart_mailchimp_config_callback' ),
        //     'ro-wc-email',
        //     'ro_wc_abandoned_cart'
        // );
	}

	/**
	 * GENERAL FUNCTIONS
	 */
	public static function put_an_hr(){
        print '<hr />';
    }

	/**
	 * ADD EMAIL TO MAILCHIMP AFTER CHECKOUT SUCCESS CALLBACKS
	 */
    public static function ro_add_email_to_mailchimp_callback(){
        printf( '<p class="js-wooc-mailchimp-creds-error" style="display:none;color:red;">Your MailChimp API key is not working. Please refresh the page. If this alert appears again, double check your credentials.</p>' );
    }

	public static function add_email_to_mailchimp_api_key_callback(){
        printf(
            '<input type="text" id="add_email_to_mailchimp_api_key" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-xxx" name="ro_wc_options[add_email_to_mailchimp_api_key]" value="%s" />',
            isset( self::$options['add_email_to_mailchimp_api_key'] ) ? esc_attr( self::$options['add_email_to_mailchimp_api_key']) : ''
        );
    }

    public static function add_email_to_mailchimp_list_id_callback(){
        printf(
            '<input type="text" id="add_email_to_mailchimp_list_id" placeholder="xxxxxxx" name="ro_wc_options[add_email_to_mailchimp_list_id]" value="%s" />',
            isset( self::$options['add_email_to_mailchimp_list_id'] ) ? esc_attr( self::$options['add_email_to_mailchimp_list_id']) : ''
        );
    }

    /**
     * EMAIL TEMPLATE PREVIEW
     */
    public static function ro_wc_email_preview_callback(){
    	printf( '<hr />Preview any email template registered in WooCommerce' );
    }

    public static function email_preview_link_callback(){
    	printf( '<a target="_blank" href="/wp-admin/admin-ajax.php?action=previewemail">Preview Email Templates</a>' );	
    }

    /**
     * ABANDONED CART CALLBACKS
     */
    public static function abandoned_cart_enabled_callback(){
        printf(
            '<input type="checkbox" id="abandoned_cart_enabled" name="ro_wc_options[abandoned_cart_enabled]" %s />',
            checked( self::$options['abandoned_cart_enabled'], true, false )
        );
    }
    public static function abandoned_cart_notification_type_callback(){
        printf(
            '<select name="ro_wc_options[abandoned_cart_notification_type]">
                <option value="">No Notification</option>
                <option value="system" %s>System Email</option>
                <!-- <option value="mailchimp" %s>Mailchimp</option> -->
            </select>',
            (isset( self::$options['abandoned_cart_notification_type']) && self::$options['abandoned_cart_notification_type'] == 'system') ? 'selected' : '',
            (isset( self::$options['abandoned_cart_notification_type']) && self::$options['abandoned_cart_notification_type'] == 'mailchimp') ? 'selected' : ''
        );
    }
    public static function abandoned_cart_test_email_callback(){
        printf(
            '<input type="text" id="abandoned_cart_test_email" name="ro_wc_options[abandoned_cart_test_email]" value="%s" /><button type="button" id="ro_send_test_email">Send Test Email</button>
                <div id="abandoned_cart_test_success" style="display:none;color:green;">Email Sent!</div>
                <div id="abandoned_cart_test_failed" style="display:none;color:red;">Email Failed...</div>',
            isset( self::$options['abandoned_cart_test_email'] ) ? esc_attr( self::$options['abandoned_cart_test_email']) : ''
        );
    }
    public static function abandoned_cart_from_name_callback(){
        printf(
            '<input type="text" id="abandoned_cart_from_name" placeholder="%s" name="ro_wc_options[abandoned_cart_from_name]" value="%s" />',
            get_bloginfo( 'name' ),
            isset( self::$options['abandoned_cart_from_name'] ) ? esc_attr( self::$options['abandoned_cart_from_name']) : ''
        );
    }
    public static function abandoned_cart_from_email_callback(){
        printf(
            '<input type="text" id="abandoned_cart_from_email" name="ro_wc_options[abandoned_cart_from_email]" value="%s" size="50" />',
            isset( self::$options['abandoned_cart_from_email'] ) ? esc_attr( self::$options['abandoned_cart_from_email']) : ''
        );
    }
    public static function abandoned_cart_return_url_callback(){
        printf(
            '<input type="text" id="abandoned_cart_return_url" placeholder="%s" name="ro_wc_options[abandoned_cart_return_url]" value="%s" size="50" />',
            get_site_url() . '/cart/',
            isset( self::$options['abandoned_cart_return_url'] ) ? esc_attr( self::$options['abandoned_cart_return_url']) : ''
        );
    }
    public static function abandoned_cart_discount_callback(){
        printf(
            '<input type="text" id="abandoned_cart_discount" name="ro_wc_options[abandoned_cart_discount]" value="%s" />',
            isset( self::$options['abandoned_cart_discount'] ) ? esc_attr( self::$options['abandoned_cart_discount']) : ''
        );
    }
    public static function abandoned_cart_discount_code_callback(){
        printf(
            '<input type="text" id="abandoned_cart_discount_code" name="ro_wc_options[abandoned_cart_discount_code]" value="%s" />',
            isset( self::$options['abandoned_cart_discount_code'] ) ? esc_attr( self::$options['abandoned_cart_discount_code']) : ''
        );
    }
    public static function abandoned_cart_3_day_enabled_callback(){
        printf(
            '<input type="checkbox" id="abandoned_cart_3_day_enabled" name="ro_wc_options[abandoned_cart_3_day_enabled]" %s />',
            checked( self::$options['abandoned_cart_3_day_enabled'], true, false )
        );   
    }
    public static function abandoned_cart_3_day_discount_callback(){
        printf(
            '<input type="text" id="abandoned_cart_3_day_discount" name="ro_wc_options[abandoned_cart_3_day_discount]" value="%s" />',
            isset( self::$options['abandoned_cart_3_day_discount'] ) ? esc_attr( self::$options['abandoned_cart_3_day_discount']) : ''
        );
    }
    public static function abandoned_cart_3_day_discount_code_callback(){
        printf(
            '<input type="text" id="abandoned_cart_3_day_discount_code" name="ro_wc_options[abandoned_cart_3_day_discount_code]" value="%s" />',
            isset( self::$options['abandoned_cart_3_day_discount_code'] ) ? esc_attr( self::$options['abandoned_cart_3_day_discount_code']) : ''
        );
    }
    public static function abandoned_cart_7_day_enabled_callback(){
        printf(
            '<input type="checkbox" id="abandoned_cart_7_day_enabled" name="ro_wc_options[abandoned_cart_7_day_enabled]" %s />',
            checked( self::$options['abandoned_cart_7_day_enabled'], true, false )
        );   
    }
    public static function abandoned_cart_7_day_discount_callback(){
        printf(
            '<input type="text" id="abandoned_cart_7_day_discount" name="ro_wc_options[abandoned_cart_7_day_discount]" value="%s" />',
            isset( self::$options['abandoned_cart_7_day_discount'] ) ? esc_attr( self::$options['abandoned_cart_7_day_discount']) : ''
        );
    }
    public static function abandoned_cart_7_day_discount_code_callback(){
        printf(
            '<input type="text" id="abandoned_cart_7_day_discount_code" name="ro_wc_options[abandoned_cart_7_day_discount_code]" value="%s" />',
            isset( self::$options['abandoned_cart_7_day_discount_code'] ) ? esc_attr( self::$options['abandoned_cart_7_day_discount_code']) : ''
        );
    }
    public static function abandoned_cart_logo_url_callback(){
        printf(
            '<input type="text" id="abandoned_cart_logo_url" name="ro_wc_options[abandoned_cart_logo_url]" value="%s" size="50" />',
            isset( self::$options['abandoned_cart_logo_url'] ) ? esc_attr( self::$options['abandoned_cart_logo_url']) : ''
        );
    }
    public static function abandoned_cart_logo_width_callback(){
        printf(
            '<input type="text" id="abandoned_cart_logo_width" name="ro_wc_options[abandoned_cart_logo_width]" value="%s" />',
            isset( self::$options['abandoned_cart_logo_width'] ) ? esc_attr( self::$options['abandoned_cart_logo_width']) : ''
        );
    }
    public static function abandoned_cart_logo_height_callback(){
        printf(
            '<input type="text" id="abandoned_cart_logo_height" name="ro_wc_options[abandoned_cart_logo_height]" value="%s" />',
            isset( self::$options['abandoned_cart_logo_height'] ) ? esc_attr( self::$options['abandoned_cart_logo_height']) : ''
        );
    }
    public static function abandoned_cart_button_text_callback(){
        printf(
            '<input type="text" id="abandoned_cart_button_text" name="ro_wc_options[abandoned_cart_button_text]" value="%s" />',
            isset( self::$options['abandoned_cart_button_text'] ) ? esc_attr( self::$options['abandoned_cart_button_text']) : ''
        );
    }
    public static function abandoned_cart_email_subject_callback(){
        printf(
            '<input type="text" id="abandoned_cart_email_subject" name="ro_wc_options[abandoned_cart_email_subject]" value="%s" size="50" />',
            isset( self::$options['abandoned_cart_email_subject'] ) ? esc_attr( self::$options['abandoned_cart_email_subject']) : ''
        );
    }
    public static function abandoned_cart_email_message_callback(){
        printf(
            '<textarea id="abandoned_cart_email_message" name="ro_wc_options[abandoned_cart_email_message]" rows="4" cols="50">%s</textarea>',
            isset( self::$options['abandoned_cart_email_message'] ) ? esc_attr( self::$options['abandoned_cart_email_message']) : ''
        );
    }
    public static function abandoned_cart_mailchimp_key_callback(){
        printf(
            '<input type="text" id="abandoned_cart_mailchimp_key" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-xxx" name="ro_wc_options[abandoned_cart_mailchimp_key]" value="%s" />',
            isset( self::$options['abandoned_cart_mailchimp_key'] ) ? esc_attr( self::$options['abandoned_cart_mailchimp_key']) : ''
        );
    }
    public static function abandoned_cart_mailchimp_list_callback(){
        printf(
            '<input type="text" id="abandoned_cart_mailchimp_list" placeholder="xxxxxxx" name="ro_wc_options[abandoned_cart_mailchimp_list]" value="%s" />',
            isset( self::$options['abandoned_cart_mailchimp_list'] ) ? esc_attr( self::$options['abandoned_cart_mailchimp_list']) : ''
        );
    }
    public static function abandoned_cart_mailchimp_config_callback(){
        if( !isset( self::$options['abandoned_cart_mailchimp_config'] ) || !self::$options['abandoned_cart_mailchimp_config'] || self::$options['abandoned_cart_mailchimp_config'] == 'false' ){
            echo '<button type="button" id="mailchimp-config">Configure MailChimp List</button>
            <input type="hidden" id="abandoned_cart_mailchimp_config" name="ro_wc_options[abandoned_cart_mailchimp_config]" value="false">';
        }
        else{
            echo '<button type="button" id="mailchimp-config" disabled>Correctly Configured</button>
            <input type="hidden" id="abandoned_cart_mailchimp_config" name="ro_wc_options[abandoned_cart_mailchimp_config]" value="true">';
        }
    }
}