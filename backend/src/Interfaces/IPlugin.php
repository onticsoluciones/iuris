<?php

namespace Ontic\Iuris\Interfaces;

use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\AnalysisDetail;

interface IPlugin
{
    /**
     * @return string
     */
    function getName();
        
    /**
     * @param AnalysisRequest $request
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request);
}