<?php

namespace RoMarketing;

if( ! function_exists( 'RoMarketing\ro_add_crazy_egg' ) ){
	function ro_add_crazy_egg(){

		global $marketingOptions;

		$crazy_egg_id = $marketingOptions['add_crazy_egg_tag_id'];
		$id_with_slash = substr_replace($crazy_egg_id, '/', 4, 0); //Add a forward slash after the 4th character of the account number

		?>
			<!-- Crazy Egg script added by ro-marketing -->
			<script data-cfasync="false" type="text/javascript">
			setTimeout(function(){var a=document.createElement("script");
			var b=document.getElementsByTagName("script")[0];
			a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/<?php echo $id_with_slash; ?>.js?"+Math.floor(new Date().getTime()/3600000);
			a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
			</script>
			<!-- End Crazy Egg script -->
		<?php
	}
	add_action( 'wp_head', 'RoMarketing\ro_add_crazy_egg' );
}