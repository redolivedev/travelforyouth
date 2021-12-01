var ro_site_wide_banner = {};

(function($){
    
    ro_site_wide_banner = {
			
		init: function(){
			ro_site_wide_banner.insertBanner();
			ro_site_wide_banner.registerEventHandlers();
		},
		registerEventHandlers: function(){
			var position = (banner_meta.sticky) ? 'fixed' : 'relative';
			if( position == 'relative' ){
				return;
			}

			var banner_height = $('.swb-site-wide-banner').height();
			$(window).on('scroll', function(){
			    var $winScroll = $(window).scrollTop();

			    if($winScroll >= (banner_height + 50)){
			        $('.swb-site-wide-banner').addClass('swb-fixed');
			    }else if($winScroll == 0){
			        $('.swb-site-wide-banner').removeClass('swb-fixed');
			    }
			});
		},
		getBannerContent: function(){
			bannerContent = {};

			// LEFT COLUMN
			if(banner_meta.left_col_image_text == 'image'){
				bannerContent.leftColStyles = 'background-image: url('+ banner_meta.left_col_image +'); background-size: cover; color: '+ banner_meta.left_col_image_over_text_color;
				bannerContent.leftColContent = banner_meta.left_col_image_over_text;
			}else{
				bannerContent.leftColStyles = 'background-color:'+ banner_meta.left_col_background_color +'; color:'+ banner_meta.left_col_text_color +';';
				bannerContent.leftColContent = banner_meta.left_col_text;
			}

			// CENTER COLUMN
			bannerContent.centerColStyles = 'color:'+ banner_meta.center_col_text_color +';';
			bannerContent.centerColContent = banner_meta.center_col_text;
			bannerContent.centerColSecondaryContent = banner_meta.center_col_secondary_text;
			bannerContent.centerColSecondaryStyles = banner_meta.center_col_secondary_text_color;

			// RIGHT COLUMN
			bannerContent.rightColButtonStyles = 'background-color:'+ banner_meta.right_col_button_background_color +'; color:'+ banner_meta.right_col_text_color +'; border-radius:' + banner_meta.right_col_button_border_radius +';'
			bannerContent.rightColButtonLink = banner_meta.right_col_button_link;
			bannerContent.rightColContent = banner_meta.right_col_text;

			return bannerContent;
		},
		insertBanner: function(){
			var bannerContent = ro_site_wide_banner.getBannerContent();
			var banner = '<div class="swb-site-wide-banner" style="background-color:'+ banner_meta.banner_background_color +';">'+
				'<div class="swb-left-col" style="'+ bannerContent.leftColStyles +'">'+ bannerContent.leftColContent +'</div>'+
				'<div class="swb-center-col"><strong style="'+ bannerContent.centerColStyles +'">'+ bannerContent.centerColContent +'</strong> <span style="color:'+ banner_meta.center_col_secondary_text_color +';">'+ bannerContent.centerColSecondaryContent +'</span></div>'+
				'<div class="swb-right-col"><a href="'+ bannerContent.rightColButtonLink +'" style="'+ bannerContent.rightColButtonStyles +'">'+ bannerContent.rightColContent +'</a></div>'+
			'</div>';
			$('body').wrapInner( '<div class="ro-site-wrapper" style="position:relative;"></div>' );
			$('body').prepend(banner);
		}
    }
    
	$(document).ready(ro_site_wide_banner.init());

}(jQuery));