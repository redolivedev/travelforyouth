jQuery(function($){
	if($('body').hasClass('toplevel_page_red-olive')) {
		var list_val	= $('#mailchimp_widget_list_id').val();
		var api_key		= $('#mailchimp_widget_api_key').val();
		if( api_key ) {
			$('#mailchimp_widget_list_id').replaceWith( 
				'<div id="mailchimp_widget_list_id"><img src="/wp-admin/images/loading.gif"></div>' 
			);
			$.post( ajaxurl, { action: 'ro_get_mailchimp_lists', api_key: api_key }, function( result ){
				if( ! result.success ) {
					$('.js-mailchimp-creds-error').show();
				} else {
                    $('#mailchimp_widget_list_id').replaceWith('<select name="ro_marketing_options[mailchimp_widget_list_id]" id="mailchimp_widget_list_id"></select>');
                    $('#mailchimp_widget_list_id').append( $('<option></option>').attr('value','').text('--Select List--') );
					$.each( result.data.lists, function( i, item ){
						$('#mailchimp_widget_list_id').append($("<option></option>").attr("value",item.id).text(item.name));
					});
					$('#mailchimp_widget_list_id').val( list_val );
				}
			});
		}
	}
});
