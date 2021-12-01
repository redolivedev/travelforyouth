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

//   gsap.to(".title", {
// 	yPercent: -100,
// 	ease: "none",
// 	scrollTrigger: {
// 	  trigger: ".title",
// 	  // start: "top bottom", // the default values
// 	  // end: "bottom top",
// 	  scrub: true
// 	}, 
//   });


 gsap.set("#dot", { scale: 0.7, autoAlpha: 1 });
// gsap.set("#dot", {transformOrigin: "50% 50%"});

gsap.to("#dot", {
  scrollTrigger: {
    trigger: "#motionPath",
	// start: "top",
    // end: "bottom",
    scrub: 1,
    markers: false,
    // onUpdate: self => {
    //   gsap.to("#tractor", {rotation: () => self.direction === 1 ? 0 : -180, overwrite: 'auto'});
    // }
  },
  duration: 3,
  ease: "none",
  immediateRender: true,
  motionPath: {
    path: "#path1",
    align: "#path1",
	start:1,
	end:0,
    alignOrigin: [0.5, 0.5],
	autoRotate:true
  }
}).reverse();


gsap.to("#dot2", {
	scrollTrigger: {
	  trigger: "#motionPath2",
	  scrub: 1,
	  markers: false,
	},
	duration: 3,
	ease: "none",
	immediateRender: true,
	motionPath: {
	  path: "#path2",
	  align: "#path2",
	  start:1,
	  end:0,
	  alignOrigin: [0.5, 0.5],
	  autoRotate:true
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
	$('#test').owlCarousel({
		loop:true,
		margin:0,
		nav:true,
		animateOut: 'fadeOut',
		animateIn: 'fadeIn',
		items:1,
		autoHeight:true,
		navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']
	});
	//var $owl = $('#mobilePlaces');
	$('#mobilePlaces').owlCarousel({
		loop:false,
		nav:true,
		autoWidth:true,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				stagePadding: 0,
				margin:0,
			},
			760:{
				items:2,
				stagePadding: 40,
				margin:40,
				
			},
			1200:{
				items:2,
				stagePadding: 80,
				margin:0,
				
			},
			1400:{
				items:2,
				stagePadding: 60,
			},
			
		},
		navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']
	});
	$('#mobilePlaces2').owlCarousel({
		loop:false,
		nav:true,
		autoWidth:true,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				stagePadding: 0,
				margin:0,
			},
			760:{
				items:2,
				stagePadding: 40,
				margin:40,
				
			},
			1200:{
				items:2,
				stagePadding: 80,
				margin:0,
				
			},
			1400:{
				items:2,
				stagePadding: 60,
			},
			
		},
		navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']
	});
	// $owl.on('mousewheel', '.owl-stage', function (e) {
	// 	if (e.deltaY>0) {
	// 		$owl.trigger('next.owl');
	// 	} else {
	// 		$owl.trigger('prev.owl');
	// 	}
	// 	e.preventDefault();
	// });


	$(".twoW").click(function () {
		$('.oneW').removeClass('active');
		$(this).addClass('active');
        $("#mobilePlaces").addClass('active');
		$("#mobilePlaces2").removeClass('active');
    });

    $(".oneW").click(function () {
		$('.twoW').removeClass('active');
		$(this).addClass('active');
        $("#mobilePlaces2").addClass('active');
		$("#mobilePlaces").removeClass('active');
    });
	
}); 

