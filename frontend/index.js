jQuery(function() {
    
    var ui = {
        UrlField: jQuery("#url"),
        AnalyzeButton: jQuery("#analyze"),
        Result: jQuery("#result")
    };
    
    ui.AnalyzeButton.click(sendAnalysisRequest);
    
    function sendAnalysisRequest()
    {
        var url = ui.UrlField.val();
        url = "http://localhost:8080/?url=" + encodeURIComponent(url);
        
        jQuery
            .getJSON(url)
            .done(function(response)
            {
                ui.Result.text(response.score);
            });
        
        return false;
    }
});
