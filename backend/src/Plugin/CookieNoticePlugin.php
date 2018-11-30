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
    private $links = [
        '/politica-de-cookies',
        '/cookies',
        '/plugins/asesor-cookies-para-la-ley-en-espana',
        '/politica_cookies-es.php',
        '/politica-cookies',
        '/index.php/politica-de-cookies',
        '/politica-de-cookies.html',
        '/politica_cookies'
    ];
    
    /**
     * @return string
     */
    function getName()
    {
        return 'cookie_notice';
    }

    /**
     * @param AnalysisRequest $request
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request)
    {
        $score = 0;
        $message = '✗ No cookie notice page was found.';
        
        foreach($this->getLinks() as $link)
        {
            try
            {
                $xpath = "//a[substring(@href, string-length(@href) - string-length('$link') +1) = '$link']";
                $request->getWebdriver()->findElement(WebDriverBy::xpath($xpath));
                $score = 100;
                $message = '✓ Site contains a cookie notice.';
            } 
            /** @noinspection PhpRedundantCatchClauseInspection */
            catch (NoSuchElementException $ignored)
            {
            }
        }

        return new AnalysisDetail(
            $this->getName(),
            Flag::Scorable,
            $score,
            $message
        );
    }
    
    private function getLinks()
    {
        foreach($this->links as $link)
        {
            yield $link;
            yield $link . '/';
        }
    }
}