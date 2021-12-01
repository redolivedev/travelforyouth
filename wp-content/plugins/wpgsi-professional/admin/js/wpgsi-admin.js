//  JS code for wpgsi
if (document.getElementById("vuejs-app-div") && frontEnd.CurrentPage === "new") {
    var app = new Vue({
        el: '#vuejs-app-div',
        data: {
            ok: "",
            DisplayIcon: false,
            IntegrationTitle: "",
            DataSource: "",
            DataSourceID: "",
            DataSourceTitles: JSON.parse(frontEnd.DataSourceTitles),
            DataSourceFields: JSON.parse(frontEnd.DataSourceFields),
            Worksheet: "",
            WorksheetID: "",
            Spreadsheet: "",
            SpreadsheetID: "",
            SpreadsheetWorksheet: "",
            GoogleSpreadsheets: JSON.parse(frontEnd.GoogleSpreadsheets),
            WorksheetColumnsTitle: {},
            DisplaySheets: {},
            Relations: {}
        },
        methods: {
            DataSourceFunc(event) {
                // Emptying The existing relation
                this.Relations = {};
                //  Now Set the Name of DataSource 
                if (typeof this.DataSourceTitles[this.DataSourceID] !== 'undefined') {
                    this.DataSource = this.DataSourceTitles[this.DataSourceID]
                }
            },
            SpreadsheetAndWorksheetChange(event) {
                this.DisplayIcon = true;
                this.Relations = {};
                this.WorksheetColumnsTitle = {};

                if (this.SpreadsheetWorksheet) {
                    var decodedString = atob(this.SpreadsheetWorksheet);
                    var TmpData = decodedString.split(',');

                    app.Spreadsheet = this.GoogleSpreadsheets[TmpData[0]][0];
                    app.SpreadsheetID = TmpData[0];

                    app.Worksheet = this.GoogleSpreadsheets[TmpData[0]][1][TmpData[1]];
                    app.WorksheetID = TmpData[1];

                    if (this.Worksheet && this.WorksheetID && this.SpreadsheetID) {
                        var requestData = {
                            action: "wpgsi_WorksheetColumnsTitle",
                            Worksheet: this.Worksheet,
                            WorksheetID: this.WorksheetID,
                            SpreadsheetID: this.SpreadsheetID,
                            nonce: frontEnd.nonce
                        };

                        jQuery.post(frontEnd.ajaxUrl, requestData, function (response) {
                            var output = JSON.parse(response);
                            if (output[0]) {
                                app.WorksheetColumnsTitle = output[1];
                                app.ok = true;
                                app.DisplayIcon = false;
                            } else {
                                app.ok = false;
                                alert("Remove hidden space or character from google worksheet name! or rename it.\n Also, Reload this Page.");
                            }
                        });
                    } else {
                        console.log('error :  DataSource || RemotePlatform  is Empty !');
                    }
                }
            },
            eventOutputs(eventX, name) {
                var NewValue = eventX.target.value;
                var existingValue = app.Relations[name];
                if (NewValue) {
                    if (existingValue) {
                        var Final = existingValue + " {{" + NewValue + "}} ";
                    } else {
                        var Final = "{{" + NewValue + "}} ";
                    }
                    Vue.set(app.Relations, name, Final);
                }
            }
        },
        mounted: function () {
            for (TmpSpreadsheet in this.GoogleSpreadsheets) {
                for (TmpWorksheet in this.GoogleSpreadsheets[TmpSpreadsheet][1]) {
                    var base64 = [];
                    base64.push(TmpSpreadsheet);
                    base64.push(TmpWorksheet);
                    Vue.set(this.DisplaySheets, btoa(base64), this.GoogleSpreadsheets[TmpSpreadsheet][0] + " ⯈ " + this.GoogleSpreadsheets[TmpSpreadsheet][1][TmpWorksheet]);
                }
            }
        }
    });
}

