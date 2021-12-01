<?php
namespace RoMarketingPro;

use ROSession;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Public interaction
 */
class PopUpPublic {
	public $pop_up;
	public $pop_ups;
	public $pop_up_meta;
	protected $popups_available = false;
	function __construct() {

        add_action( 'wp', array( $this, 'get_eligible_popups' ), 10 ); //Check if there are any popups to display
        add_action( 'wp', array( $this, 'maybe_set_up_popup' ), 50 ); //If there are popups, run the set up code

        add_action( 'wp_ajax_ro_accept_popup', array( $this, 'accept_popup') );
        add_action( 'wp_ajax_nopriv_ro_accept_popup', array( $this, 'accept_popup') );
        add_action( 'wp_ajax_ro_dismiss_popup', array( $this, 'remove_popup') );
        add_action( 'wp_ajax_nopriv_ro_dismiss_popup', array( $this, 'remove_popup') );
        add_action( 'wp_ajax_ro_check_session_timer', array( $this, 'ro_check_session_timer') );
        add_action( 'wp_ajax_nopriv_ro_check_session_timer', array( $this, 'ro_check_session_timer') );
        add_action( 'wp_ajax_ro_process_comment', array( $this, 'ro_process_comment') );
        add_action( 'wp_ajax_nopriv_ro_process_comment', array( $this, 'ro_process_comment') );
	}

	/**
	 * Get all of the Pop Ups and filter out any that are not eligible for this page.
	 */
	function get_eligible_popups(){
		$this->get_available_popups();
        $this->filter_available_popups();
	}

	/**
	 * Get all of the Pop Ups that are active right now.
	 */
	function get_available_popups(){
		$args = array(
			'post_type' 		=> 'pop-up',
			'posts_per_page' 	=> -1,
			'meta_query' 		=> array(
				'relation' 		=> 'AND',
				array(
					'key'		=> 'start_date',
					'value' 	=> date( 'Ymd' ),
					'compare'	=> '<=',
					'type' 		=> 'DATE'
				),
				array(
					'key'		=> 'end_date',
					'value' 	=> date( 'Ymd' ),
					'compare'	=> '>=',
					'type' 		=> 'DATE'
				),
			)
		);
        $popups = new \WP_Query( $args );

		if( $popups->have_posts() ) {
			$this->pop_ups = $popups->posts;
            $this->popups_available = true;
			return;
		} else {
			$this->popups_available = false;
			return;
		}
	}

	/**
	 * Filter out any Pop Ups that should not be displayed on this page
	 */
	function filter_available_popups(){
		if( ! $this->popups_available ) return;

        global $post;

        // Filter out Pop Ups based on session variable. If they've already been dismissed or accepted, don't show them.
        $pop_ups_after_sesssion_filter = array_filter( $this->pop_ups, function( $pop_up ){
            if (ROSession::arrayHas('ro_popup_accepted', $pop_up->ID)) {
                return false;
            }

            if (ROSession::arrayHas("ro_poup_removed", $pop_up->ID)) {
                return false;
            }

            return true;
        });


		// Filter out Pop Ups based on specific pages settings
		$pop_ups_after_specific_pages_filter = array_filter( $pop_ups_after_sesssion_filter, function( $pop_up ) use( $post ){
			if( get_field( 'display_setting', $pop_up->ID ) === 'specific_pages' ){
				// Check if the current page matches any of the specified pages
				$display_pages = get_field( 'display_pages', $pop_up->ID );
				if( $this->pages_match( $display_pages, 'specified_page', $post ) ) return true;

				// Check if the current page matches any of the specified URLs
				$display_pages_url_string = get_field( 'display_pages_url_string', $pop_up->ID );
				if( $this->strings_match( $display_pages_url_string, 'specified_string', $_SERVER['REQUEST_URI'] ) ) return true;

				// If the current page didn't match a specified page or url, return false
				return false;
			}else{
				return true;
			}
		});

		// Filter out Pop Ups based on excluded pages settings
		$pop_ups_after_excluded_pages_filter = array_filter( $pop_ups_after_specific_pages_filter, function( $pop_up ) use( $post ){
			// Check if the current page matches any of the excluded pages
			$exclude_pages = get_field( 'exclude_pages', $pop_up->ID );
			if( $this->pages_match( $exclude_pages, 'excluded_page', $post ) ) return false;

			// Check if the current page matches any of the excluded URLs
			$exclude_pages_url_string = get_field( 'exclude_pages_url_string', $pop_up->ID );
			if( $this->strings_match( $exclude_pages_url_string, 'excluded_string', $_SERVER['REQUEST_URI'] ) ) return false;

			// If the current page didn't match an excluded page, return true
			return true;
		});

		// Reorder pop_ups array so the values start at 0, then return the first value if there is one
		$pop_ups = array_values( $pop_ups_after_excluded_pages_filter );
		if( isset( $pop_ups[0] ) ){
			$this->pop_up = $pop_ups[0];
		}else{
			$this->pop_up = false;
		}
	}

