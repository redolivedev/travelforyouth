var ro_pop_up = {};

(function($){
    ro_pop_up = {	
		init: function(){
			ro_pop_up.insertCustomCSS();
		},
		insertCustomCSS: function(){
			$('<style type="text/css">@media only screen and (min-width: 768px){ '+pop_up_meta.custom_css+'}</style>').appendTo('head');
			$('<style type="text/css">@media only screen and (max-width: 767px){'+pop_up_meta.custom_css_mobile+'}</style>').appendTo('head');
		}
    }
	$(document).ready(ro_pop_up.init());
}(jQuery));
