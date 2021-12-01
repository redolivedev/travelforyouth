jQuery(function($){

	//Add class to cart form, so it can be easily specified
	if($('body').hasClass('woocommerce-cart')){
		$('form').addClass('cart-form');
	}
	
	//Check to see if the user presses the enter key
	$('.cart-form').keypress(function(e){				
		if( e.which == '13' ){
			//If the coupon field is focused, trigger its click event
			if( $('#coupon_code').is(':focus') ){
				e.preventDefault();
				$('input[name="apply_coupon"]').trigger('click');
			}			
		}
	});

});