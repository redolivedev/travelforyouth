jQuery(function($){

	/*
	 * NOTE: The following variables are defined in ro-wc-infinite-scroll.php
	 * 
	 * pageClass <-Set by the user in the admin panel
	 * prodClass <-Set by the user in the admin panel
	 * scrollBuffer <-Set by the user in the admin panel
	 * maxPages 
	 * pluginDir
	 */

	var firstPage = true;
	var currentPage = 1;
	var currDocHeight = $(document).height();
	var basePageUrl = '';
	
	$(document).ready(function(){

		//Hide the page's pagination element
		$('ul.' + pageClass).css('display', 'none'); 

		//Attach a loading spinner div to the hidden pagination element
		$('ul.' + pageClass).after('<div id="load-spinner"><img src="' + pluginDir + 'assets/img/giphy.gif" alt="loading" height="40" width="40" /></div>');

		//Hide the spinner for now
		$('#load-spinner').hide();

		//Set the initial height of the document
		currDocHeight = $(document).height(); 

		$(window).scroll(function(){
			if( ($(window).height() + $(window).scrollTop()) > ( $(document).height() - scrollBuffer) && 
				($(window).height() + $(window).scrollTop()) < ( $(document).height())){

				//Prevent multiple calls due to multiple scroll events.
				waitForFinalEvent( function(){
					var nextPageUrl = checkNextPage();

					if( nextPageUrl && nextPageUrl.length > 0 ){
						pullNextPage( nextPageUrl );
					}
				}, timeToWaitForLast, "some-function-identifier-string");
			
			}
		});
	});

	/**
	 * Attach the appropriate element to the page and fill it with the contents of the next page.
	 */
	function pullNextPage(nextPage){

		//Find all of the elements with a class that matches the prodClass variable
		var productTagArray = $('.' + prodClass);

		//Attach another element with the prodClass after the last prodClass element
		$(productTagArray.last()).after('<ul class="' + prodClass + '"></ul>');

		//Find all the prodClass elements again (including the new one just added)
		productTagArray = $('.' + prodClass);

		//Start showing the spinner because we're loading AJAX
		$('#load-spinner').show();

		//Load the li content of the next page into the newly-added prodClass element
		$(productTagArray.last()).load( nextPage + ' .' + prodClass + ' li', function(){
			$('#load-spinner').hide();
		});
	}

	/**
	 * Make sure that the next page should be loaded. If it should be, return the page to load.
	 */
	function checkNextPage(){

		//I've we've reached the max number of pages, return false
		if( currentPage >= maxPages ){			
			return false;			
		}

		//If it's the first page, return the URL in the page's 'next' link. Then use that as the basePageUrl.
		//If it's not the first page, append the correct number for the page to grab and append it to the basePageUrl.
		if( firstPage ){
			var nextPage = $('.next.' + pageClass).attr('href');
			basePageUrl = nextPage.replace(/[0-9]+\/?$/, '');
			firstPage = false;
			currDocHeight = $(document).height();
			currentPage++;
			return nextPage;
		}
		else if( checkDocHeight($(document).height()) ){
			currentPage++;
			currDocHeight = $(document).height();
			return basePageUrl + currentPage + '/';
		}		
	}

 	/**
 	 * Make sure that the document height has changed since the last AJAX call. An increase in height means
 	 * that we're able to make another AJAX call.
 	 */
	function checkDocHeight(testDocHeight){		
		if(testDocHeight == currDocHeight){
			return false;			
		}
		else if( Math.abs(testDocHeight - currDocHeight) < 500 ){
			return false;
		}
		else {
			return true;
		}		
	}

	/**
	 * Bones function which restricts the amount of calls that go through on an event. 
	 */ 
	var waitForFinalEvent = (function () {
	  var timers = {};
	  return function (callback, ms, uniqueId) {
	    if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
	    if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
	    timers[uniqueId] = setTimeout(callback, ms);
	  };
	})();

	// how long to wait before deciding the scroll has stopped, in ms.
	var timeToWaitForLast = 300;

});