	function maybe_set_up_popup(){
		if( ! $this->pop_up ) return false;

		$this->set_up_meta_data();
		$this->maybe_set_up_global_delay_timer();

		add_action( 'wp_footer', array( $this, 'add_popup' ), 10 );
		add_action( 'wp_footer', array( $this, 'maybe_set_scroll_distance_variable' ), 20 );
		add_action( 'wp_footer', array( $this, 'ro_popups_custom_css' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'ro_popups_scripts' ) );
	}

	function set_up_meta_data(){
		$post_id = $this->pop_up->ID;

		$this->pop_up_meta = array(
			// Date Range
			'end_date' 					=> get_field( 'end_date', $post_id ),
			'start_date' 				=> get_field( 'start_date', $post_id ),

			// Creation Info
			'url_string' 				=> get_field( 'url_string', $post_id ),
			'pop_up_delay' 				=> get_field( 'pop_up_delay', $post_id ),
			'specific_page' 			=> get_field( 'specific_page', $post_id ),
			'request_email' 		    => get_field( 'request_email', $post_id ),
			'pop_up_trigger' 			=> get_field( 'pop_up_trigger', $post_id ),
			'pop_up_delay_type' 		=> get_field( 'pop_up_delay_type', $post_id ),
			'pop_up_content_type' 		=> get_field( 'pop_up_content_type', $post_id ),
			'scroll_distance_type' 		=> get_field( 'scroll_distance_type', $post_id ),
			'scroll_distance_pixels' 	=> get_field( 'scroll_distance_pixels', $post_id ),
			'scroll_distance_percentage'=> get_field( 'scroll_distance_percentage', $post_id ),

			// Pop Up Box
			'background_color' 			=> get_field( 'background_color', $post_id ),
			'page_overlay_color' 		=> get_field( 'page_overlay_color', $post_id ),
			'default_text_color' 		=> get_field( 'default_text_color', $post_id ),
			'page_overlay_transparency' => get_field( 'page_overlay_transparency', $post_id ),

			// Border
			'border_color' 				=> get_field( 'border_color', $post_id ),
			'border_text_color' 		=> get_field( 'border_text_color', $post_id ),
			'border_text' 				=> get_field( 'border_text', $post_id ),

			// Styles
			'custom_css' 				=> get_field( 'custom_css', $post_id ),
			'custom_css_mobile'			=> get_field( 'custom_css_mobile', $post_id ),

			// Main Content
			'text_color' 				=> get_field( 'text_color', $post_id ),
			'pop_up_image' 				=> get_field( 'pop_up_image', $post_id ),
			'large_text' 				=> get_field( 'large_text', $post_id ),
			'medium_text' 				=> get_field( 'medium_text', $post_id ),

			// Time Remaining
			'time_limit' 				=> get_field( 'time_limit', $post_id ),
			'time_limit_text_color' 	=> get_field( 'time_limit_text_color', $post_id ),

			// Button
			'button_text' 				=> get_field( 'button_text', $post_id ),
			'button_link' 				=> get_field( 'button_link', $post_id ),
			'button_color' 				=> get_field( 'button_color', $post_id ),
			'button_text_color' 		=> get_field( 'button_text_color', $post_id ),

			// Dismiss
			'dismiss_text' 				=> get_field( 'dismiss_text', $post_id ),
            'dismiss_text_color' 		=> get_field( 'dismiss_text_color', $post_id ),

            // Thank you
            'thank_you_page'            => get_field('thank_you_page', $post_id),
            'thank_you_response'        => get_field('thank_you_response', $post_id),
            'notification_email'        => get_field('notification_email', $post_id),
            'message_large_text'        => str_replace("'", "\'", get_field('message_large_text', $post_id) ),
            'message_medium_text'       => str_replace("'", "\'", get_field('message_medium_text', $post_id) )
        );

        //Request email
        if( $this->pop_up_meta['pop_up_content_type'] === 'comment' && $this->pop_up_meta['request_email'] === 'yes' ){
            $this->pop_up_meta['email_field'] = true;
            $this->pop_up_meta['email_accept'] = true;
        }elseif( $this->pop_up_meta['pop_up_content_type'] === 'mailing_list' ){
            $this->pop_up_meta['email_field'] = true;
            $this->pop_up_meta['email_accept'] = false;
        }else{
            $this->pop_up_meta['email_field'] = false;
            $this->pop_up_meta['email_accept'] = false;
        }

        // Button link and class
		if( $this->pop_up_meta['pop_up_content_type'] === 'mailing_list' ){
			$this->pop_up_meta['button_link'] = '#';
			$this->pop_up_meta['button_class'] = 'ro-add-email';
		}elseif( $this->pop_up_meta['pop_up_content_type'] === 'comment' ){
            $this->pop_up_meta['button_link'] = '#';
			$this->pop_up_meta['button_class'] = 'ro-add-comment';
        }else{
			$this->pop_up_meta['button_class'] = 'apply-button';
		}

        // MailChimp
        $this->pop_up_meta['mc_info'] = json_decode( get_field( 'mailchimp_account_information', $post_id ) );

        $this->pop_up_meta['page_overlay_color'] = $this->build_rgba_value();
    }

