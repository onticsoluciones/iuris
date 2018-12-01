<?php

namespace Ontic\Iuris\Plugin;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Ontic\Iuris\Interfaces\IPlugin;
use Ontic\Iuris\Model\AnalysisDetail;
use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\Flag;

class LssiPlugin implements IPlugin
{
    /**
     * @return string
     */
    function getCode()
    {
        return 'lssi';
    }

    /**
     * @return string
     */
    function getShortName()
    {
        return 'LSSI';
    }

    /**
     * @param AnalysisRequest $request
     * @param array $config
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request, array $config)
    {
        $score = 0;
        $message = '✗ El sitio web no cumple con la LSSI. Es obligatorio si la web tiene actividad lucrativa, como por ejemplo un comercio electronico, una web de contratacion de productos o servicios, etc que dentro de su aviso legal se especifique que cumple con la normativa LSSI (34/2002). En ella se deberá indicar los datos del registro mercantil, haciendo hincapié en la actividad de la empresa, CIF, dirección para dar visibilidad a los usuarios.  ';
        
        $webdriver = $request->getWebdriver();
        if($legalNoticePath = $this->getLegalNoticeUrl($webdriver, $config['urls']))
        {
            $originalUrl = $webdriver->getCurrentURL();
            $newUrl = $originalUrl . $legalNoticePath;
            $webdriver->get($newUrl);
            
            $legalNoticeSource = $webdriver->getPageSource();
            foreach($config['keywords'] as $keyword)
            {
                if(stripos($legalNoticeSource, $keyword) !== false)
                {
                    $score = 100;
                    $message = '✓ El sitio web cumple con LSSI. ';
                    break;
                }
            }
            
            $webdriver->get($originalUrl);
        }
        
        return new AnalysisDetail(
            $this->getCode(),
            Flag::Scorable,
            $score,
            $message
        );
    }

    /**
     * @param WebDriver $driver
     * @param string[] $urls
     * @return string|null
     */
    private function getLegalNoticeUrl(WebDriver $driver, $urls)
    {
        foreach($this->getLinks($urls) as $url)
        {
            try
            {
                $xpath = "//a[substring(@href, string-length(@href) - string-length('$url') +1) = '$url']";
                $driver->findElement(WebDriverBy::xpath($xpath));
                return $url;
            }
            /** @noinspection PhpRedundantCatchClauseInspection */
            catch (NoSuchElementException $ignored)
            {
            }
        }
        
        return null;
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