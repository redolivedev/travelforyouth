<?php

namespace RoMarketing;

function maybe_add_ga_experiment_tag_code(){
	if( isset( $_SESSION['ga_experiment_tag'] ) && $_SESSION['ga_experiment_tag'] ){
		return;
	}

	global $marketingOptions, $mobile_detect;

	if( RO_MARKETING_PRO_ACTIVE ){
		// compare device with target devices
		switch ( $marketingOptions['google_experiment_tag_target_devices'] ) {
			case 'mobile':
				$run_experiment = $mobile_detect->isMobile() && !$mobile_detect->isTablet() ? true : false;
				break;

			case 'tablet':
				$run_experiment = $mobile_detect->isMobile() && $mobile_detect->isTablet() ? true : false;
				break;

			case 'tablet_and_phone':
				$run_experiment = $mobile_detect->isMobile() ? true : false;
				break;

			case 'desktop':
				$run_experiment = ! $mobile_detect->isMobile() ? true : false;
				break;

			default:
				$run_experiment = true;
				break;
		}
	}else{
		$run_experiment = true;
	}

	if( $run_experiment ) :
	?>

		<!-- Google Analytics Content Experiment code -->
		<script data-cfasync="false">function utmx_section(){}function utmx(){}(function(){var
		k='<?php echo $marketingOptions["google_experiment_tag_id"]; ?>',
		d=document,l=d.location,c=d.cookie;
		if(l.search.indexOf('utm_expid='+k)>0)return;
		function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
		indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
		length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
		'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
		'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
		'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
		valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
		'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
		</script><script data-cfasync="false">utmx('url','A/B');</script>
		<!-- End of Google Analytics Content Experiment code -->

	<?php
		$_SESSION['ga_experiment_tag'] = true;
	endif;
}
add_action( 'wp_head', 'RoMarketing\maybe_add_ga_experiment_tag_code', 5 );
