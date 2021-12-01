jQuery(function($){

	$(document).ready(function(){
		var version = getQueryVariable('ver');
		postAjax(version);
	});

	/** 
	 * Gets the specified URL parameter
	 */
	function getQueryVariable(variable)	{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
	}

	/**
	 * Posts the value of the url parameter
	 */
	function postAjax(version){
		var data = {
			'action': 'ab_testing_lookup',
			'ajax_sent': true,
			'version': version
		};

		$.post( frontEndAjaxURL, data, function(result){
			if(result.success){
				replaceText(result.data);
			}
			else {
				console.log(result.data);
			}
		});
	}

	/**
	 * Replaces the specified elements with their version text.
	 */
	function replaceText(data){
		if( data ){
			data.forEach(function(value, key){
				$(value.element_id).html(value.element_text);
			});
		}		
	}

});