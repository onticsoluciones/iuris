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
    public function getShortName()
    {
        return 'Confianza Online';
    }

    /**
     * @return string
     */
    function getCode()
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
            $message = '✓ El sitio web está dentro del programa de Confianza Online.';
        } 
        /** @noinspection PhpRedundantCatchClauseInspection */ 
        catch(NoSuchElementException $ignored)
        {
            $score = 0;
            $message = '⚠ Es recomendable formar parte del programa Confianza Online para aumentar la reputación del sitio web. Recomendamos visitar la web https://www.confianzaonline.es/empresas/unete-a-confianza-online/ para verificar los requisitos y adherirse al programa y adquirir el sello para incrustar en la web.';
        }
        
        return new AnalysisDetail(
            $this->getCode(),
            0,
            $score,
            $message
        );
    }
}