if (document.getElementById("vuejs-app-div") && frontEnd.CurrentPage === "edit") {
    var app = new Vue({
        el: '#vuejs-app-div',
        data: {
            ok: true,
            DisplayIcon: false,
            IntegrationTitle: frontEnd.IntegrationTitle,
            DataSource: frontEnd.DataSource,
            DataSourceID: frontEnd.DataSourceID,
            DataSourceTitles: JSON.parse(frontEnd.DataSourceTitles),
            DataSourceFields: JSON.parse(frontEnd.DataSourceFields),
            Worksheet: frontEnd.Worksheet,
            WorksheetID: frontEnd.WorksheetID,
            Spreadsheet: frontEnd.Spreadsheet,
            SpreadsheetID: frontEnd.SpreadsheetID,
            SpreadsheetWorksheet: "",
            GoogleSpreadsheets: JSON.parse(frontEnd.GoogleSpreadsheets),
            WorksheetColumnsTitle: frontEnd.WorksheetColumnsTitle,
            DisplaySheets: {},
            Relations: frontEnd.Relations,
        },
        methods: {
            DataSourceFunc(event) {
                this.Relations = {};
                //  Now Set the Name of DataSource 
                if (typeof this.DataSourceTitles[this.DataSourceID] !== 'undefined') {
                    this.DataSource = this.DataSourceTitles[this.DataSourceID]
                }
            },
            SpreadsheetAndWorksheetChange(event) {
                this.DisplayIcon = true;
                this.Relations = {};
                this.WorksheetColumnsTitle = {};

                if (this.SpreadsheetWorksheet) {
                    var decodedString = atob(this.SpreadsheetWorksheet);
                    var TmpData = decodedString.split(',');

                    app.Spreadsheet = this.GoogleSpreadsheets[TmpData[0]][0];
                    app.SpreadsheetID = TmpData[0];

                    app.Worksheet = this.GoogleSpreadsheets[TmpData[0]][1][TmpData[1]];
                    app.WorksheetID = TmpData[1];

                    if (this.Worksheet && this.WorksheetID && this.SpreadsheetID) {
                        var requestData = {
                            action: "wpgsi_WorksheetColumnsTitle",
                            Worksheet: this.Worksheet,
                            WorksheetID: this.WorksheetID,
                            SpreadsheetID: this.SpreadsheetID,
                            nonce: frontEnd.nonce
                        };

                        jQuery.post(frontEnd.ajaxUrl, requestData, function (response) {
                            var output = JSON.parse(response);
                            if (output[0]) {
                                app.WorksheetColumnsTitle = output[1];
                                app.ok = true;
                                app.DisplayIcon = false;
                            } else {
                                app.ok = false;
                            }
                        });
                    } else {
                        console.log('error :  DataSource || RemotePlatform  is Empty !');
                    }
                }
            },
            eventOutputs(eventX, name) {
                var NewValue = eventX.target.value;
                var existingValue = app.Relations[name];
                if (NewValue) {
                    if (existingValue) {
                        var Final = existingValue + " {{" + NewValue + "}} ";
                    } else {
                        var Final = "{{" + NewValue + "}} ";
                    }
                    Vue.set(app.Relations, name, Final);
                }
            }
        },
        mounted: function () {
            if (frontEnd.SpreadsheetID && frontEnd.WorksheetID) {
                var base64 = [];
                base64.push(frontEnd.SpreadsheetID);
                base64.push(frontEnd.WorksheetID);
                this.SpreadsheetWorksheet = btoa(base64);
            }

            for (TmpSpreadsheet in this.GoogleSpreadsheets) {
                for (TmpWorksheet in this.GoogleSpreadsheets[TmpSpreadsheet][1]) {
                    var base64 = [];
                    base64.push(TmpSpreadsheet);
                    base64.push(TmpWorksheet);
                    Vue.set(this.DisplaySheets, btoa(base64), this.GoogleSpreadsheets[TmpSpreadsheet][0] + " ⯈ " + this.GoogleSpreadsheets[TmpSpreadsheet][1][TmpWorksheet]);
                }
            }
        }
    });
}

// New Code For update 
(function ($) {
    'use strict';
    $("#wpgsiUpdateProduct").click(function () {
        // Click confirmation 
        console.log('Product updating started ...');
        // Hide the Start Button 
        $("#wpgsiUpdateProduct").css('visibility', 'hidden');
        // Show lodging animation button
        $("#updateAnimation").css('visibility', 'visible');
        // Counter
        var i = 0;
        //  init the function
        var intervalId = window.setInterval(function () {
            i++;
            // Printing Number of request
            console.log('Number of Request : ' + i);
            // AJAX Data
            var wpgsiAjaxData = {
                'action': 'wpgsi_productUpdateAJAX',
                'val': 'no value, its a empty request to run the PHP script  '
            };
            // Init request 
            jQuery.post(frontEnd.ajaxUrl, wpgsiAjaxData, function (reqRet) {
                console.log(reqRet);
                var reqRetObj = [];
                var reqRetObj = JSON.parse(reqRet);
                console.log(reqRetObj);
                // End The request 
                if (reqRetObj[0] === true) {
                    alert("Products Updated Successfully.");
                    $("#updateAnimation").css('visibility', 'hidden');
                    $("#updateDone").css('visibility', 'visible');
                    clearInterval(intervalId);
                }

            });
        }, 20000);
    })
})(jQuery);


// Please Don't Copy My Code ; World is BIG ; thank you;