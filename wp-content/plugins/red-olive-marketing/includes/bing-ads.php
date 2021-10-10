<?php

namespace RoMarketing;

function ro_add_bing_ads_script() {
	global $marketingOptions;
	?>

	<!-- Bing ads added by ro-marketing -->
	<script data-cfasync="false">(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"<?php echo $marketingOptions['bing_ads_account_id'] ?>"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>
	<noscript data-cfasync="false"><img src="//bat.bing.com/action/0?ti=<?php echo $marketingOptions['bing_ads_account_id'] ?>&amp;Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>
	<!-- End Bing ads added by ro-marketing -->
	<?php
}
add_action( 'wp_head', 'RoMarketing\ro_add_bing_ads_script' );
