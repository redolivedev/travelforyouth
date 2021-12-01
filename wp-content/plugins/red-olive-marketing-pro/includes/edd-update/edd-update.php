<?php
if (! defined('REDOLIVE_IO_SITE_URL')) {
    define('REDOLIVE_IO_SITE_URL', 'https://www.redolive.io');
}
define('RED_OLIVE_MARKETING_PRO', 'Red Olive Marketing PRO');
define('RED_OLIVE_MARKETING_PRO_LICENSE_PAGE', 'red-olive');

if (! class_exists('RoMarketingPro\EDD_SL_Plugin_Updater')) {
    // load our custom updater
    include RO_MARKETING_PRO_DIR . 'includes/edd-update/EDD_SL_Plugin_Updater.php';
}

function red_olive_marketing_pro_updater()
{

    // Get the options
    global $marketingOptions;

    // retrieve our license key from the DB
    $license_key = isset($marketingOptions['marketing_license_key']) ? trim($marketingOptions['marketing_license_key']) : false;

    if (! $license_key) {
        return;
    }

    // setup the updater
    $red_olive_updater = new RoMarketingPro\EDD_SL_Plugin_Updater(
        REDOLIVE_IO_SITE_URL,
        RO_MARKETING_PRO_FILE,
        array(
            'version' 	=> RO_MARKETING_PRO_VERSION,
            'license' 	=> $license_key,
            'item_name' => RED_OLIVE_MARKETING_PRO,
            'author' 	=> 'Red Olive',
            'beta'		=> false
        )
    );
}
add_action('admin_init', 'red_olive_marketing_pro_updater', 0);


/************************************
* this illustrates how to activate
* a license key
*************************************/

function red_olive_marketing_pro_activate_license()
{

    // listen for our activate button to be clicked
    if (isset($_POST['red_olive_marketing_pro_license_activate'])) {

        // run a quick security check
        if (! check_admin_referer('red_olive_marketing_pro_nonce', 'red_olive_marketing_pro_nonce')) {
            return;
        } // get out if we didn't click the Activate button

        // Get the options
        global $marketingOptions;

        // retrieve our license key from the DB
        $license = trim($marketingOptions['marketing_license_key']);

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => $license,
            'item_name'  => urlencode(RED_OLIVE_MARKETING_PRO), // the name of our product in EDD
            'url'        => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post(REDOLIVE_IO_SITE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ));

        // make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {
                switch ($license_data->error) {

                    case 'expired':

                        $message = sprintf(
                            __('Your license key expired on %s.'),
                            date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;

                    case 'revoked':

                        $message = __('Your license key has been disabled.');
                        break;

                    case 'missing':

                        $message = __('Invalid license.');
                        break;

                    case 'invalid':
                    case 'site_inactive':

                        $message = __('Your license is not active for this URL.');
                        break;

                    case 'item_name_mismatch':

                        $message = sprintf(__('This appears to be an invalid license key for %s.'), RED_OLIVE_MARKETING_PRO);
                        break;

                    case 'no_activations_left':

                        $message = __('Your license key has reached its activation limit.');
                        break;

                    default:

                        $message = __('An error occurred, please try again.');
                        break;
                }
            }
        }

        // Check if anything passed on a message constituting a failure
        if (! empty($message)) {
            $base_url = admin_url('admin.php?page=' . RED_OLIVE_MARKETING_PRO_LICENSE_PAGE);
            $redirect = add_query_arg(array( 'sl_activation' => 'false', 'message' => urlencode($message) ), $base_url);

            wp_redirect($redirect);
            exit();
        }

        // $license_data->license will be either "valid" or "invalid"

        update_option('red_olive_marketing_pro_license_status', $license_data->license);
        wp_redirect(admin_url('admin.php?page=' . RED_OLIVE_MARKETING_PRO_LICENSE_PAGE));
        exit();
    }
}
add_action('admin_init', 'red_olive_marketing_pro_activate_license');


function red_olive_marketing_pro_deactivate_license()
{

    // listen for our activate button to be clicked
    if (isset($_POST['red_olive_marketing_pro_license_deactivate'])) {

        // run a quick security check
        if (! check_admin_referer('red_olive_marketing_pro_nonce', 'red_olive_marketing_pro_nonce')) {
            return;
        } // get out if we didn't click the Activate button

        // Get the options
        global $marketingOptions;

        // retrieve our license key from the DB
        $license = trim($marketingOptions['marketing_license_key']);

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license'    => $license,
            'item_name'  => urlencode(RED_OLIVE_MARKETING_PRO), // the name of our product in EDD
            'url'        => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post(REDOLIVE_IO_SITE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ));

        // make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
            }

            $base_url = admin_url('admin.php?page=' . RED_OLIVE_MARKETING_PRO_LICENSE_PAGE);
            $redirect = add_query_arg(array( 'sl_activation' => 'false', 'message' => urlencode($message) ), $base_url);

            wp_redirect($redirect);
            exit();
        }

        // decode the license data
        $license_data = json_decode(wp_remote_retrieve_body($response));

        // $license_data->license will be either "deactivated" or "failed"
        if ($license_data->license == 'deactivated' || $license_data->license == 'failed') {
            delete_option('red_olive_marketing_pro_license_status');
        }

        wp_redirect(admin_url('admin.php?page=' . RED_OLIVE_MARKETING_PRO_LICENSE_PAGE));
        exit();
    }
}
add_action('admin_init', 'red_olive_marketing_pro_deactivate_license');

function should_check_license()
{
    if (get_transient('romp_license_last_checked')) {
        return false;
    }
    set_transient('romp_license_last_checked', time(), 4 * HOUR_IN_SECONDS);
    return true;
}

function red_olive_marketing_pro_check_license()
{
    if (!should_check_license()) {
        return true;
    }

    global $wp_version;

    // Get the options
    global $marketingOptions;

    // retrieve our license key from the DB
    $license = trim($marketingOptions['marketing_license_key']);

    $api_params = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode(RED_OLIVE_MARKETING_PRO),
        'url'       => home_url()
    );

    // Call the custom API.
    $response = wp_remote_post(REDOLIVE_IO_SITE_URL, array( 'timeout' => 15, 'body' => $api_params ));

    if (is_wp_error($response)) {
        return false;
    }

    $license_data = json_decode(wp_remote_retrieve_body($response));
    
    if ($license_data->license !== 'valid') {
        delete_option('red_olive_marketing_pro_license_status');
    }
}
add_action('admin_init', 'red_olive_marketing_pro_check_license');

function red_olive_marketing_pro_admin_notices()
{
    if (isset($_GET['sl_activation']) && ! empty($_GET['message'])) {
        switch ($_GET['sl_activation']) {

            case 'false':
                $message = urldecode($_GET['message']);
                ?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
                break;

            case 'true':
            default:
                // Developers can put a custom success message here for when activation is successful if they would like.
                break;

        }
    }
}
add_action('admin_notices', 'red_olive_marketing_pro_admin_notices');
