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


//  gsap.set("#dot", { scale: 0.7, autoAlpha: 1 });
// gsap.set("#dot", {transformOrigin: "50% 50%"});

gsap.to("#dot", {
  scrollTrigger: {
	start: 'top top',
	end: '+=447px',
    scrub: 1,
    markers: false,
    // onUpdate: self => {
    //   gsap.to("#tractor", {rotation: () => self.direction === 1 ? 0 : -180, overwrite: 'auto'});
    // }
  },
//   duration: 3,
  ease: "none",
  immediateRender: true,
  motionPath: {
    path: "#path1",
    align: "#path1",
	start:1,
	end:0,
    alignOrigin: [0.5, 0.5],
	autoRotate:true
  },
});



gsap.to("#dot2", {
	scrollTrigger: {
	  trigger: "#motionPath2",
	  scrub: 1,
	  start: '-=447px center',
	  end: 'bottom center',
	  markers: false,
	},

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

  
  gsap.to("#dot3", {
	scrollTrigger: {
	  trigger: "#motionPath3",
	  scrub: 1,
	  start: 'top center',
	  end: 'bottom center',
	  markers: false,
	},
	
	ease: "none",
	immediateRender: true,
	motionPath: {
	  path: "#path3",
	  align: "#path3",
	  start:0,
	  end:1,
	  alignOrigin: [0.5, 0.5],
	  autoRotate:true
	}
  });

  gsap.to("#dot4", {
	scrollTrigger: {
	  trigger: "#motionPath4",
	  scrub: 1,
	  start: 'top center',
	  end: 'bottom center',
	  markers: false,
	},
	
	ease: "none",
	immediateRender: true,
	motionPath: {
	  path: "#path4",
	  align: "#path4",
	  start:1,
	  end:0,
	  alignOrigin: [0.5, 0.5],
	  autoRotate:true
	}
  });

  gsap.to("#dot5", {
	scrollTrigger: {
	  trigger: "#motionPath5",
	  scrub: 1,
	  start: 'top center',
	  end: 'bottom center',
	  markers: false,
	},
	
	ease: "none",
	immediateRender: true,
	motionPath: {
	  path: "#path5",
	  align: "#path5",
	  start:0,
	  end:1,
	  alignOrigin: [0.5, 0.5],
	  autoRotate:true
	}
  });

  gsap.to("#dot6", {
	scrollTrigger: {
	  trigger: ".testimonial",
	  scrub: 1,
	  start: 'center center',
	  end: '+=447px center',
	  markers: false,
	},
	
	ease: "none",
	immediateRender: true,
	motionPath: {
	  path: "#path6",
	  align: "#path6",
	  start:0,
	  end:1,
	  alignOrigin: [0.5, 0.5],
	  autoRotate:true
	}
  });

  gsap.to("#dot7", {
	scrollTrigger: {
	  trigger: "#motionPath7",
	  scrub: 1,
	  start: 'top center',
	  end: 'bottom center',
	  markers: false,
	},
	
	ease: "none",
	immediateRender: true,
	motionPath: {
	  path: "#path7",
	  align: "#path7",
	  start:0,
	  end:1,
	  alignOrigin: [0.5, 0.5],
	  autoRotate:true
	}
  });




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
				margin:20,
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
				margin:20,
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




	var mainArray = [];
	$('.sbi_photo[data-full-res]').each(function(){
		$(this).val().push(mainArray);
	});
	console.log(mainArray);

	
}); 

