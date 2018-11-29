<?php

namespace Ontic\Iuris\Plugin;

use Ontic\Iuris\Interfaces\IPlugin;
use Ontic\Iuris\Model\AnalysisDetail;
use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\Flag;

class DummyPlugin implements IPlugin
{
    /**
     * @param AnalysisRequest $request
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request)
    {
        return new AnalysisDetail(
            $this->getName(), 
            Flag::Scorable, 
            100, 
            'Todo correcto'
        );
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'dummy';
    }
}