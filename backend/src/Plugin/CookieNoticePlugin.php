<?php

namespace Ontic\Iuris\Plugin;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Ontic\Iuris\Interfaces\IPlugin;
use Ontic\Iuris\Model\AnalysisDetail;
use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\Flag;

class CookieNoticePlugin implements IPlugin
{
    /**
     * @return string
     */
    function getCode()
    {
        return 'cookie_notice';
    }
    
    /**
     * @return string
     */
    function getShortName()
    {
        return 'Cookie Notice';
    }

    /**
     * @param AnalysisRequest $request
     * @param array $config
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request, array $config)
    {
        $score = 0;
        $message = '✗ No se ha encontrado aviso de cookies en la web. Es obligatorio que el sitio web muestre un aviso para que el usuario acepte las cookies, asi como de disponer un enlace a la politica de cookies donde deben estar informadas las cookies que el sitio web envia al usuario, el nombre de la cookie y su tipo. Puede seguir la guia de https://www.aepd.es/herramientas/facilita.html donde encontrará una guia paso a paso para elaborarla';
        
        foreach($this->getLinks($config['links']) as $link)
        {
            try
            {
                $xpath = "//a[substring(@href, string-length(@href) - string-length('$link') +1) = '$link']";
                $request->getWebdriver()->findElement(WebDriverBy::xpath($xpath));
                $score = 100;
                $message = '✓ El sitio muestra aviso de cookies..';
            } 
            /** @noinspection PhpRedundantCatchClauseInspection */
            catch (NoSuchElementException $ignored)
            {
            }
        }

        return new AnalysisDetail(
            $this->getCode(),
            Flag::Scorable,
            $score,
            $message
        );
    }
    
    private function getLinks($links)
    {
        foreach($links as $link)
        {
            yield $link;
            yield $link . '/';
        }
    }

}