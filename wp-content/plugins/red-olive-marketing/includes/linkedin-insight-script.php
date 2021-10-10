<?php

namespace RoMarketing;

function ro_add_linkedin_insight_tag() {
	global $marketingOptions;

	// LinkedIn Insight Tag
	?>
		<!-- LinkedIn Insight Tag added by ro-marketing -->
		<script type="text/javascript">
			_linkedin_data_partner_id = "<?php echo $marketingOptions['linkedin_insight_partner_id']; ?>";
		</script>
		<script type="text/javascript">
			(function(){var s = document.getElementsByTagName("script")[0];
			var b = document.createElement("script");
			b.type = "text/javascript";b.async = true;
			b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
			s.parentNode.insertBefore(b, s);})();
		</script>
		<noscript>
			<img height="1" width="1" style="display:none;" alt="" src="https://dc.ads.linkedin.com/collect/?pid=<?php echo $marketingOptions['linkedin_insight_partner_id']; ?>&fmt=gif" />
		</noscript>
		<!-- End LinkedIn Insight Tag added by ro-marketing -->
	<?php
}
add_action( 'wp_footer', 'RoMarketing\ro_add_linkedin_insight_tag' );