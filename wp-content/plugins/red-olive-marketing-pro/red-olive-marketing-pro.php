<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Plugin Name: RO Marketing Pro
 * Plugin URI: https://www.redolive.io/red-olive-marketing-pro/
 * Description: A plugin to help with marketing
 * Version: 1.5.1
 * Author: Red Olive
 * License: Proprietary
 */

define('RO_MARKETING_PRO_VERSION', '1.5.1');  /** NOTE: Make sure to update this version number too **/
define('RO_MARKETING_PRO_FILE', __FILE__);
define('RO_MARKETING_PRO_URL', plugin_dir_url(__FILE__));
define('RO_MARKETING_PRO_DIR', plugin_dir_path(__FILE__));
define('RO_MARKETING_PRO_BASENAME', plugin_basename(__FILE__));

if (function_exists('get_field') && ! function_exists('acf_add_options_page')) {
    function ro_show_acf_version_admin_warning()
    {
        ?>
			<div class="notice notice-error">
				<p>
					<span style="color:#dc3232;">IMPORTANT</span>:
					Red Olive Marketing Pro cannot run unless the Free version of Advanced
					Custom Fields is deactivated. Please deactivate Advanced Custom Fields
					and any plugins that include the Free version of Advanced Custom Fields
				</p>
			</div>
		<?php
    }
    add_action('admin_notices', 'ro_show_acf_version_admin_warning');
} else {
    require_once RO_MARKETING_PRO_DIR . 'init.php';
    require_once RO_MARKETING_PRO_DIR . 'vendor/acf-mailchimp/acf-mailchimp.php';
}
