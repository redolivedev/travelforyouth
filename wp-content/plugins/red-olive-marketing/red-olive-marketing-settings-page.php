<?php

namespace RoMarketing;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/*
 * Used to insert data for marketing plugin
 */
class RoMarketingSettings
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
        add_action('admin_init', array( $this, 'page_init' ));
        add_action('admin_menu', array( $this, 'admin_menu' ));
        add_action('admin_enqueue_scripts', array( $this, 'load_ro_marketing_admin_scripts' ));
        add_filter( 'plugin_action_links_' . RO_MARKETING_BASENAME, array( $this, 'include_settings_link_on_plugins_page' ) );
    }

    /**
     * Adds a "Settings" link for this plugin on the Installed Plugins page.
     */
    public function include_settings_link_on_plugins_page( $links ){
    	$settings_link = '<a href="admin.php?page=red-olive">' . __( 'Settings' ) . '</a>';

    	array_unshift( $links, $settings_link );

    	return $links;
    }

    public function admin_menu()
    {
        add_menu_page(
            'Red Olive',
            'Red Olive',
            'manage_options',
            'red-olive',
            array( $this, 'create_admin_page' ),
            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIxODBweCIgaGVpZ2h0PSIxODAuMDA4cHgiIHZpZXdCb3g9IjAgMCAxODAgMTgwLjAwOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTgwIDE4MC4wMDgiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxwYXRoIGZpbGw9IiNFQjIyMjciIGQ9Ik04OS45NzksMEM0MC4zNiwwLDAsNDAuMzc2LDAsODkuOTk4YzAsNDkuNjMyLDQwLjM2LDkwLjAxMSw4OS45NzksOTAuMDExYzQ5LjYzNywwLDkwLjAyMS00MC4zNzksOTAuMDIxLTkwLjAxMUMxODAsNDAuMzc2LDEzOS42MTUsMCw4OS45NzksMHogTTg5Ljk3OSwxNjUuMTE4Yy0xOC4yMTIsMC0zNC45MS02LjUzMS00Ny45MjEtMTcuMzYxbDE0LjkyNC0xNC45MzRsOS44MjgtOS44MDljLTMuODE3LTIuNjk1LTcuMTQ3LTYuMDAyLTkuODI4LTkuODE0Yy0yLjgxNC00LjAyOS00Ljg4OS04LjYwOS02LjEwNy0xMy41MTJjLTAuNzczLTMuMTI3LTEuMzE1LTYuMzItMS4zMTUtOS42OWMwLTIyLjI5MiwxOC4xNDctNDAuNDE2LDQwLjQyLTQwLjQxNmMzLjM2MiwwLDYuNTc3LDAuNTM0LDkuNjk5LDEuMjk0bDExLjAyNC0xMS4wMjNjLTYuMzg0LTIuNjU2LTEzLjM3OS00LjE0Ni0yMC43MjQtNC4xNDZjLTI5LjkyNSwwLTU0LjI3NywyNC4zNTYtNTQuMjc3LDU0LjI5MmMwLDcuMzQxLDEuNSwxNC4zMzUsNC4xNDUsMjAuNzI0YzEuODQ0LDQuNDc1LDQuNDE5LDguNTEyLDcuMzM1LDEyLjI5M2wtMTQuOTUxLDE0LjkzOGMtMTAuODA4LTEzLjAxNi0xNy4zNDgtMjkuNzI5LTE3LjM0OC00Ny45NTRjMC00MS40MDcsMzMuNjk3LTc1LjEwNCw3NS4wOTctNzUuMTA0YzE4LjIzLDAsMzQuOTM4LDYuNTMxLDQ3Ljk2MywxNy4zNDNsLTE0LjkzOCwxNC45NDlsLTkuODI4LDkuODA1YzMuODMxLDIuNjgyLDcuMTMzLDYuMDA0LDkuODQyLDkuODEyYzIuODE0LDQuMDI2LDQuODk0LDguNTk4LDYuMTEyLDEzLjUwN2MwLjc3MiwzLjEyMywxLjMxMSw2LjMzMSwxLjMxMSw5LjY4OGMwLDIyLjI5Ni0xOC4xNDcsNDAuNDM1LTQwLjQ2Miw0MC40MzVjLTMuMzI5LDAtNi41NDktMC41MzktOS42NjItMS4zMDNsLTExLjA0MiwxMS4wMjljNi4zOTcsMi42NDgsMTMuMzkzLDQuMTQ4LDIwLjcwNCw0LjE0OGMyOS45NTgsMCw1NC4zMjMtMjQuMzY1LDU0LjMyMy01NC4zMWMwLTcuMzMxLTEuNDk5LTE0LjMxNy00LjE0Ny0yMC43MTRjLTEuODQ1LTQuNDctNC40Mi04LjUxMy03LjMzNi0xMi4yOTNsMTQuOTI0LTE0Ljk0MmMxMC44NCwxMy4wMTUsMTcuMzgsMjkuNzM1LDE3LjM4LDQ3Ljk0OUMxNjUuMTIyLDEzMS40MiwxMzEuNDIsMTY1LjExOCw4OS45NzksMTY1LjExOHoiLz48L3N2Zz4=',
            79
        );
        add_submenu_page(
            'red-olive',
            'Settings Admin',
            RO_MARKETING_PRO_ACTIVE ? 'RO Marketing Pro' : 'RO Marketing Free',
            'manage_options',
            'red-olive',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('ro_marketing_options');
        $setting_page_heading = RO_MARKETING_PRO_ACTIVE ? 'RO Marketing Pro Settings' : 'RO Marketing Free Settings'; ?>
		<div class="wrap">
            <h2><?php echo $setting_page_heading; ?></h2>
			<h2 class="tab-nav nav-tab-wrapper">
				<a href="#" class="nav-tab nav-tab-active" data-tab="1"></span> General</a>
				<a href="#" class="nav-tab" data-tab="2"></span> Call Tracking</a>
				<a href="#" class="nav-tab" data-tab="3"></span> CRO</a>
				<a href="#" class="nav-tab" data-tab="4"></span> Email</a>
				<a href="#" class="nav-tab" data-tab="5"></span> Live Chat</a>
				<a href="#" class="nav-tab" data-tab="6"></span> Local SEO</a>
				<a href="#" class="nav-tab" data-tab="7"></span> PPC</a>
				<a href="#" class="nav-tab" data-tab="8"></span> Promos</a>
				<a href="#" class="nav-tab" data-tab="9"></span> QA</a>
				<a href="#" class="nav-tab" data-tab="10"></span> Redirects</a>
				<a href="#" class="nav-tab" data-tab="11"></span> Reviews</a>
				<a href="#" class="nav-tab" data-tab="12"></span> Scripts</a>
				<a href="#" class="nav-tab" data-tab="13"></span> Settings</a>
				<a href="#" class="nav-tab" data-tab="14"></span> Social</a>
				<a href="#" class="nav-tab ll-nav-tab" data-tab="15"></span> Legit Local</a>

				<!-- These right-aligned elements should appear last -->
				<?php if (! RO_MARKETING_PRO_ACTIVE): ?>
					<a href="<?php echo RO_MARKETING_PRO_DOWNLOAD_URL ?>" target="_blank" class="nav-tab-link align-right" data-tab="98">Buy Pro</span></a>
				<?php endif; ?>
				<a href="#" class="nav-tab align-right" data-tab="99"></span> Feature Request</a>
			</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields('ro_marketing'); ?>

				<div class="tab" data-tab="1">
					<?php do_settings_sections('ro-marketing-general'); ?>
				</div>
				<div class="tab" data-tab="2">
					<?php do_settings_sections('ro-marketing-call-tracking'); ?>
				</div>
				<div class="tab" data-tab="3">
					<?php do_settings_sections('ro-marketing-cro'); ?>
				</div>
				<div class="tab" data-tab="4">
					<?php do_settings_sections('ro-marketing-email'); ?>
				</div>
				<div class="tab" data-tab="5">
					<?php do_settings_sections('ro-marketing-live-chat'); ?>
				</div>
				<div class="tab" data-tab="6">
					<?php do_settings_sections('ro-marketing-local-seo'); ?>
				</div>
				<div class="tab" data-tab="7">
					<?php do_settings_sections('ro-marketing-ppc'); ?>
				</div>
				<div class="tab" data-tab="8">
					<?php do_settings_sections('ro-marketing-promo'); ?>
				</div>
				<div class="tab" data-tab="9">
					<?php do_settings_sections('ro-marketing-qa'); ?>
				</div>
				<div class="tab" data-tab="10">
					<?php do_settings_sections('ro-marketing-redirects'); ?>
				</div>
				<div class="tab" data-tab="11">
					<?php do_settings_sections('ro-marketing-reviews'); ?>
				</div>
				<div class="tab" data-tab="12">
					<?php do_settings_sections('ro-marketing-scripts'); ?>
				</div>
				<div class="tab" data-tab="13">
					<?php do_settings_sections('ro-marketing-settings'); ?>
				</div>
				<div class="tab" data-tab="14">
					<?php do_settings_sections('ro-marketing-social'); ?>
				</div>
				<div class="tab ll-tab" data-tab="15">
					<p>
						<img src="https://legitlocal.com/company_logo.png" alt="Legit Local Logo" />
					</p>
					<h3>What is Legit Local</h3>
					<p>Legit Local is an easy way to request feedback and reviews from your customers.</p>
					<p>
						<a class="button button-primary" href="https://legitlocal.com?utm_source=marketing%20plugin">Get a Free Trial</a>
					</p>

					<div class="ll-propositions">
						<div class="row">
							<div class="column">
								<h2 class="h2">How It Works</h2>
							</div>
						</div>
						<div class="row">
							<div class="how-it-works column">
								<div class="info">
									<div class="step">1</div>
									<div class="text">Request customer feedback 1-10 stars (private)</div>
								</div>
								<div class="phone">
									<img alt="Request customer feedback 1-10 stars (private)" src="https://legitlocal.com/how-to-1.png">
								</div>
							</div>
							<div class="how-it-works column">
								<div class="info">
									<div class="step">2</div>
									<div class="text">Customers that rate their experience high (8-10) are asked to leave an online review</div>
								</div>
								<div class="phone">
									<img alt="Customers that rate their experience high (8-10) are asked to leave an online review" src="https://legitlocal.com/how-to-2.png">
								</div>
							</div>
							<div class="how-it-works column">
								<div class="info">
									<div class="step">3</div>
									<div class="text">Their feedback and review are sent to your dashboard</div>
								</div>
								<div class="phone">
									<img alt="Their feedback and review are sent to your dashboard" src="https://legitlocal.com/how-to-3.png">
								</div>
							</div>
						</div>
					</div>

					<p>
						<a class="button button-secondary" href="https://legitlocal.com?utm_source=marketing%20plugin">Get a Free Trial</a>
					</p>
				</div>


				<!-- These right-aligned elements should appear last -->
				<div class="tab" data-tab="98">

				</div>
				<div class="tab" data-tab="99">
					<?php do_settings_sections('ro-marketing-feature-request'); ?>
				</div>

				<?php submit_button(); ?>
            </form>
        </div>
		<?php
    }

    /**
     * Register and add settings tabs
     */
    public function page_init()
    {

        //Initialize all of the tab classes
        QATab::init();
        PPCTab::init();
        CROTab::init();
        EmailTab::init();
        PromoTab::init();
        SocialTab::init();
        GeneralTab::init();
        ReviewsTab::init();
        ScriptsTab::init();
        LocalSeoTab::init();
        LiveChatTab::init();
        SettingsTab::init();
        RedirectsTab::init();
        CallTrackingTab::init();
        FeatureRequestTab::init();

        //Save the settings
        register_setting(
            'ro_marketing', // Option group
            'ro_marketing_options', // Option name
            array( 'RoMarketing\RoMarketingOptions', 'sanitize' ) // Sanitize
        );
    }

    /**
     * Add necessary admin scripts
     */
    public function load_ro_marketing_admin_scripts()
    {
        wp_enqueue_script('ro_marketing', plugin_dir_url(__FILE__) . 'assets/js/ro-marketing.js');
    }
}

if (is_admin()) {
    $roMarketingSettings = new RoMarketingSettings();
}
