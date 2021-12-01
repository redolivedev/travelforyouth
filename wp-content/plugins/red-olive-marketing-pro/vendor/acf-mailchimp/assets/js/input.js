(function($){
	
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize_field( $field ) {
		if( ! $('.js-mc-combined-values').val() ) return;
		var values = JSON.parse( $('.js-mc-combined-values').val() );
		
		get_mailchimp_lists( values.apiKey, values.list );
	}

	function get_mailchimp_lists( api_key, list_val ){
		$('.js-mc-list-select').replaceWith( 
			'<div class="js-mc-list-select" style="text-align: center;"><img src="/wp-admin/images/loading.gif"></div>' 
		);
		$.post( ajaxurl, { action: 'ro_get_mailchimp_lists', api_key: api_key }, function( result ){
			if( ! result.success ) {
				$('.js-mc-list-select').replaceWith( 
					'<div class="js-mc-list-select"></div>' 
				);
				$('.js-mailchimp-creds-error').show();
			} else {
				$('.js-mc-list-select').replaceWith(
					'<select class="js-mc-list-select" name="mc-list"></select>'
				);

				$('.js-mc-list-select').append(
					$('<option></option>').attr('value','').text('--Select List--')
				);
				$.each( result.data.lists, function( i, item ){
					$('.js-mc-list-select').append(
						$('<option></option>').attr('value',item.id).text(item.name)
					);
				});
				$('.js-mc-list-select').val( list_val );
				$('.js-mc-list-select').show();
			}
		}).fail( function(){
            $('.js-mc-list-select').replaceWith( 
                '<div class="js-mc-list-select"></div>' 
            );
            $('.js-mailchimp-server-error').show();
        });
	}
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		acf.add_action('ready_field/type=mailchimp', initialize_field);
		
		acf.add_action( 'ready append', function( $el ){

			acf.get_fields({ type: 'mailchimp' }, $el).each( function(){

				jQuery( document ).on( 'click', '#js-mc-set-key', function(){
					$('.js-mailchimp-creds-error').hide();
					$('.js-mc-combined-values').val('');
					var parentElement = $(this).parents('.ro-acf-mailchimp-container');
					var apiKey = $(parentElement).find( '.js-mc-api-key' ).val();
					get_mailchimp_lists( apiKey, '' );
					$('.js-mc-combined-values').val( JSON.stringify( {"apiKey": apiKey, "list": ''} ) );
				});

				jQuery( document ).on( 'change', '.js-mc-list-select', function(){
					$('.js-mailchimp-creds-error').hide();
					var parentElement = $(this).parents('.ro-acf-mailchimp-container');
					var apiKey = $(parentElement).find( '.js-mc-api-key' ).val();
					var list = $(this).val();
					$('.js-mc-combined-values').val( JSON.stringify( {"apiKey": apiKey, "list": list} ) );
				});
			});
		});
	}

})(jQuery);
