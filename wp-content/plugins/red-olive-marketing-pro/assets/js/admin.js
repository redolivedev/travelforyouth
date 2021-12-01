jQuery(function($){
	$('.js-ro-marketing-activate').click(function( event ){
		var $this = $(this);
		if( ! $this.hasClass('saved') ){
			event.preventDefault();

			var data = {
				action : 'ro_marketing_set_license_key',
				license_key : $('#marketing_license_key').val()
			};
			$.post(ajaxurl, data, function(result){
				$this.addClass('saved');
				$this.click();
			});
		}
	});
});