jQuery(function($){
	if( $('body').hasClass('page-template-ro-blank') ){
		var bgColor = $('#bg-color').val();	
		if( bgColor != 'false' ){
			$('#content').css( "background-color", bgColor );
		}
	}
});
