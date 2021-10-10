jQuery(function($){
	if($('body').hasClass('toplevel_page_red-olive')) {
		$('.tab').hide();
		$('.tab[data-tab="1"]').show();
		$('.nav-tab').click(function(e){
			e.preventDefault();
			$('.nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			$('.tab').slideUp();
			$('.tab[data-tab="'+ $(this).data('tab') +'"]').slideDown();
		});		
	}
});
