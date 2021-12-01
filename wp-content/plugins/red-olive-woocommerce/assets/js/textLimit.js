jQuery(function($){
	if( ! $('body').hasClass('post-type-product') ) {
		return;
	}	

	//Set the counters on page load
	setFeedCounters();

	//Set counters on keyup in either field
	$('#_ro_google_product_title').keyup(function(){
		setFeedCounters();
	});

	$('#_ro_google_product_description').keyup(function(){
		setFeedCounters();
	});

	$('#feeds_global_description').keyup(function(){
		setFeedCounters();
	})

	//Assign the counted values to the correct html element
	function setFeedCounters(){

		var titleLength = null;
		var descLength = null;
		var globalDescLength = null;


		if( $('#_ro_google_product_title').length > 0 ){
			titleLength = getCorrectTextLength($('#_ro_google_product_title').val());
		}

		if( $('#_ro_google_product_description').length > 0 ){
			descLength = getCorrectTextLength($('#_ro_google_product_description').val());
		}

		if( $('#feeds_global_description').length > 0 ){
			globalDescLength = getCorrectTextLength($('#feeds_global_description').val());
		}

		if ( !titleLength && !descLength && !globalDescLength){
			return;			
		}

		$('.title-counter').html(titleLength);
		$('.desc-counter').html(descLength);
		$('.global-desc-counter').html(globalDescLength);

		if(titleLength > 140){
			$('.title-counter').css('color', 'red');
		}

		if(descLength > 4990){
			$('.desc-counter').css('color', 'red');
		}

		if(globalDescLength > 4990){
			$('.global-desc-counter').css('color', 'red');
		}
	}

	//Get the amount of characters taking line breaks into consideration
	function getCorrectTextLength(text){
		var newLines = text.match(/(\r\n|\n|\r)/g);
		var addition = 0;
		if (newLines != null) {
			addition = newLines.length;
		}

		return text.length + addition;
	}

});