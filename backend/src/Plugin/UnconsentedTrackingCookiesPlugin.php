<?php

namespace Ontic\Iuris\Plugin;

use Ontic\Iuris\Interfaces\IPlugin;
use Ontic\Iuris\Model\AnalysisDetail;
use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\Flag;

class UnconsentedTrackingCookiesPlugin implements IPlugin
{
    private $trackingCookies = [
        '_ga' => 1,
        '_gid' => 1
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
        $unconsentedTrackingCookies = [];
        
        $cookies = $request->getWebdriver()->manage()->getCookies();
        foreach($cookies as $cookie)
        {
            if(isset($this->trackingCookies[$cookie->getName()]))
            {
                $unconsentedTrackingCookies[] = $cookie->getName();
            }
        }
        
        if(count($unconsentedTrackingCookies) === 0)
        {
            $score = 100;
            $message = 'No tracking cookies found';
        }
        else
        {
            $score = 0;
            $message = 'Tracking cookies found: ' . implode(', ', $unconsentedTrackingCookies);
        }
        
        return new AnalysisDetail(
            $this->getName(),
            Flag::Scorable,
            $score,
            $message);
    }
}