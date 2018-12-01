jQuery(function() {

    var ui = {
        UrlField: jQuery("#url"),
        AnalyzeButton: jQuery("#analyze"),
        Result: jQuery("#result"),
        Spinner: jQuery("#spinner"),
        ResultTemplate: jQuery(".plugin-result-template").clone().removeClass("plugin-result-template")
    };

    ui.AnalyzeButton.click(sendAnalysisRequest);

    function sendAnalysisRequest()
    {
        var url = ui.UrlField.val();
        url = "http://localhost:8080/?url=" + encodeURIComponent(url);
        
        ui.Spinner.show();
        ui.Result.empty();

        jQuery
            .getJSON(url)
            .done(function(response)
            {
                var details = response.details;
                
                for(var i=0; i<details.length; i++)
                {
                    var detail = details[i];
                    var panel = ui.ResultTemplate.clone();
                    panel.find(".title").html(detail.analyzer);
                    panel.find(".score").html(detail.score + "%");
                    var chart = panel.find(".easypiechart");
                    chart.attr("data-percent", detail.score);
                    
                    var messagesContainer = panel.find(".messages");
                    var messages = detail.message.split("\n");
                    for(var j=0; j<messages.length; j++)
                    {
                        var p = jQuery("<p>").text(messages[j]);
                        messagesContainer.append(p);
                    }
                    
                    var color = "#30a5ff";
                    
                    if(detail.score < 100)
                    {
                        color = "#ffb53e";
                    }
                    
                    if(detail.score < 50)
                    {
                        color = "#ef4040";
                    }
                    
                    chart.css("color", color);
                    ui.Result.append(panel);
                    
                    chart.easyPieChart({
                        scaleColor: false,
                        barColor: color
                    });
                }
            })
            .always(function()
            {
                ui.Spinner.hide();
            })
        ;

        return false;
    }
});
