jQuery(function() 
{
    var apiUrl = "http://localhost:8080";

    var ui = {
        UrlField: jQuery("#url"),
        AnalyzeButton: jQuery("#analyze"),
        Result: jQuery("#result"),
        Spinner: jQuery("#spinner"),
        ResultTemplate: jQuery(".plugin-result-template").clone().removeClass("plugin-result-template"),
        AvailablePlugins: jQuery(".available-plugins")
    };

    ui.AnalyzeButton.click(sendAnalysisRequest);
    
    function displayAvailablePlugins()
    {
        var url = apiUrl + '/plugins';
        
        jQuery
            .getJSON(url)
            .done(function(response)
            {
                for(var code in response)
                {
                    if(!response.hasOwnProperty(code))
                    {
                        continue;
                    }
                    
                    var displayName = response[code];
                    
                    var input = jQuery("<input>");
                    input.attr("type", "checkbox");
                    input.attr("id", "plugin-" + code);
                    input.attr("checked", true);
                    input.attr("data-plugin", code);
                    input.addClass("selected-plugin");
                    var label = jQuery("<label>");
                    label.attr("for", "plugin-" + code);
                    label.css("padding-left", "22px");
                    label.css("padding-right", "10px");
                    label.css("line-height", "20px");
                    label.text(displayName);
                    
                    ui.AvailablePlugins.append(input);
                    ui.AvailablePlugins.append(label);
                }
            });
    }

    function sendAnalysisRequest()
    {
        var selectedPlugins = [];
        jQuery(".selected-plugin:checked").each(function(index, element)
        {
            selectedPlugins.push(jQuery(element).data("plugin"));
        });
        
        var url = apiUrl + "/?url=" + encodeURIComponent(ui.UrlField.val()) + "&selected_plugins=" + selectedPlugins.join(',');
        
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
    
    displayAvailablePlugins();
});
