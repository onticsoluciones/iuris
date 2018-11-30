jQuery(function() {

    var ui = {
        UrlField: jQuery("#url"),
        AnalyzeButton: jQuery("#analyze"),
        Result: jQuery("#result"),
        Spinner: jQuery("#spinner")
    };

    ui.AnalyzeButton.click(sendAnalysisRequest);

    function sendAnalysisRequest()
    {
        var url = ui.UrlField.val();
        url = "http://localhost:8080/?url=" + encodeURIComponent(url);
        
        ui.Spinner.show();

        jQuery
            .getJSON(url)
            .done(function(response)
            {
                ui.Result.text(response.score);
            })
            .always(function()
            {
                ui.Spinner.hide();
            })
        ;

        return false;
    }
});
