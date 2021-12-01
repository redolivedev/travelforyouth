jQuery(function($){
	if($('body').hasClass('woocommerce-checkout')){
		if($('#billing_email').length){
			storeEmail( $('#billing_email').val() );
		}
		$('#billing_email').on('blur', function(){
			if($('#billing_email').val()){
				storeEmail( $('#billing_email').val() );
			}
		});	
	}

	if($('body').hasClass('woocommerce-order-received')){
		checkoutOrDeleteEmail();
	}

	function storeEmail(email){
		var data = {
			'action': 'ro_save_cart_contents',
			'email': email
		};

		$.post( ro_wc_ac_localized.ajaxUrl, data, function( result ){
			if(result.success){
				// console.log(result);
			}
			else {
				// console.log(result.data);
			}
		});
	}

	function checkoutOrDeleteEmail(){
		var data = {
            'action': 'ro_checkout_or_delete_stored_email',
            'order_id': wcOrderID
		};

		$.post( ro_wc_ac_localized.ajaxUrl, data, function( result ){
			if( result.success ){
				// console.log( result );
			}
			else{
				// console.log( result.data );
			}
		});
	}
});