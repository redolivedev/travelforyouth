<?php

namespace RoMarketingPro;

// check to see if the hidden input exists in submission
function ro_preprocess_new_comment($commentdata) {
	// if the request is coming from admin
	if( isset( $_POST['action'] ) && $_POST['action'] == 'replyto-comment' ) return $commentdata;

	if( !isset($_POST['is_valid'])) {
		die('Your comment has been posted');
	}
	return $commentdata;
}
add_action('preprocess_comment', 'RoMarketingPro\ro_preprocess_new_comment');

// add hidden field to form
function ro_add_field_to_comment_form() {
	?>
	<script>
	(function($) {
		/*
		 * add hidden field to comment form for spam detection
		 */
		$('.comment-form .form-submit').prepend('<input type="hidden" name="is_valid" value="1" />');
		$('#commentform [type="submit"]').prepend('<input type="hidden" name="is_valid" value="1" />');
	})(jQuery);
	</script>
	<?php
}
add_action( 'wp_footer', 'RoMarketingPro\ro_add_field_to_comment_form', 99999 );
