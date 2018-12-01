var currentTab;

/*
 * Switches currentTab to reflect the currently active tab
 */
function updateActiveTab(tabs) {

  function isSupportedProtocol(urlString) {
    var supportedProtocols = ["https:", "http:"];
    var url = document.createElement('a');
    url.href = urlString;
    return supportedProtocols.indexOf(url.protocol) != -1;
  }

  function httpGet(theUrl, callback)
  {
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            callback(xmlhttp.responseText);
        }
    }
    xmlhttp.open("GET", theUrl, true);
    xmlhttp.send();
  }

  function updateTab(tabs) {
    if (tabs[0]) {
      currentTab = tabs[0];
      if (isSupportedProtocol(currentTab.url)) {
	httpGet("http://localhost:8080/?url=" + currentTab.url, function(response) {
	    //console.log(response);

	    // get score from iuris ws
    	    var score = JSON.parse(response).score.toString();

	    // set score
	    browser.browserAction.setBadgeText({text: score, tabId: currentTab.id});

	    // switch color based on web score
	    if(score >= 100)
	    {
		var bbcolor = 'green';
	    }
	    else if(score >= 50 && score < 100)
	    {
		var bbcolor = 'yellow';
	    }
	    else
	    {
		var bbcolor = 'red';
	    }
	    browser.browserAction.setBadgeBackgroundColor({'color': bbcolor, tabId: currentTab.id});
	});

      } else {
        //console.log("iuris does not support the " + ${currentTab.url} + " URL.");
      }
    }
  }

  var gettingActiveTab = browser.tabs.query({active: true, currentWindow: true});
  gettingActiveTab.then(updateTab);
}

// listen to tab URL changes
browser.tabs.onUpdated.addListener(updateActiveTab);

// update when the extension loads initially
updateActiveTab();