    function add_popup() {
		global $post;

        if( $this->pop_up_meta['pop_up_content_type'] === 'link' ){
            // page time persistence
            if (ROSession::has("ro_popup_end_time_" . $this->pop_up->ID)) {
                $endTime = ROSession::get("ro_popup_end_time_" . $this->pop_up->ID);
                if ($endTime < strtolower('now')) return false;
            } else {
                $endTime = strtotime( '+' . $this->pop_up_meta['time_limit'] . ' seconds' );
                ROSession::set('ro_pup_end_time_' . $this->pop_up->ID, $endTime);
            }
        }

		ob_start();
		require RO_MARKETING_PRO_DIR . 'includes/pop-up-ads/templates/popup-html.php';
		echo ob_get_clean();
	}


	function maybe_set_up_global_delay_timer(){
		if( $this->pop_up_meta['pop_up_trigger'] !== 'delay' ) return;
		if( $this->pop_up_meta['pop_up_delay_type'] !== 'session' ) return;
		if (ROSession::has("ro_popup_delay_time_" . $this->pop_up->ID)) return;

		if( ! $this->pop_up_meta['pop_up_delay'] ) $this->pop_up_meta['pop_up_delay'] = 0;

		ROSession::set('ro_popup_delay_time_' . $this->pop_up->ID, strtotime( '+' . $this->pop_up_meta['pop_up_delay'] . ' seconds' ));
	}

	function maybe_set_scroll_distance_variable(){
		if( $this->pop_up_meta['pop_up_trigger'] !== 'scroll' ) return;
		if( ! $this->pop_up_meta['scroll_distance_pixels'] ) $this->pop_up_meta['scroll_distance_pixels'] = 0;
		if( ! $this->pop_up_meta['scroll_distance_percentage'] ) $this->pop_up_meta['scroll_distance_percentage'] = 0;

		?>
		<script>
			var roPopUpScrollDistance = <?php echo $this->pop_up_meta['scroll_distance_pixels']; ?>;
			var roPopUpScrollPercentage = <?php echo $this->pop_up_meta['scroll_distance_percentage']; ?>;
		</script>
		<?php
	}

	function build_rgba_value(){
		list( $r, $g, $b ) = sscanf( $this->pop_up_meta['page_overlay_color'], "#%02x%02x%02x" );

		return $r . ', ' . $g . ', ' . $b . ', ' . (float)( $this->pop_up_meta['page_overlay_transparency'] * .01 );
	}

	function add_zero( $number ) {
		if( $number < 10 ) {
			$number = '0' . $number;
		}
		return $number;
	}

	/**
	 * Checks to see if the current_page matches any of the pages in the specified_pages array
	 */
	function pages_match( $specified_pages, $value_index, $current_page ){
		if( ! $specified_pages || ! is_array( $specified_pages ) ) return false;

		$matching_pages = array_filter( $specified_pages, function( $specified_page ) use( $current_page, $value_index ){
			return $specified_page[ $value_index ] === $current_page->ID;
		});

		return ( ! empty( $matching_pages ) );
	}

