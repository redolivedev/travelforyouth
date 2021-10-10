<?php

namespace RoMarketing;

function ro_add_remarketing() {
	global $marketingOptions;

	// Google Standard Remarketing
	echo '
	<!-- Google Code for Remarketing Tag added by ro-marketing -->
	<script type="text/javascript">
	/* <![CDATA[ */
	'; echo $marketingOptions['dynamic_remarketing_conversion_id'] ? 'var google_conversion_id = ' . $marketingOptions['dynamic_remarketing_conversion_id'] . ';' : ''; echo '
	var google_custom_params = window.google_tag_params;
	var google_remarketing_only = true'; echo ';
	/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
	<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/'. $marketingOptions['dynamic_remarketing_conversion_id'] .'/?value=0&amp;guid=ON&amp;script=0"/>
	</div>
	</noscript>

	<style>
	iframe[title="Google conversion frame"] { display: none }
	</style>
	<!-- End Google Code for Remarketing Tag added by ro-marketing -->
	';
}
add_action( 'wp_footer', 'RoMarketing\ro_add_remarketing' );
