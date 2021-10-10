<?php

namespace RoMarketing;

function ro_add_live_chat_script(){
	global $marketingOptions;
	$license_number = $marketingOptions['live_chat_license_number'];

	?>
	<!-- Start of LiveChat (www.livechatinc.com) code -->
	<script type="text/javascript">
	window.__lc = window.__lc || {};
	window.__lc.license = <?php echo $license_number; ?>;
	(function() {
	  var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
	  lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
	  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
	})();
	</script>
	<!-- End of LiveChat code -->
	<?php
}
add_action( 'wp_footer', 'RoMarketing\ro_add_live_chat_script' );