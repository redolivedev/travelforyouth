<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/*
 * Used to insert data for woocommerce plugin
 */

class RoWcSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_filter( 'plugin_action_links_' . RO_WC_BASENAME, array( $this, 'include_settings_link_on_plugins_page' ) );
    }

    /**
     * Adds a "Settings" link for this plugin on the Installed Plugins page.
     */
    public function include_settings_link_on_plugins_page( $links ){
    	$settings_link = '<a href="admin.php?page=ro-wc-settings-admin">' . __( 'Settings' ) . '</a>';

    	array_unshift( $links, $settings_link );

    	return $links;
    }

    /**
     * Add options page if RO Marketing Free is installed. Otherwise, add menu to tell user to install it
     */
    public function add_plugin_page(){
    	if ( empty ( $GLOBALS['admin_page_hooks']['red-olive'] ) ){
    		add_menu_page(
			    'RO WooCommerce',
			    'RO WooCommerce',
			    'manage_options',
			    'ro-wc-warning',
			    array( $this, 'ro_woocommerce_menu_warning' ),
			    'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIxODBweCIgaGVpZ2h0PSIxODAuMDA4cHgiIHZpZXdCb3g9IjAgMCAxODAgMTgwLjAwOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTgwIDE4MC4wMDgiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxwYXRoIGZpbGw9IiNFQjIyMjciIGQ9Ik04OS45NzksMEM0MC4zNiwwLDAsNDAuMzc2LDAsODkuOTk4YzAsNDkuNjMyLDQwLjM2LDkwLjAxMSw4OS45NzksOTAuMDExYzQ5LjYzNywwLDkwLjAyMS00MC4zNzksOTAuMDIxLTkwLjAxMUMxODAsNDAuMzc2LDEzOS42MTUsMCw4OS45NzksMHogTTg5Ljk3OSwxNjUuMTE4Yy0xOC4yMTIsMC0zNC45MS02LjUzMS00Ny45MjEtMTcuMzYxbDE0LjkyNC0xNC45MzRsOS44MjgtOS44MDljLTMuODE3LTIuNjk1LTcuMTQ3LTYuMDAyLTkuODI4LTkuODE0Yy0yLjgxNC00LjAyOS00Ljg4OS04LjYwOS02LjEwNy0xMy41MTJjLTAuNzczLTMuMTI3LTEuMzE1LTYuMzItMS4zMTUtOS42OWMwLTIyLjI5MiwxOC4xNDctNDAuNDE2LDQwLjQyLTQwLjQxNmMzLjM2MiwwLDYuNTc3LDAuNTM0LDkuNjk5LDEuMjk0bDExLjAyNC0xMS4wMjNjLTYuMzg0LTIuNjU2LTEzLjM3OS00LjE0Ni0yMC43MjQtNC4xNDZjLTI5LjkyNSwwLTU0LjI3NywyNC4zNTYtNTQuMjc3LDU0LjI5MmMwLDcuMzQxLDEuNSwxNC4zMzUsNC4xNDUsMjAuNzI0YzEuODQ0LDQuNDc1LDQuNDE5LDguNTEyLDcuMzM1LDEyLjI5M2wtMTQuOTUxLDE0LjkzOGMtMTAuODA4LTEzLjAxNi0xNy4zNDgtMjkuNzI5LTE3LjM0OC00Ny45NTRjMC00MS40MDcsMzMuNjk3LTc1LjEwNCw3NS4wOTctNzUuMTA0YzE4LjIzLDAsMzQuOTM4LDYuNTMxLDQ3Ljk2MywxNy4zNDNsLTE0LjkzOCwxNC45NDlsLTkuODI4LDkuODA1YzMuODMxLDIuNjgyLDcuMTMzLDYuMDA0LDkuODQyLDkuODEyYzIuODE0LDQuMDI2LDQuODk0LDguNTk4LDYuMTEyLDEzLjUwN2MwLjc3MiwzLjEyMywxLjMxMSw2LjMzMSwxLjMxMSw5LjY4OGMwLDIyLjI5Ni0xOC4xNDcsNDAuNDM1LTQwLjQ2Miw0MC40MzVjLTMuMzI5LDAtNi41NDktMC41MzktOS42NjItMS4zMDNsLTExLjA0MiwxMS4wMjljNi4zOTcsMi42NDgsMTMuMzkzLDQuMTQ4LDIwLjcwNCw0LjE0OGMyOS45NTgsMCw1NC4zMjMtMjQuMzY1LDU0LjMyMy01NC4zMWMwLTcuMzMxLTEuNDk5LTE0LjMxNy00LjE0Ny0yMC43MTRjLTEuODQ1LTQuNDctNC40Mi04LjUxMy03LjMzNi0xMi4yOTNsMTQuOTI0LTE0Ljk0MmMxMC44NCwxMy4wMTUsMTcuMzgsMjkuNzM1LDE3LjM4LDQ3Ljk0OUMxNjUuMTIyLDEzMS40MiwxMzEuNDIsMTY1LjExOCw4OS45NzksMTY1LjExOHoiLz48L3N2Zz4='
			);
    	}else{
	        // This page will be under "Red Olive"
			add_submenu_page(
				'red-olive',
	            'Settings Admin',
	            'RO WooCommerce',
	            'manage_options',
	            'ro-wc-settings-admin',
	            array( $this, 'create_admin_page' )
	        );
    	}
    }

    public function ro_woocommerce_menu_warning(){
    	echo '
		<h2>To use RO WooCommerce, you must first install our free prerequisite plugin, RO Marketing Free.</h2>
		<p>You can install it through your Plugins page by clicking <a target="_blank" href="plugin-install.php?s=ro+marketing&tab=search&type=term">HERE</a>
		</p>
		<p>
			Or you can download it from our website (and learn more about its features) by clicking <a target="_blank" href="https://wordpress.org/plugins/red-olive-marketing/">HERE</a>
		</p>';
		die;
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'ro_wc_options' );
        ?>
        <div class="wrap">
            <h2>RO WooCommerce Settings</h2>
            <h2 class="tab-nav nav-tab-wrapper">
                <a href="#" class="nav-tab nav-tab-active" data-tab="1"></span> General</a>
                <a href="#" class="nav-tab" data-tab="2"></span> Email</a>
                <a href="#" class="nav-tab" data-tab="3"></span> PPC</a>
                <a href="#" class="nav-tab" data-tab="4"></span> Rich Snippets</a>
                <a href="#" class="nav-tab" data-tab="5"></span> Shopping Feeds</a>
                <a href="#" class="nav-tab" data-tab="6"></span> URL Functions</a>
                <a href="#" class="nav-tab" data-tab="7"></span> UX</a>
                <a href="#" class="nav-tab" data-tab="8"></span> Settings</a>

                <!-- Right-aligned elements should appear last -->
                <a href="#" class="nav-tab align-right" data-tab="99"></span> Feature Request</a>
            </h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'ro_woocommerce' );
                ?>

                <div class="tab" data-tab="1">
                    <?php do_settings_sections( 'ro-wc-general' ); ?>
                </div>
                <div class="tab" data-tab="2">
                    <?php do_settings_sections( 'ro-wc-email' ); ?>
                </div>
                <div class="tab" data-tab="3">
                    <?php do_settings_sections( 'ro-wc-ppc' ); ?>
                </div>
                <div class="tab" data-tab="4">
                    <?php do_settings_sections( 'ro-wc-rich-snippets' ); ?>
                </div>
                <div class="tab" data-tab="5">
                    <p><a target="_blank" href="/ro_product_feed/google"><img style="height:14px; width:auto" src="<?php echo RO_WC_URL . '/assets/img/google-icon.png'; ?>" /> See the google/bing ads product feed</a></p>
                    <h4>Exporting and Importing</h4>
                    <div id="import_export">
                        <p style="display:inline-block;margin-right:5px;">
                            <a target="_blank" href="/?ro_product_export=true">Export Product Information</a>
                        </p>
                        <p style="display:inline-block;">|</p>
                        <div style="display:inline-block;margin-right:5px;">
                            <strong>Import CSV File:</strong>
                            <input type="file" name="ro_product_import" id="ro_product_import">
                        </div>
                    </div>
                    <div id="import_processing" style="display:none;">
                        <h3 style="color:DodgerBlue;">Processing...</h3>
                    </div>
                    <div id="import_success" style="display:none;">
                        <h3 style="color:green;">Import Complete!</h3>
                    </div>
                    <div id="import_failure" style="display:none;">
                        <h3 style="color:red;">Import Failed.</h3>
                    </div>

                    <?php do_settings_sections( 'ro-wc-shopping-feeds' ); ?>
                </div>
                <div class="tab" data-tab="6">
                    <?php do_settings_sections( 'ro-wc-url-functions' ); ?>
                </div>
                <div class="tab" data-tab="7">
                    <?php do_settings_sections( 'ro-wc-ux' ); ?>
                </div>
                <div class="tab" data-tab="8">
                    <?php do_settings_sections( 'ro-wc-settings' ); ?>
                </div>

                <!-- Right-aligned elements should appear last -->
                <div class="tab" data-tab="99">
                    <?php do_settings_sections( 'ro-wc-feature-request' ); ?>
                </div>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        RoWooCommerce\FeatureRequestTab::init();
        RoWooCommerce\ShoppingFeedsTab::init();
        RoWooCommerce\URLFunctionsTab::init();
        RoWooCommerce\RichSnippetsTab::init();
        RoWooCommerce\SettingsTab::init();
        RoWooCommerce\GeneralTab::init();
        RoWooCommerce\SocialTab::init();
        RoWooCommerce\EmailTab::init();
        RoWooCommerce\PPCTab::init();
        RoWooCommerce\UXTab::init();

        //Get the marketing options object to use when saving the settings
        $options_singleton = RoWooCommerce\RoWooCommerceOptions::get_instance();

        //Save the settings
        register_setting(
            'ro_woocommerce', // Option group
            'ro_wc_options', // Option name
            array( 'RoWooCommerce\RoWooCommerceOptions', 'sanitize' ) // Sanitize
        );
    }
}

if( is_admin() ) $roWcSettings = new RoWcSettings();