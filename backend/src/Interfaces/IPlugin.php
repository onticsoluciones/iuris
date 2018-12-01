<?php

namespace Ontic\Iuris\Interfaces;

use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\AnalysisDetail;

interface IPlugin
{
    /**
     * @return string
     */
    function getCode();

    /**
     * @return string
     */
    function getShortName();

    /**
     * @param AnalysisRequest $request
     * @param array $config
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request, array $config);
}