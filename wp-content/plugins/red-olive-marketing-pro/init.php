<?php

namespace RoMarketingPro;

class RoMarketingAdminPage
{

    function __construct()
    {
        add_action('plugins_loaded', array($this, 'acf_init'));
        add_action('init', array($this, 'register_post_types'));
        add_action('wp_enqueue_scripts', array($this, 'load_acf_marketing_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'load_marketing_admin_scripts'));
    }

    //Make sure Advanced Custom Fields has a chance to load before checking for it
    public function acf_init()
    {
        if (function_exists('acf_add_options_page')) {

            // ab testing
            acf_add_options_page(array(
                'page_title' => 'On-Site Text Variation Creator',
                'menu_slug' => 'on-site-text-variation-creator',
                'parent_slug' => 'ro-marketing-settings-admin',
                'capability' => 'edit_posts',
                'redirect' => false
            ));

            // rewrites
            acf_add_options_page(array(
                'page_title' => 'Redirects',
                'menu_slug' => 'ro-redirects',
                'parent_slug' => 'ro-marketing-settings-admin',
                'capability' => 'edit_posts',
                'redirect' => false
            ));

            // Social Media Reviews
            acf_add_options_page(array(
                'page_title' => 'Review Collection Page Settings',
                'menu_slug' => 'review-collection-page-settings',
                'parent_slug' => 'ro-marketing-settings-admin',
                'capability' => 'edit_posts',
                'redirect' => false
            ));

            // KML Sitemap
            acf_add_options_page(array(
                'page_title' => 'KML Sitemap',
                'menu_slug' => 'ro-kml-sitemap',
                'parent_slug' => 'ro-marketing-settings-admin',
                'capability' => 'edit_posts',
                'redirect' => false
            ));

            // NAP Builder
            acf_add_options_page(array(
                'page_title' => 'NAP Builder',
                'menu_slug' => 'ro-nap-builder',
                'parent_slug' => 'ro-marketing-settings-admin',
                'capability' => 'edit_posts',
                'redirect' => false
            ));

            // Site Wide Banner
            acf_add_options_page(array(
                'page_title' => 'Site Wide Banner',
                'menu_slug' => 'ro-site-wide-banner',
                'parent_slug' => 'ro-marketing-settings-admin',
                'capability' => 'edit_posts',
                'redirect' => false
            ));

            //Add the local field groups for ACF
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/pop-up.php';
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/ab-testing.php';
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/nap-builder.php';
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/floating-cta.php';
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/scripts.php';
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/site-wide-banner.php';
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/social-media-reviews.php';

            //Add a variable to be used by the ACF file for rewrites
            $protocol = 'http://';
            if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) {
                $protocol = 'https://';
            }
            $base_url = $protocol . $_SERVER['HTTP_HOST'];
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/rewrites.php';
            require RO_MARKETING_PRO_DIR . 'includes/acf-fields/kml-sitemap.php';
        }
    }

    public function register_post_types()
    {
        $this->register_popup_post_type();
        $this->register_popup_comment_post_type();
        $this->register_floating_cta_post_type();
        $this->register_scripts_post_type();
    }

    protected function register_popup_post_type()
    {

        $labels = array(
            'name' => __('Pop-Ups', 'red-olive'),
            'singular_name' => __('Pop-Up', 'red-olive'),
            'add_new' => _x('Add New Pop-up', 'red-olive', 'red-olive'),
            'add_new_item' => __('Add New Pop-up', 'red-olive'),
            'edit_item' => __('Edit Pop-up', 'red-olive'),
            'new_item' => __('New Pop-up', 'red-olive'),
            'view_item' => __('View Pop-up', 'red-olive'),
            'search_items' => __('Search Pop-ups', 'red-olive'),
            'not_found' => __('No Pop-ups found', 'red-olive'),
            'not_found_in_trash' => __('No Pop-ups found in Trash', 'red-olive'),
            'parent_item_colon' => __('Parent Pop-up:', 'red-olive'),
            'menu_name' => __('Pop-ups', 'red-olive'),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => 'Pop-ups',
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => null,
            'menu_icon' => 'dashicons-slides',
            'show_in_nav_menus' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'supports' => array(
                'title'
            )
        );

        /**
         * Registers a new post type
         * @param string  Post type key, must not exceed 20 characters
         * @param array|string  See optional args description above.
         **@uses $wp_post_types Inserts new post type object into the list
         *
         */
        register_post_type('pop-up', $args);
    }

    protected function register_popup_comment_post_type()
    {

        $labels = array(
            'name' => __('Pop-Up Comments', 'red-olive'),
            'singular_name' => __('Pop-Up Comment', 'red-olive'),
            'edit_item' => __('Edit Pop-up comment', 'red-olive'),
            'new_item' => __('New Pop-up comment', 'red-olive'),
            'view_item' => __('View Pop-up comment', 'red-olive'),
            'search_items' => __('Search Pop-up comments', 'red-olive'),
            'not_found' => __('No Pop-up comments found', 'red-olive'),
            'not_found_in_trash' => __('No Pop-up comments found in Trash', 'red-olive'),
            'menu_name' => __('Pop-up Comments', 'red-olive'),
        );

        $args = array(
            'labels' => $labels,
            'description' => 'Pop-up comments',
            'show_ui' => true,
            'public' => false,
            'show_in_menu' => 'edit.php?post_type=pop-up',
            'has_archive' => true
        );

        /**
         * Registers a new post type
         * @param string  Post type key, must not exceed 20 characters
         * @param array|string  See optional args description above.
         **@uses $wp_post_types Inserts new post type object into the list
         *
         */
        register_post_type('pop-up-comment', $args);
    }

    protected function register_floating_cta_post_type()
    {
        $labels = array(
            'name' => __('Floating CTAs', 'red-olive'),
            'singular_name' => __('Floating CTA', 'red-olive'),
            'add_new' => _x('Add Floating CTA', 'red-olive', 'red-olive'),
            'add_new_item' => __('Add Floating CTA', 'red-olive'),
            'edit_item' => __('Edit Floating CTA', 'red-olive'),
            'new_item' => __('New Floating CTA', 'red-olive'),
            'view_item' => __('View Floating CTA', 'red-olive'),
            'search_items' => __('Search Floating CTAs', 'red-olive'),
            'not_found' => __('No Floating CTAs found', 'red-olive'),
            'not_found_in_trash' => __('No Floating CTAs found in Trash', 'red-olive'),
            'parent_item_colon' => __('Parent Floating CTA:', 'red-olive'),
            'menu_name' => __('Floating CTAs', 'red-olive'),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => 'Floating CTAs',
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => null,
            'menu_icon' => 'dashicons-testimonial',
            'show_in_nav_menus' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'supports' => array(
                'title'
            )
        );

        /**
         * Registers a new post type
         * @uses $wp_post_types Inserts new post type object into the list
         *
         * @param string  Post type key, must not exceed 20 characters
         * @param array|string  See optional args description above.
         **/
        register_post_type( 'floating-cta', $args );
    }


    protected function register_scripts_post_type()
    {
        $labels = array(
            'name' => __('Scripts', 'red-olive'),
            'singular_name' => __('Add Script', 'red-olive'),
            'add_new' => _x('Add Script', 'red-olive', 'red-olive'),
            'add_new_item' => __('Add Script', 'red-olive'),
            'edit_item' => __('Edit Script', 'red-olive'),
            'new_item' => __('New Script', 'red-olive'),
            'view_item' => __('View Script', 'red-olive'),
            'search_items' => __('Search Scripts', 'red-olive'),
            'not_found' => __('No Scripts found', 'red-olive'),
            'not_found_in_trash' => __('No scripts found in Trash', 'red-olive'),
            'parent_item_colon' => __('Parent scripts:', 'red-olive'),
            'menu_name' => __('Scripts', 'red-olive'),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => 'Scripts',
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'menu_position' => null,
            'menu_icon' => 'dashicons-cloud',
            'show_in_nav_menus' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'supports' => array(
                'title'
            )
        );

        /**
         * Registers a new post type
         * @param string  Post type key, must not exceed 20 characters
         * @param array|string  See optional args description above.
         **@uses $wp_post_types Inserts new post type object into the list
         *
         */
        register_post_type('script', $args);
    }

    //Add front end scripts that require ACF
    public function load_acf_marketing_scripts()
    {

        if (!function_exists('acf_add_options_page')) {
            return;
        }

        $browser = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        $isIE7 = (bool)preg_match('/msie 7./i', $browser);
        $isIE8 = (bool)preg_match('/msie 8./i', $browser);


        if (function_exists('get_field')) {
            $ab_testing_active = get_field('activate_ab_testing', 'options');
        } else {
            $ab_testing_active = false;
        }

        if (!$isIE7 && !$isIE8 && $ab_testing_active) {
            wp_enqueue_script('ab_testing_script', RO_MARKETING_PRO_URL . 'assets/js/abTesting.js', array('jquery'));
        }
    }

    //Add admin scripts
    public function load_marketing_admin_scripts()
    {
        wp_enqueue_script('ro-marketing-admin', RO_MARKETING_PRO_URL . 'assets/js/admin.js', array('jquery'), 1.0, true);
        wp_enqueue_script('ro_redirect_csv_upload_script', RO_MARKETING_PRO_URL . 'assets/js/csvRedirectsUpload.js', array('jquery'));
    }
}

new RoMarketingAdminPage;

//Allow for ajax calls
if (defined('DOING_AJAX') && DOING_AJAX) require_once RO_MARKETING_PRO_DIR . 'includes/ro-ajax.php';

require RO_MARKETING_PRO_DIR . 'includes/edd-update/edd-update.php';

// Add the settings pages and tab files
if (is_admin()) {
    //Settings pages
    require_once RO_MARKETING_PRO_DIR . 'red-olive-marketing-settings-page.php';

    //Tabs
    require RO_MARKETING_PRO_DIR . 'includes/singletons/options-singleton.php';
    require RO_MARKETING_PRO_DIR . 'includes/tabs/cro-tab.php';
    require RO_MARKETING_PRO_DIR . 'includes/tabs/email-tab.php';
    require RO_MARKETING_PRO_DIR . 'includes/tabs/promo-tab.php';
    require RO_MARKETING_PRO_DIR . 'includes/tabs/general-tab.php';
    require RO_MARKETING_PRO_DIR . 'includes/tabs/reviews-tab.php';
    require RO_MARKETING_PRO_DIR . 'includes/tabs/settings-tab.php';
    require RO_MARKETING_PRO_DIR . 'includes/tabs/local-seo-tab.php';
    require RO_MARKETING_PRO_DIR . 'includes/tabs/redirects-tab.php';
}

//Get all of the required files
require RO_MARKETING_PRO_DIR . 'includes/acf-fields/acf-setup.php';
require RO_MARKETING_PRO_DIR . 'includes/required-files/required-files.php';
