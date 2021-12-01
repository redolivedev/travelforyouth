jQuery(function($){
	if($('body').hasClass('red-olive_page_ro-wc-settings-admin')) {
		$('.tab').hide();
		$('.tab[data-tab="1"]').show();
		$('.nav-tab').click(function(e){
			e.preventDefault();
			$('.nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			$('.tab').slideUp();
			$('.tab[data-tab="'+ $(this).data('tab') +'"]').slideDown();
		});

		$('#abandoned_cart_test_email').val('');

		$('#ro_send_test_email').click(function(){
			if( $('#abandoned_cart_test_email').val() ){
				
				data = {
					'action': 'ro_send_test_rac_email',
					'test_email': $('#abandoned_cart_test_email').val()
				};

				$.post( ajaxurl, data, function( result ){
					if( ! result.success ){
						$('#abandoned_cart_test_failed').show();
						console.log( result.data );
					}else{
						$('#abandoned_cart_test_success').show();
						$('#abandoned_cart_test_success').html( result.data );
					}
					$('#abandoned_cart_test_email').val('');
				});	
			}
		});

		/** MailChimp credentials configured in Abandoned Cart section **/
		var list_val	= $('#abandoned_cart_mailchimp_list').val();
		var api_key		= $('#abandoned_cart_mailchimp_key').val();

		if( api_key ){
			$('#abandoned_cart_mailchimp_list').replaceWith('<div id="abandoned_cart_mailchimp_list"><img src="' + ro_wc_localized.pluginUrl + 'assets/img/giphy.gif" alt="loading" height="30" width="30" /></div>');
		}

		if( api_key ) {
			$.post( ajaxurl, { action: 'ro_get_lists', api_key: api_key }, function( result ){
				if( ! result.success ) {
                    $('.js-wooc-mailchimp-creds-error').show();
				} else {
					$('#abandoned_cart_mailchimp_list').replaceWith('<select name="ro_wc_options[abandoned_cart_mailchimp_list]" id="abandoned_cart_mailchimp_list"></select>');
					$.each( result.data.lists, function( i, item ){
						$('#abandoned_cart_mailchimp_list')
						.append($("<option></option>")
						.attr("value",item.id)
						.text(item.name));
					});
					$('#abandoned_cart_mailchimp_list').val( list_val );
				}
			});
		}

		/** MailChimp credentials configured in Other section ( For add email to MailChimp on checkout success ) **/
		var add_on_checkout_list_val	= $('#add_email_to_mailchimp_list_id').val();
		var add_on_checkout_api_key		= $('#add_email_to_mailchimp_api_key').val();

		if( add_on_checkout_api_key ){
			$('#add_email_to_mailchimp_list_id').replaceWith('<div id="add_email_to_mailchimp_list_id"><img src="' + ro_wc_localized.pluginUrl + 'assets/img/giphy.gif" alt="loading" height="30" width="30" /></div>');
		}
		
		if( add_on_checkout_api_key ) {
			$.post( ajaxurl, { action: 'ro_get_lists', api_key: add_on_checkout_api_key }, function( result ){
				if( ! result.success ) {
					$('.js-wooc-mailchimp-creds-error').show();
				} else {
					$('#add_email_to_mailchimp_list_id').replaceWith('<select name="ro_wc_options[add_email_to_mailchimp_list_id]" id="add_email_to_mailchimp_list_id"></select>');
					$.each( result.data.lists, function( i, item ){
						$('#add_email_to_mailchimp_list_id')
						.append($("<option></option>")
						.attr("value",item.id)
						.text(item.name));
					});
					$('#add_email_to_mailchimp_list_id').val( add_on_checkout_list_val );
				}
			});
		}
	}

	$('#mailchimp-config').click(function(){

		$('#mailchimp-config').prop( "disabled", true );
		$('#mailchimp-config').text("Configuring...");
		
		var list_val	= $('#abandoned_cart_mailchimp_list').val();
		var api_key		= $('#abandoned_cart_mailchimp_key').val();

		$.post( ajaxurl, { action: 'ro_add_mailchimp_merge_fields', mailchimp_list: list_val, api_key: api_key }, function( result ){
			if( !result.success ){
				alert( 'Your API key is not working or no List ID was selected' );
				$('#mailchimp-config').text("Configure MailChimp List");
				$('#mailchimp-config').prop( "disabled", false );
				$('#abandoned_cart_mailchimp_config').val('false');
				console.log( result );
			}
			else{
				$('#mailchimp-config').text("Correctly Configured");
				$('#mailchimp-config').prop( "disabled", true );
				$('#abandoned_cart_mailchimp_config').val('true');
			}
		});
	});

	$('.form-table').on('change', '#abandoned_cart_mailchimp_list', function(){

		$('#mailchimp-config').prop( "disabled", true );
		$('#mailchimp-config').html("Checking...");
			
		var list_val	= $('#abandoned_cart_mailchimp_list').val();
		var api_key		= $('#abandoned_cart_mailchimp_key').val();

		$.post( ajaxurl, { action: 'ro_check_mailchimp_merge_fields', mailchimp_list: list_val, api_key: api_key }, function( result ){
			if( !result.success ){
				alert( 'Your API key is not working or no List ID was selected' );
				$('#mailchimp-config').text("Configure MailChimp List");
				$('#mailchimp-config').prop( "disabled", false );
				$('#abandoned_cart_mailchimp_config').val('false');
				console.log( result );
			}
			else{
				console.log( result );

				if( result.data.emailhash ){
					$('#mailchimp-config').text("Correctly Configured");
					$('#mailchimp-config').prop( "disabled", true );
					$('#abandoned_cart_mailchimp_config').val('true');
				}
				else{
					$('#mailchimp-config').text("Configure MailChimp List");
					$('#mailchimp-config').prop( "disabled", false );
					$('#abandoned_cart_mailchimp_config').val('false');
				}
			}
		});
	});

	$("#billing_postcode").on("blur", function(){
    	$('body').trigger('update_checkout');
  	});

  	$("#shipping_postcode").on("blur", function(){
    	$('body').trigger('update_checkout');
  	});

  	$('.js-ro-wc-activate').click(function(event){
  		var $this = $(this);
  		if( ! $this.hasClass('saved') ){
  			event.preventDefault();

  			var data = {
  				action : 'ro_wc_set_license_key',
  				license_key : $('#wc_license_key').val()
  			};
  			$.post(ajaxurl, data, function(result){
  				$this.addClass('saved');
  				$this.click();
  			});
  		}
  	});
});