	/**
	 * Checks to see if the current_page_url contains the specified_url_string
	 */
	function strings_match( $specified_urls, $value_index, $current_url ){
		if( ! $specified_urls || ! is_array( $specified_urls ) ) return false;

		$matching_strings = array_filter( $specified_urls, function( $specified_url ) use( $current_url, $value_index ){

			// If the specified URL is just an empty string, return false. It should not be allowed to match the current URL.
			if( ! $specified_url[ $value_index ] ) return false;

			// Use '/' as the second argument of preg_quote to make sure it escapes forward slashes
			return preg_match( '/' . preg_quote( $specified_url[ $value_index ], '/' ) . '/', $current_url );
		});

		return ( ! empty( $matching_strings ) );
	}

	function ro_popups_custom_css() {
		wp_register_script( 'ro_pop_up_ads_css', RO_MARKETING_PRO_URL . 'assets/js/pop-up-css.js', array('jquery') );
		wp_localize_script( 'ro_pop_up_ads_css', 'pop_up_meta', $this->pop_up_meta );
		wp_enqueue_script( 'ro_pop_up_ads_css' );
	}

	function ro_popups_scripts() {
		wp_enqueue_script( 'glio', RO_MARKETING_PRO_URL.'assets/js/glio/glio.js', array(), null, true );
		wp_enqueue_style( 'fancybox', RO_MARKETING_PRO_URL . 'assets/js/fancybox/jquery.fancybox.css' );
		wp_enqueue_script(
            'fancybox',
            RO_MARKETING_PRO_URL . 'assets/js/fancybox/jquery.fancybox.pack.js',
            array('jquery'),
            '2.1.5',
            true
        );
	}

	function format_time( $endTime ) {
		$currentTime	= strtotime('now');
		$diff 			= $endTime - $currentTime;

		$hours 			= $this->add_zero( floor( ( $diff / 60 / 60 ) % 60 ) );
		$minutes 		= $this->add_zero( floor( ( $diff / 60 ) % 60 ) );
		$seconds 		= $this->add_zero( floor( $diff % 60 ) );

		$formattedTime 	= $hours . ':' . $minutes . ':' . $seconds;
		return $formattedTime;
	}

	public function accept_popup() {
        if( ! isset( $_POST['pop_up_id'] ) ){
            wp_send_json_error( 'Missing Pop Up ID' );
        }

        ROSession::arraySetValue('ro_popup_accepted', $_POST['pop_up_id']);

		wp_send_json_success( 'Pop Up Accepted' );
	}

	public function remove_popup() {
        if( ! isset( $_POST['pop_up_id'] ) ){
            wp_send_json_error( 'Missing Pop Up ID' );
        }

        ROSession::arraySetValue('ro_popup_accepted', $_POST['pop_up_id']);

		wp_send_json_success( 'Pop Up Removed' );
	}

	public function ro_check_session_timer(){
		if( strtotime( 'now' ) >= ROSession::get("ro_popup_delay_time_" . $this->pop_up->ID)){
			wp_send_json_success(array('now' => strtotime('now'), 'timer' => ROSession::get("ro_popup_delay_time_" . $this->pop_up->ID)));
		}else{
            wp_send_json_error(array('now' => strtotime('now'), 'timer' => ROSession::get("ro_popup_delay_time_" . $this->pop_up->ID)));
		}
    }

    public function ro_process_comment(){
        $this->send_notification_emails();

        $post_args = array(
            'post_status'   => 'publish',
            'post_type' => 'pop-up-comment',
            'post_content'  => $_POST['comment'],
            'post_title'    => $_POST['email'] ? $_POST['email'] : 'Anonymous'
        );

        wp_send_json_success( wp_insert_post( $post_args ) );
    }

    protected function send_notification_emails(){
        if( ! isset( $_POST['notification_email'] ) || ! $_POST['notification_email'] ) return;

        $clean_emails = str_replace( ' ', '', $_POST['notification_email'] );
        $email_array = explode( ',', $clean_emails );

        foreach( $email_array as $email ){
            if( ! is_email( $email ) ) continue;

            $headers = "From: " . get_bloginfo('name') . " <" . get_bloginfo('admin_email') . ">" . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();

            $subject = 'New Comment on ' . get_bloginfo('name');
            $message = $_POST['comment'] . '<br/><br/><br/><i>--Powered By RO Marketing Pro Pop Up Comments--</i>';

            if( $_POST['email'] ){
                $subject .= ' from ' . $_POST['email'];
                $message = 'From ' . $_POST['email'] . ':<br/><br/>' . $message;
            }

            wp_mail( $email, $subject, $message, $headers );
        }
    }
}

function call_ro_popup_public() {
	$popUp = new PopUpPublic();
}
add_action( 'plugins_loaded', 'RoMarketingPro\call_ro_popup_public' );
