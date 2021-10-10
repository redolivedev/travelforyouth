<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Plugin Name: RO Marketing
 * Plugin URI: https://www.redolive.io/ro-marketing-free/
 * Description: A plugin to help with marketing
 * Version: 1.24.9
 * Author: Red Olive
 * License: GPLv3
 */

define('RO_MARKETING_URL', plugin_dir_url(__FILE__));
define('RO_MARKETING_DIR', plugin_dir_path(__FILE__));
define('RO_MARKETING_BASENAME', plugin_basename(__FILE__));
define('RO_MARKETING_PRO_DOWNLOAD_URL', 'https://www.redolive.io/red-olive-marketing-pro/');
if (! defined('RO_PLUGIN_SITE_URL')) {
    define('RO_PLUGIN_SITE_URL', 'https://www.redolive.io/');
}

if (in_array('red-olive-marketing-pro/red-olive-marketing-pro.php', get_option('active_plugins'))) {
    define('RO_MARKETING_PRO_ACTIVE', true);
} else {
    define('RO_MARKETING_PRO_ACTIVE', false);
}

global $marketingOptions;
$marketingOptions = get_option('ro_marketing_options');

require_once 'init.php';
