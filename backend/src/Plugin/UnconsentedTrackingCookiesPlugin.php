<?php

namespace Ontic\Iuris\Plugin;

use Ontic\Iuris\Interfaces\IPlugin;
use Ontic\Iuris\Model\AnalysisDetail;
use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\Flag;

class UnconsentedTrackingCookiesPlugin implements IPlugin
{
    private $trackingCookies = [
        '_gid' => 'Google Analytics: Used to distinguish users.',
        '_ga' => 'Google Analytics: Used to distinguish users.',
        '_gat' => 'Google Analytics: Used to throttle request rate. If Google Analytics is deployed via Google Tag Manager, this cookie will be named _dc_gtm_<property-id>.',
        '__utma' => 'Google Analytics: Used to distinguish users and sessions. The cookie is created when the javascript library executes and no existing __utma cookies exists. The cookie is updated every time data is sent to Google Analytics.',
        '__utmt' => 'Google Analytics: Used to throttle request rate.',
        '__utmb' => 'Google Analytics: Used to determine new sessions/visits. The cookie is created when the javascript library executes and no existing __utmb cookies exists. The cookie is updated every time data is sent to Google Analytics.',
        '__utmc' => 'Google Analytics: Not used in ga.js. Set for interoperability with urchin.js. Historically, this cookie operated in conjunction with the __utmb cookie to determine whether the user was in a new session/visit.',
        '__utmz' => 'Stores the traffic source or campaign that explains how the user reached your site. The cookie is created when the javascript library executes and is updated every time data is sent to Google Analytics.',
        '__utmv' => 'Google Analytics: Used to store visitor-level custom variable data. This cookie is created when a developer uses the _setCustomVar method with a visitor level custom variable. This cookie was also used for the deprecated _setVar method. The cookie is updated every time data is sent to Google Analytics.',
        '__unam' => 'ShareThis: Is set as part of the ShareThis service and monitors "click-stream" activity, e.g. web pages viewed, navigation from page to page, time spent on each page etc.'
    ];
    
    private $sessionCookies = [
        'PHPSESSID' => 'PHP Session Cookie',
        'JSESSIONID' => 'Java Servlet Session Cookie',
        'has_js' => 'Detection of browser features',
        'COOKIE_SUPPORT' => 'Detection of browser features',
        'GUEST_LANGUAGE_ID' => 'Language selected by the user',
        'ASP.NET_SessionId' => 'dotNet Session Cookie',
        'citrix_ns_id' => 'Citrix Firewall Session Cookie',
        'adaptive_image' => 'Use by Drupal to determine the optimal size of images',
	'__cfduid' => 'Cloudfare session cookie'
    ];
    
    /**
     * @return string
     */
    function getName()
    {
        return 'unconsented_tracking_cokies';
    }

    /**
     * @param AnalysisRequest $request
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request)
    {
        $trackingCookies = [];
        $sessionCookies = [];
        $unknownCookies = [];
        
        $cookies = $request->getWebdriver()->manage()->getCookies();
        $totalScore = 0;
        
        foreach($cookies as $cookie)
        {
            $name = $cookie->getName();
            
            if(isset($this->trackingCookies[$name]))
            {
                $trackingCookies[] = '✗ ' . $this->trackingCookies[$name];
            }
            elseif(isset($this->sessionCookies[$name]))
            {
                $sessionCookies[] = '✓ ' . $this->sessionCookies[$name];
                $totalScore += 100;
            }
            else
            {
                $unknownCookies[] = '? ' . $name;
                $totalScore += 50;
            }
        }
        
        $message = array_merge($trackingCookies, $sessionCookies, $unknownCookies);
        $message = implode("\n", $message);
        
        return new AnalysisDetail(
            $this->getName(),
            Flag::Scorable,
            $totalScore / count($cookies),
            $message);
    }
}