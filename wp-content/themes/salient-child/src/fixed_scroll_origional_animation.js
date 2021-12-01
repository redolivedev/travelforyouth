<div class="factsContainer">
<?php get_template_part( 'map' ); ?>
	<div class="factsContainer_sm">
		<!-- <div class="fact first">
			 <h3 class="t-group">Two Week<br/>Trips</h3>
			<div class="place none">
			<p></p>
			</div>
		</div> -->
		<?php while( have_rows('places') ): the_row();
			 if( get_sub_field('week') == 'two' ):
			$week = get_sub_field('week'); ?>
			<div class="fact week-<?php echo $week; ?>">
				<?php if(get_sub_field('place_page_url')): ?>
					<a href="<?php the_sub_field('place_page_url'); ?>" class="place">
				<?php else: ?>
					<div class="place">
				<?php endif; ?>
					<span><?php the_sub_field('sm_yellow'); ?></span>
					<h3><?php the_sub_field('name'); ?></h3>
					<?php if(get_sub_field('highlight_flag')): ?>
						<div class="flag"><?php the_sub_field('highlight_flag'); ?></div>
					<?php endif; ?>    
					<div class="place-img">
						<img src="<?php the_sub_field('place_image'); ?>">
					</div>
					<div class="hover-text">
						<p class="yellow">DATES</p>
						<p><?php the_sub_field('dates'); ?></p>
						<p class="yellow">length</p>
						<p><?php the_sub_field('length'); ?></p>
						<p class="yellow"><?php the_sub_field('social_cause_title'); ?></p>
						<p><?php the_sub_field('social_cause'); ?></p>
						<p class="yellow">Highlights</p>
						<p><?php the_sub_field('highlights'); ?></p>
					</div>
				<?php if(get_sub_field('place_page_url')): ?>
					</a>
				<?php else: ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; //close week?>    
		<?php endwhile; ?>   
		<?php // get_template_part( 'locations' ); ?>
		
	<div class="fact">
		<div class="place none">
			<p></p>
		</div>
	</div>
</div><!-- close SMl -->


<!-- <div class="socialContainer">
	<h3>YO</h3>
</div> -->

</div> <!-- close Fast L -->



<div class="factsContainer2">
	<div class="factsContainer_sm2">
	  <div class="fact2 first">
			 <h3 class="t-group">One Week<br/>Trips</h3>
			<div class="place none">
			<p></p>
			</div>
		</div>
		<?php while( have_rows('places') ): the_row();
			 if( get_sub_field('week') == 'one' ):
			$week = get_sub_field('week'); ?>
			<div class="fact2 week-<?php echo $week; ?>">
				<?php if(get_sub_field('place_page_url')): ?>
					<a href="<?php the_sub_field('place_page_url'); ?>" class="place">
				<?php else: ?>
					<div class="place">
				<?php endif; ?>
					<span><?php the_sub_field('sm_yellow'); ?></span>
					<h3><?php the_sub_field('name'); ?></h3>
					<?php if(get_sub_field('highlight_flag')): ?>
						<div class="flag"><?php the_sub_field('highlight_flag'); ?></div>
					<?php endif; ?>    
					<div class="place-img">
						<img src="<?php the_sub_field('place_image'); ?>">
					</div>
					<div class="hover-text">
						<p class="yellow">DATES</p>
						<p><?php the_sub_field('dates'); ?></p>
						<p class="yellow">length</p>
						<p><?php the_sub_field('length'); ?></p>
						<p class="yellow"><?php the_sub_field('social_cause_title'); ?></p>
						<p><?php the_sub_field('social_cause'); ?></p>
						<p class="yellow">Highlights</p>
						<p><?php the_sub_field('highlights'); ?></p>
					</div>
				<?php if(get_sub_field('place_page_url')): ?>
					</a>
				<?php else: ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; //close week?>    
		<?php endwhile; ?>   
		<?php // get_template_part( 'locations' ); ?>
		
	<div class="fact2">
		<div class="place none">
			<p></p>
		</div>
	</div>
</div><!-- close SMl -->


<!-- <div class="socialContainer">
	<h3>YO</h3>
</div> -->

</div> <!-- close Fast L -->







 let scroll_tl = gsap.timeline({
	scrollTrigger: {
		trigger: '.factsContainer',
		start: "top center",
		// pin: true,
		scrub: true,
		// end: "+=50",
		// markers: true,
	}
}),
	facts = [...document.querySelectorAll('.fact')]
	var smallFactsContainer = document.querySelectorAll('.factsContainer_sm');
    var ww = smallFactsContainer.innerWidth;
    // wh = window.innerHeight;
// scroll_tl.to('.factsContainer h2', {
// 	scale: 1.3,
// 	duration: 1,
// 	ease: "slow"
// })
scroll_tl.to(facts, {
	xPercent: -85 * (facts.length - 1.5),
	scrollTrigger: {
		trigger: ".factsContainer_sm",
		start: "center center",
		pin: true,
		// horizontal: true,
		// pinSpacing:false,
		// markers: true,
		scrub: 1,
		//snap: 1 / (facts.length - 1),
		// base vertical scrolling on how wide the container is so it feels more natural.
		//end: () => `+=${smallFactsContainer.ww}`,
		//end: () => `+=800`
	}
});




let scroll_tl2 = gsap.timeline({
	scrollTrigger: {
		trigger: '.factsContainer2',
		start: "top center",
		// pin: true,
		scrub: true,
		// end: "+=50",
		// markers: true,
	}
}),
	facts2 = [...document.querySelectorAll('.fact2')]
	var smallFactsContainer2 = document.querySelectorAll('.factsContainer_sm2');
    var ww = smallFactsContainer2.innerWidth;
    // wh = window.innerHeight;
// scroll_tl.to('.factsContainer h2', {
// 	scale: 1.3,
// 	duration: 1,
// 	ease: "slow"
// })
scroll_tl2.to(facts2, {
	xPercent: -85 * (facts2.length - 1.5),
	scrollTrigger: {
		trigger: ".factsContainer_sm2",
		start: "center center",
		pin: true,
		// horizontal: true,
		// pinSpacing:false,
		// markers: true,
		scrub: 1,
		//snap: 1 / (facts.length - 1),
		// base vertical scrolling on how wide the container is so it feels more natural.
		//end: () => `+=${smallFactsContainer.ww}`,
		//end: () => `+=800`
	}
});


