<?php

namespace RoWooCommerce;

class ShoppingFeedsTab{

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
		 * SHOPPING FEEDS
		 */
		 add_settings_section( 
            'ro_feeds_settings', // ID
            'Shopping Feeds Global Settings', // Title
            array( __CLASS__, 'put_an_hr' ), // Callback
            'ro-wc-shopping-feeds' // Page
        );
	}

	private static function add_fields(){

		/**
		 * SHOPPING FEEDS
		 */
		add_settings_field(  
            'feeds_global_brand', // ID
            'Global Brand', // Title
            array( __CLASS__, 'feeds_global_brand_callback' ), // Callback
            'ro-wc-shopping-feeds', // Page
            'ro_feeds_settings' // Section
        );

        add_settings_field(
            'feeds_global_category',
            'Global Category',
            array( __CLASS__, 'feeds_global_category_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_product_type',
            'Global Product Type',
            array( __CLASS__, 'feeds_global_product_type_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_condition',
            'Global Condition',
            array( __CLASS__, 'feeds_global_condition_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );
        
        add_settings_field(
            'feeds_global_age_group',
            'Global Age Group',
            array( __CLASS__, 'feeds_global_age_group_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_gender',
            'Global Gender',
            array( __CLASS__, 'feeds_global_gender_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_size_type',
            'Global Size Type',
            array( __CLASS__, 'feeds_global_size_type_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_size',
            'Global Size',
            array( __CLASS__, 'feeds_global_size_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_color',
            'Global Color',
            array( __CLASS__, 'feeds_global_color_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_enable_global_custom_labels',
            'Enable Global Custom Labels<br/>
            <i><span style="font-weight:normal">For stores with more than 1000 products, uncheck this box to use all Custom Labels</span></i><br/>More info <a target="_blank" href="https://www.redolive.io/woocommerce-google-shopping-feed-plugin/#custom-labels">HERE</a>',
            array( __CLASS__, 'feeds_enable_global_custom_labels_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_custom_label_1',
            'Global Custom Label 1',
            array( __CLASS__, 'feeds_global_custom_label_1_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_custom_label_2',
            'Global Custom Label 2',
            array( __CLASS__, 'feeds_global_custom_label_2_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_custom_label_3',
            'Global Custom Label 3',
            array( __CLASS__, 'feeds_global_custom_label_3_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_custom_label_4',
            'Global Custom Label 4',
            array( __CLASS__, 'feeds_global_custom_label_4_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_description',
            'Global Description',
            array( __CLASS__, 'feeds_global_description_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );

        add_settings_field(
            'feeds_global_min_price',
            'Minimum Product Price (to be included in the feed)',
            array( __CLASS__, 'feeds_global_min_price_callback' ),
            'ro-wc-shopping-feeds',
            'ro_feeds_settings'
        );
	}

	/**
	 * GENERAL FUNCTIONS
	 */
	public static function put_an_hr(){
        print '<hr />';
    }

	/**
	 * SHOPPING FEEDS CALLBACKS
	 */
    public static function feeds_global_brand_callback()
    {
        printf(
            '<input type="text" id="feeds_global_brand" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_brand]" value="%s" />',
            isset( self::$options['feeds_global_brand'] ) ? esc_attr( self::$options['feeds_global_brand']) : ''
        );
    }

    public static function feeds_global_category_callback()
    {
        printf(
            '<input type="text" id="feeds_global_category" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_category]" value="%s" />',
            isset( self::$options['feeds_global_category'] ) ? esc_attr( self::$options['feeds_global_category']) : ''
        );
    }

    public static function feeds_global_product_type_callback()
    {
        printf(
            '<input type="text" id="feeds_global_product_type" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_product_type]" value="%s" />',
            isset( self::$options['feeds_global_product_type'] ) ? esc_attr( self::$options['feeds_global_product_type']) : ''
        );
    }

    public static function feeds_global_condition_callback()
    {
        printf(
            '<select name="ro_wc_options[feeds_global_condition]">
                <option value="new" %s>New</option>
                <option value="refurbished" %s>Refurbished</option>
                <option value="used" %s>Used</option>
            </select>',
            (isset( self::$options['feeds_global_condition']) && self::$options['feeds_global_condition'] == 'new') ?
            'selected' : '',
            (isset( self::$options['feeds_global_condition']) && self::$options['feeds_global_condition'] == 'refurbished') ? 'selected' : '',
            (isset( self::$options['feeds_global_condition']) && self::$options['feeds_global_condition'] == 'used') ?
            'selected' : ''
        );
    }

    public static function feeds_global_age_group_callback()
    {
        printf(
            '<select name="ro_wc_options[feeds_global_age_group]">
                <option value="" >No Age Group</option>
                <option value="adult" %s>Adult</option>
                <option value="kids" %s>Kids</option>
                <option value="toddler" %s>Toddler</option>
                <option value="infant" %s>Infant</option>
                <option value="new" %s>Newborn</option>
            </select>',
            (isset( self::$options['feeds_global_age_group']) && self::$options['feeds_global_age_group'] == 'adult') ?
            'selected' : '',
            (isset( self::$options['feeds_global_age_group']) && self::$options['feeds_global_age_group'] == 'kids') ?
            'selected' : '',
            (isset( self::$options['feeds_global_age_group']) && self::$options['feeds_global_age_group'] == 'toddler') ?
            'selected' : '',
            (isset( self::$options['feeds_global_age_group']) && self::$options['feeds_global_age_group'] == 'infant') ? 'selected' : '',
            (isset( self::$options['feeds_global_age_group']) && self::$options['feeds_global_age_group'] == 'newborn') ?
            'selected' : ''
        );
    }

    public static function feeds_global_gender_callback()
    {
        printf(
            '<select name="ro_wc_options[feeds_global_gender]">
                <option value="">No Global Gender</option>
                <option value="male" %s>Male</option>
                <option value="female" %s>Female</option>
                <option value="unisex" %s>Unisex</option>
            </select>',
            (isset( self::$options['feeds_global_gender']) && self::$options['feeds_global_gender'] == 'male') ?
            'selected' : '',
            (isset( self::$options['feeds_global_gender']) && self::$options['feeds_global_gender'] == 'female') ? 'selected' : '',
            (isset( self::$options['feeds_global_gender']) && self::$options['feeds_global_gender'] == 'unisex') ?
            'selected' : ''
        );
    }

    public static function feeds_global_size_type_callback()
    {
        printf(
            '<select name="ro_wc_options[feeds_global_size_type]">
                <option value="">No Global Size Type</option>
                <option value="regular" %s>Regular</option>
                <option value="petite" %s>Petite</option>
                <option value="plus" %s>Plus</option>
                <option value="bigandtall" %s>Big and Tall</option>
                <option value="maternity" %s>Maternity</option>
            </select>',
            (isset( self::$options['feeds_global_size_type']) && self::$options['feeds_global_size_type'] == 'regular') ?
            'selected' : '',
            (isset( self::$options['feeds_global_size_type']) && self::$options['feeds_global_size_type'] == 'petite') ? 'selected' : '',
            (isset( self::$options['feeds_global_size_type']) && self::$options['feeds_global_size_type'] == 'plus') ?
            'selected' : '',
            (isset( self::$options['feeds_global_size_type']) && self::$options['feeds_global_size_type'] == 'bigandtall') ?
            'selected' : '',
            (isset( self::$options['feeds_global_size_type']) && self::$options['feeds_global_size_type'] == 'maternity') ?
            'selected' : ''
        );
    }

    public static function feeds_global_size_callback()
    {
        printf(
            '<input type="text" id="feeds_global_size" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_size]" value="%s" />',
            isset( self::$options['feeds_global_size'] ) ? esc_attr( self::$options['feeds_global_size']) : ''
        );
    }

    public static function feeds_global_color_callback()
    {
        printf(
            '<input type="text" id="feeds_global_color" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_color]" value="%s" />',
            isset( self::$options['feeds_global_color'] ) ? esc_attr( self::$options['feeds_global_color']) : ''
        );
    }

    public static function feeds_enable_global_custom_labels_callback(){
        printf(
            '<input type="checkbox" id="feeds_enable_global_custom_labels" name="ro_wc_options[feeds_enable_global_custom_labels]" %s />',
            checked( self::$options['feeds_enable_global_custom_labels'], true, false )
        );
    }

    public static function feeds_global_custom_label_1_callback()
    {
        printf(
            '<input type="text" id="feeds_global_custom_label_1" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_custom_label_1]" value="%s" />',
            isset( self::$options['feeds_global_custom_label_1'] ) ? esc_attr( self::$options['feeds_global_custom_label_1']) : ''
        );
    }

    public static function feeds_global_custom_label_2_callback()
    {
        printf(
            '<input type="text" id="feeds_global_custom_label_2" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_custom_label_2]" value="%s" />',
            isset( self::$options['feeds_global_custom_label_2'] ) ? esc_attr( self::$options['feeds_global_custom_label_2']) : ''
        );
    }

    public static function feeds_global_custom_label_3_callback()
    {
        printf(
            '<input type="text" id="feeds_global_custom_label_3" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_custom_label_3]" value="%s" />',
            isset( self::$options['feeds_global_custom_label_3'] ) ? esc_attr( self::$options['feeds_global_custom_label_3']) : ''
        );
    }

    public static function feeds_global_custom_label_4_callback()
    {
        printf(
            '<input type="text" id="feeds_global_custom_label_4" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_custom_label_4]" value="%s" />',
            isset( self::$options['feeds_global_custom_label_4'] ) ? esc_attr( self::$options['feeds_global_custom_label_4']) : ''
        );
    }

    public static function feeds_global_description_callback()
    {
        printf(
            '<textarea rows="6" cols="50" maxlength="4999" id="feeds_global_description" name="ro_wc_options[feeds_global_description]">%s</textarea><p><span class="global-desc-counter">0</span>/4999</p>',
            isset( self::$options['feeds_global_description'] ) ? esc_attr( self::$options['feeds_global_description']) : ''
        );
    }

    public static function feeds_global_min_price_callback(){
        printf(
            '<input type="text" id="feeds_global_min_price" placeholder="xxxxxxx" name="ro_wc_options[feeds_global_min_price]" value="%s" />',
            isset( self::$options['feeds_global_min_price'] ) ? esc_attr( self::$options['feeds_global_min_price']) : ''
        );
    }
}