jQuery(function($){
	if($('body').hasClass('red-olive_page_ro-wc-settings-admin')) {
		$('#ro_product_import').change(function(event){
			var csvInput = event;
			stringifyCSV(csvInput);
		});

		function stringifyCSV(csvInput){

			//Make sure the file has a csv extension
			var extension = $("input#ro_product_import").val().split(".").pop().toLowerCase();
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
					postAjax(stringify);					
				};
				reader.readAsText(csvInput.target.files.item(0));
			}			
		}

		function postAjax(stringifiedCVS){

			$('#import_processing').show();

			var data = {
				'action': 'ro_import_csv',
				'csv_data': stringifiedCVS
			};

			$.post( ajaxurl, data, function( result ){
				if(result.success){
					$('#import_processing').hide();
					$('#import_success').show();
				}
				else {
					$('#import_processing').hide();
					$('#import_failure').show();
					console.log(result.data);
				}
			});
		}
	}	
});