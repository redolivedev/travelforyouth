var ro_site_wide_banner = {};

(function($){
    ro_site_wide_banner = {
		init: function(){
			ro_site_wide_banner.insertCustomCSS();
		},
		insertCustomCSS: function(){
			$('<style type="text/css">@media only screen and (min-width: 768px){ '+banner_meta.custom_css+'}</style>').appendTo('head');
			$('<style type="text/css">@media only screen and (max-width: 767px){'+banner_meta.custom_css_mobile+'}</style>').appendTo('head');
		}
    }   
	$(document).ready(ro_site_wide_banner.init());
}(jQuery));
