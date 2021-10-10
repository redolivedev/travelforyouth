<?php

namespace RoMarketing;

function ro_add_call_tracking_script() {
	global $marketingOptions;
	?>
	<!-- Call tracking added by ro-marketing -->
	<script async src="//<?php echo $marketingOptions['call_tracking_account_id'] ?>.tctm.co/t.js"></script>
	<!-- End call tracking added by ro-marketing -->
	<?php
}
add_action( 'wp_head', 'RoMarketing\ro_add_call_tracking_script' );
