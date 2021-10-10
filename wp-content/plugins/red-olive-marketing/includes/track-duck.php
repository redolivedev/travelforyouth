<?php

namespace RoMarketing;

if( ! is_admin() ) {
	if( ! function_exists( 'RoMarketing\ro_add_track_duck' ) ) {
		function ro_add_track_duck () {
			global $marketingOptions;
			if( $marketingOptions['track_duck_id'] ) :
			?>
			<!-- Track Duck script added by ro-marketing -->
			<script src="//cdn.trackduck.com/toolbar/prod/td.js" async data-trackduck-id="<?php echo $marketingOptions['track_duck_id'] ?>"></script>
			<!-- End Track Duck script added by ro-marketing -->
			<?php
			endif;
		}
		add_action ( 'wp_footer', 'RoMarketing\ro_add_track_duck', 1 );
	}
}
