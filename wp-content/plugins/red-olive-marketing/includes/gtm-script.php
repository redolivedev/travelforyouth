<?php

namespace RoMarketing;

function ro_add_gtm_head_script() {
	global $marketingOptions;
	?>
		<!-- Google Tag Manager head script added by ro-marketing -->
		<script data-cfasync="false">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer',<?php echo '\'' . $marketingOptions['google_tag_manager_account_id'] . '\'';?>);
		</script>
		<!-- End Google Tag Manager head script added by ro-marketing -->
	<?php
}
add_filter( 'wp_head', 'RoMarketing\ro_add_gtm_head_script' );

function ro_add_gtm_body_script() {
	global $marketingOptions;
	?>
		<!-- Google Tag Manager body script added by ro-marketing -->
		<noscript data-cfasync="false"><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $marketingOptions['google_tag_manager_account_id'] ?>"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager body script added by ro-marketing -->
	<?php
}
add_filter( 'after_opening_body', 'RoMarketing\ro_add_gtm_body_script' );
