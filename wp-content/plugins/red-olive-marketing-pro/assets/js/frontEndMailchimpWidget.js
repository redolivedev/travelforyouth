jQuery( function( $ ){
	MailchimpWidget = {

		init: function(){
			MailchimpWidget.registerEventHandlers();
		},

		registerEventHandlers: function(){
			$('body').on( 'click', '.mailchimp-button', MailchimpWidget.sendAddEmailAjax );
			$('.mailchimp-text').on( 'keyup', MailchimpWidget.clearMessages );
		},
		
		sendAddEmailAjax: function(){
			var email = $('.mailchimp-text').val();
			if( ! email ){
				return;
            }

            var buttonText = $('.mailchimp-button').text();
            
            $('.mailchimp-button').replaceWith(
                '<div class="mailchimp-button" style="text-align: center;"><img src="/wp-admin/images/loading.gif"></div>'
            );
			
			var data = {
				'action': 'ro_add_email_to_mailchimp',
				'email': email
			};

			$.post( frontEndAjaxURL, data, function( result ){
				if( ! result.success ){
					MailchimpWidget.handleAddEmailFailure( result, buttonText );
				}else{
					MailchimpWidget.handleAddEmailSuccess( result );
				}
			});
		},

		handleAddEmailSuccess: function( result ){
            $('.mailchimp-text').val('');
            $('.mailchimp-button').hide();
			$('.mailchimp-success').show();
		},

		handleAddEmailFailure: function( result, buttonText ){
            $('.mailchimp-button').replaceWith(
                '<button class="mailchimp-button">' + buttonText + '</button>'
            );
			$('.mailchimp-error').text( result.data );
			$('.mailchimp-error').show();
		},

		clearMessages: function(){
			$('.mailchimp-error').hide();
			$('.mailchimp-success').hide();
		}
	};

	$( window ).on( 'load', function(){
		MailchimpWidget.init();
	});
});