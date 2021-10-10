<?php

namespace RoMarketing;

function show_gtm_admin_warning(){
	?>
		<div class="notice notice-error">
			<p><span style="color:#dc3232;">IMPORTANT</span>: Google Tag Manager in Red Olive RO Marketing plugin is enabled but not working. The "after_opening_body" hook must be added to header.php file. For instructions on where and how to add the hook to your header file, <a target="_blank" href="https://www.redolive.io/google-tag-manager-wordpress-plugin/#hook">CLICK HERE</a>.</p>
		</div>
	<?php
}
add_action( 'admin_notices', 'RoMarketing\show_gtm_admin_warning' );