<?php

namespace RoMarketingPro;

if ( ! defined( 'ABSPATH' ) ) {
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
        add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_menu', array( $this, 'ro_marketing_menu_message' ), 100 );
        add_filter( 'plugin_action_links_' . RO_MARKETING_PRO_BASENAME, array( $this, 'include_settings_link_on_plugins_page' ) );
    }

    /**
     * Adds a "Settings" link for this plugin on the Installed Plugins page.
     */
    public function include_settings_link_on_plugins_page( $links ){
    	$settings_link = '<a href="admin.php?page=red-olive">' . __( 'Settings' ) . '</a>';

    	array_unshift( $links, $settings_link );

    	return $links;
    }

	/**
	 * Displays a menu item which informs the user that the RO Marketing Free plugin needs to be installed.
	 */
	public function ro_marketing_menu_message(){
	    if (! defined("RO_MARKETING_FREE")) {
			add_menu_page(
			    'RO Marketing',
			    'RO Marketing',
			    'manage_options',
			    'ro-marketing-warning',
			    array( $this, 'ro_marketing_menu_warning' ),
			    'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIxODBweCIgaGVpZ2h0PSIxODAuMDA4cHgiIHZpZXdCb3g9IjAgMCAxODAgMTgwLjAwOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTgwIDE4MC4wMDgiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxwYXRoIGZpbGw9IiNFQjIyMjciIGQ9Ik04OS45NzksMEM0MC4zNiwwLDAsNDAuMzc2LDAsODkuOTk4YzAsNDkuNjMyLDQwLjM2LDkwLjAxMSw4OS45NzksOTAuMDExYzQ5LjYzNywwLDkwLjAyMS00MC4zNzksOTAuMDIxLTkwLjAxMUMxODAsNDAuMzc2LDEzOS42MTUsMCw4OS45NzksMHogTTg5Ljk3OSwxNjUuMTE4Yy0xOC4yMTIsMC0zNC45MS02LjUzMS00Ny45MjEtMTcuMzYxbDE0LjkyNC0xNC45MzRsOS44MjgtOS44MDljLTMuODE3LTIuNjk1LTcuMTQ3LTYuMDAyLTkuODI4LTkuODE0Yy0yLjgxNC00LjAyOS00Ljg4OS04LjYwOS02LjEwNy0xMy41MTJjLTAuNzczLTMuMTI3LTEuMzE1LTYuMzItMS4zMTUtOS42OWMwLTIyLjI5MiwxOC4xNDctNDAuNDE2LDQwLjQyLTQwLjQxNmMzLjM2MiwwLDYuNTc3LDAuNTM0LDkuNjk5LDEuMjk0bDExLjAyNC0xMS4wMjNjLTYuMzg0LTIuNjU2LTEzLjM3OS00LjE0Ni0yMC43MjQtNC4xNDZjLTI5LjkyNSwwLTU0LjI3NywyNC4zNTYtNTQuMjc3LDU0LjI5MmMwLDcuMzQxLDEuNSwxNC4zMzUsNC4xNDUsMjAuNzI0YzEuODQ0LDQuNDc1LDQuNDE5LDguNTEyLDcuMzM1LDEyLjI5M2wtMTQuOTUxLDE0LjkzOGMtMTAuODA4LTEzLjAxNi0xNy4zNDgtMjkuNzI5LTE3LjM0OC00Ny45NTRjMC00MS40MDcsMzMuNjk3LTc1LjEwNCw3NS4wOTctNzUuMTA0YzE4LjIzLDAsMzQuOTM4LDYuNTMxLDQ3Ljk2MywxNy4zNDNsLTE0LjkzOCwxNC45NDlsLTkuODI4LDkuODA1YzMuODMxLDIuNjgyLDcuMTMzLDYuMDA0LDkuODQyLDkuODEyYzIuODE0LDQuMDI2LDQuODk0LDguNTk4LDYuMTEyLDEzLjUwN2MwLjc3MiwzLjEyMywxLjMxMSw2LjMzMSwxLjMxMSw5LjY4OGMwLDIyLjI5Ni0xOC4xNDcsNDAuNDM1LTQwLjQ2Miw0MC40MzVjLTMuMzI5LDAtNi41NDktMC41MzktOS42NjItMS4zMDNsLTExLjA0MiwxMS4wMjljNi4zOTcsMi42NDgsMTMuMzkzLDQuMTQ4LDIwLjcwNCw0LjE0OGMyOS45NTgsMCw1NC4zMjMtMjQuMzY1LDU0LjMyMy01NC4zMWMwLTcuMzMxLTEuNDk5LTE0LjMxNy00LjE0Ny0yMC43MTRjLTEuODQ1LTQuNDctNC40Mi04LjUxMy03LjMzNi0xMi4yOTNsMTQuOTI0LTE0Ljk0MmMxMC44NCwxMy4wMTUsMTcuMzgsMjkuNzM1LDE3LjM4LDQ3Ljk0OUMxNjUuMTIyLDEzMS40MiwxMzEuNDIsMTY1LjExOCw4OS45NzksMTY1LjExOHoiLz48L3N2Zz4='
			);
		}
	}

	public function ro_marketing_menu_warning(){
		echo '
		<h2>To use RO Marketing Pro, you must first install our free prerequisite plugin, RO Marketing Free.</h2>
		<p>You can install it through your Plugins page by clicking <a target="_blank" href="plugin-install.php?s=ro+marketing&tab=search&type=term">HERE</a>
		</p>
		<p>
			Or you can download it from our website (and learn more about its features) by clicking <a target="_blank" href="https://wordpress.org/plugins/red-olive-marketing/">HERE</a>
		</p>';
		die;
	}

	/**
	 * Register and add settings
	 */
	public function page_init(){

		//Initialize all of the tab classes for pro version
		CROTab::init();
		EmailTab::init();
		PromoTab::init();
		GeneralTab::init();
		ReviewsTab::init();
		LocalSeoTab::init();
		SettingsTab::init();
		RedirectsTab::init();

		//Save the settings
		register_setting(
			'ro_marketing', // Option group
			'ro_marketing_options', // Option name
			array( 'RoMarketingPro\RoMarketingOptions', 'sanitize' ) // Sanitize
		);
	}
}

if( is_admin() ) $roMarketingSettings = new RoMarketingSettings();
