<?php

namespace RoMarketing;

function ro_facebook_pixel_script() {
	global $marketingOptions;
	?>

		<!-- Facebook Pixel Code Added by RO Marketing -->
		<script data-cfasync="false">
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
		document,'script','https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '<?php echo $marketingOptions["ro_facebook_pixel_id"]; ?>');
		fbq('track', 'PageView');
		</script>
		<noscript data-cfasync="false"><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=<?php echo $marketingOptions["ro_facebook_pixel_id"]; ?>&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->
	<?php
}
add_action( 'wp_head', 'RoMarketing\ro_facebook_pixel_script' );