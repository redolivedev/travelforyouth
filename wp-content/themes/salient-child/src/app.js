window.$ = $;

// or all tools are exported from the "all" file (excluding bonus plugins):
import { gsap, ScrollTrigger, Draggable, MotionPathPlugin } from "gsap/all";
// don't forget to register plugins
gsap.registerPlugin(ScrollTrigger, Draggable, MotionPathPlugin); 





gsap.utils.toArray(".panel").forEach((panel, i) => {
  ScrollTrigger.create({
    trigger: panel,
    toggleClass: "turnedOn",
    scrub: 1,
    start: "top", 
    pin: true, 
    pinSpacing: false,
    markers:false,
  });
});



gsap.timeline({
    scrollTrigger: {
      trigger: '.hero',
      scrub: true
    }
  })
  .to('.blackOpac', {
	duration: 0.5,
    opacity: 0
  })
  .to('.blackOpac', {
	duration: 0.5,
    opacity: 1
  });

  gsap.to(".title", {
	yPercent: -100,
	ease: "none",
	scrollTrigger: {
	  trigger: ".title",
	  // start: "top bottom", // the default values
	  // end: "bottom top",
	  scrub: true
	}, 
  });







 let scroll_tl = gsap.timeline({
	scrollTrigger: {
		trigger: '.factsContainer',
		start: "top center",
		// pin: true,
		scrub: true,
		end: "+=300",
		// markers: true,
	}
}),
	facts = [...document.querySelectorAll('.fact')]
scroll_tl.to('.factsContainer h2', {
	scale: 1,
	duration: 1,
	ease: "slow"
})
scroll_tl.to(facts, {
	xPercent: -85 * (facts.length - 1),
	scrollTrigger: {
		trigger: ".factsContainer_sm",
		start: "center center",
		pin: true,
		// horizontal: true,
		// pinSpacing:false,
		// markers: true,
		scrub: 1,
		snap: 1 / (facts.length - 1),
		// base vertical scrolling on how wide the container is so it feels more natural.
		// end: () => `+=${smallFactsContainer.offsetWidth}`
		end: () => `+=18406`
	}
});

var tooltipSpan = document.getElementById('play');

window.onmousemove = function (e) {
    var x = e.clientX,
        y = e.clientY;
    tooltipSpan.style.top = (y -240) + 'px';
    tooltipSpan.style.left = (x -240) + 'px';
};





jQuery(document).ready(function($) {
	$('.owl-carousel').owlCarousel({
		loop:true,
		margin:0,
		nav:true,
		items:1,
		navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']
	})
}); 

