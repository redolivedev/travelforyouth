jQuery(function($){function r(r){var t=$("input#ro_product_import").val().split(".").pop().toLowerCase();if(-1==$.inArray(t,["csv"]))return alert("CSV file required"),!1;if(void 0!=r.target.files){var o=new FileReader;o.onload=function(r){var t=r.target.result.split("\n"),o=[],s=t[0].split(",");$.each(t,function(r,i){for(var e=t[r].split(/,(?=(?:(?:[^"]*"){2})*[^"]*$)/),n="",a={},c=0;c<e.length;c++)a[s[c]]=e[c].replace(/"|\r|\r\n|\n/g,"");o.push(a)}),stringify=JSON.stringify(o),i(stringify)},o.readAsText(r.target.files.item(0))}}function i(r){$("#import_processing").show();var i={action:"ro_import_csv",csv_data:r};$.post(ajaxurl,i,function(r){r.success?($("#import_processing").hide(),$("#import_success").show()):($("#import_processing").hide(),$("#import_failure").show(),console.log(r.data))})}$("body").hasClass("settings_page_ro-wc-settings-admin")&&$("#ro_product_import").change(function(i){var t=i;r(t)})});