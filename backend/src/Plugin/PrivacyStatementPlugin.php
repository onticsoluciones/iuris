<?php

namespace Ontic\Iuris\Plugin;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Ontic\Iuris\Interfaces\IPlugin;
use Ontic\Iuris\Model\AnalysisDetail;
use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\Flag;

class PrivacyStatementPlugin implements IPlugin
{
    /**
     * @return string
     */
    function getName()
    {
        return 'privacy_statement';
    }

    /**
     * @param AnalysisRequest $request
     * @param array $config
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request, array $config)
    {
        $score = 0;
        $message = '✗ No privacy statement was found.';

        foreach($this->getLinks($config['links']) as $link)
        {
            try
            {
                $xpath = "//a[substring(@href, string-length(@href) - string-length('$link') +1) = '$link']";
                $request->getWebdriver()->findElement(WebDriverBy::xpath($xpath));
                $score = 100;
                $message = '✓ Site contains a privacy statement.';
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

    private function getLinks($links)
    {
        foreach($links as $link)
        {
            yield $link;
            yield $link . '/';
        }
    }
}