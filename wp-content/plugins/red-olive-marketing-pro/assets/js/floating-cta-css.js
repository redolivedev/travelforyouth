var ro_floating_cta = {};

(function($){
    ro_floating_cta = {	
		init: function(){
			ro_floating_cta.insertCustomCSS();
		},
		insertCustomCSS: function(){
			$('<style type="text/css">@media only screen and (min-width: 768px){ '+cta_meta.cta_custom_css+'}</style>').appendTo('head');
			$('<style type="text/css">@media only screen and (max-width: 767px){'+cta_meta.cta_custom_css_mobile+'}</style>').appendTo('head');
		}
    }
	$(document).ready(ro_floating_cta.init());
}(jQuery));
