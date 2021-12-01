jQuery(function($){
	if($('body').hasClass('admin_page_ro-redirects')) {

		$('.acf-field-56f0665fd1168').find('.acf-button').hide();

		$('.redirect-check').find('[type=checkbox]').change(function(event){
			if( $(this).is(':checked') ){
				postForceAjax( $(this) );
			}
		});

		$('#ro_redirect_file_import').change(function(event){
			if( ! confirm( "Are you sure you want to upload this file?\nIf you click 'cancel' the page will still reload, but no redirects will be added." ) ){
				location.reload();
				return false;
			}
			var csvInput = event;
			var skipCheck = $('#ro_redirect_skip_check').is(':checked');
			stringifyCSV(csvInput, skipCheck);
		});

		$('#alphabetize-urls').click(function(event){
			event.preventDefault();
			postAlphabetizeRedirects();
		});

		$('#delete-all-redirects').click(function(event){
			event.preventDefault();
			postDeleteAllRedirects();
		});

		function stringifyCSV(csvInput, skipCheck){

			//Make sure the file has a csv extension
			var extension = $("input#ro_redirect_file_import").val().split(".").pop().toLowerCase();
			if($.inArray(extension, ["csv"]) == -1) {
				alert('CSV file required');
				return false;
			}
			 
			//Make sure the file is not empty   
			if (csvInput.target.files != undefined) {

				//Create a FileReader to read the CSV content
				var reader = new FileReader();

				reader.onload = function(event) {

					//Split the CSV content up into rows
					var csvValues = event.target.result.split("\n");

					//Result array to hold all the line objects
					var result = [];

					//Store headers to correctly name each CSV element
					var headers = csvValues[0].split(",");

					//Break each row into its comma-separated columns and create an object for each
					$.each( csvValues, function( key, value ){
						//Split the string at each comma except when a comma is found within double quotes
						var csvValue = csvValues[key].split(/,(?=(?:(?:[^"]*"){2})*[^"]*$)/);
						var inputrad = "";
						var lineObject = {};

						//Iterate through each index of csvValue and create an object from it
						for( var i = 0; i < csvValue.length; i++ ){
							//Remove any leftover double quotes and line breaks from the CSV stuff
							lineObject[headers[i]] = csvValue[i].replace(/"|\r|\r\n|\n/g, '');
						}
						result.push(lineObject);
					});
					stringify = JSON.stringify(result);
					postUploadAjax(stringify, skipCheck);	
				};
				reader.readAsText(csvInput.target.files.item(0));
			}
		}

		function postUploadAjax(stringifiedCVS, skipCheck){
			$('#import_processing').show();

			var data = {
				'action': 'ro_import_redirects_csv',
				'csv_data': stringifiedCVS,
				'skip_check': skipCheck
			};			

			$.post( ajaxurl, data, function( result ){
				if(result.success){
					$('#import_processing').hide();
					$('#import_success').show();
					location.reload();
				}
				else {
					$('#import_processing').hide();
					$('#import_failure').show();
					console.log(result.data);
				}
			});
		}

		function postForceAjax(element){
			var redirectData = element.context.name;

			element.closest('.acf-fields').find('.force_redirect_processing').show();

			var data = {
				'action': 'ro_force_redirect',
				'redirect_data': redirectData
			};

			$.post( ajaxurl, data, function( result ){
				if(result.success){
					element.closest('.acf-fields').find('.force_redirect_processing').hide();
					element.closest('.acf-fields').find('.force_redirect_success').show();
					location.reload();
				}
				else {
					element.closest('.acf-fields').find('.force_redirect_processing').hide();
					element.closest('.acf-fields').find('.force_redirect_failure').show();
					console.log(result.data);
				}
			});
		}

		function postAlphabetizeRedirects(){
			$('#alpha_processing').show();

			var data = {
				'action': 'ro_alphabetize_redirects'
			}

			$.post( ajaxurl, data, function( result ){
				if(result.success){
					$('#alpha_processing').hide();
					$('#alpha_success').show();
					location.reload();
				}else{
					$('#import_processing').hide();
					$('#import_failure').show();
					console.log(result.data);
				}
			});
		}

		function postDeleteAllRedirects(){
			if( confirm( "WARNING:\nAre you sure you want to DELETE all redirects? This cannot be undone." ) ){
				var data = {
					'action': 'ro_delete_all_redirects'
				}

				$.post( ajaxurl, data, function( result ){
					if(result.success){						
						location.reload();
					}else{						
						console.log(result.data);
					}
				});
			}
		}
	}
});