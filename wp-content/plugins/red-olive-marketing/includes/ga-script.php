<?php

namespace RoMarketing;

function ro_add_ga_script()
{
    global $marketingOptions;

    //If the Google Optimize setting is enabled and has an ID, add it before the Google Analytics tag
    if (isset($marketingOptions['google_optimize_tag']) && $marketingOptions['google_optimize_tag'] && $marketingOptions['google_optimize_tag_id']) {
        $google_optimize_tag_id = $marketingOptions['google_optimize_tag_id']; ?>
			<!-- Google Optimize Page Hiding Tag added by ro-marketing -->
			<style>.async-hide { opacity: 0 !important} </style>
			<script data-cfasync="false">(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
			h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
			(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
			})(window,document.documentElement,'async-hide','dataLayer',4000,
			{'<?php echo $google_optimize_tag_id; ?>':true});</script>
			<!-- End Google Optimize Page Hiding Tag added by ro-marketing -->
		<?php
    } ?>
	<!-- Google Analytics added by ro-marketing -->
	<script data-cfasync="false">
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', '<?php echo $marketingOptions['google_analytics_account_id'] ?>', 'auto');

	<?php if (! empty($google_optimize_tag_id)): ?>
		//Add the Google Optimize tag ID, if it's available
		ga('require', '<?php echo $google_optimize_tag_id; ?>');
	<?php endif; ?>

	<?php if (is_404()) : ?>
		<?php if (isset($marketingOptions['google_analytics_404_dimension_number']) && $marketingOptions['google_analytics_404_dimension_number']) : ?>
			ga('set', 'dimension<?php echo $marketingOptions['google_analytics_404_dimension_number'] ?>', window.location.href );
		<?php endif; ?>
	<?php endif; ?>
	ga('send', 'pageview');

	</script>
	<!-- End Google Analytics added by ro-marketing -->
	<?php
}
add_action('wp_head', 'RoMarketing\ro_add_ga_script');


function ro_add_ga_404_dimension()
{
    global $marketingOptions;
    if (is_404()) :

    endif;
}
add_action('wp_footer', 'RoMarketing\ro_add_ga_404_dimension');
