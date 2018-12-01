<?php

namespace Ontic\Iuris\Plugin;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Ontic\Iuris\Interfaces\IPlugin;
use Ontic\Iuris\Model\AnalysisDetail;
use Ontic\Iuris\Model\AnalysisRequest;

class ConfianzaOnlinePlugin implements IPlugin
{
    /**
     * @return string
     */
    function getName()
    {
        return 'confianza_online';
    }

    /**
     * @param AnalysisRequest $request
     * @param array $config
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request, array $config)
    {
        try
        {
            $xpath = "//a[starts-with(@href, 'https://www.confianzaonline.es/empresas/')]";
            $request->getWebdriver()->findElement(WebDriverBy::xpath($xpath));
            $score = 100;
            $message = '✓ Website enrolled in the Confianza Online program.';
        } 
        /** @noinspection PhpRedundantCatchClauseInspection */ 
        catch(NoSuchElementException $ignored)
        {
            $score = 0;
            $message = '⚠ Enroll in the Confianza Online program to increase your website reputation.';
        }
        
        return new AnalysisDetail(
            $this->getName(),
            0,
            $score,
            $message
        );
    }
}