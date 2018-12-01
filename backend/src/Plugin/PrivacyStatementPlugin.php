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
    function getCode()
    {
        return 'privacy_statement';
    }

    /**
     * @return string
     */
    function getShortName()
    {
        return 'Privacy Statement';
    }

    /**
     * @param AnalysisRequest $request
     * @param array $config
     * @return AnalysisDetail
     */
    function analyze(AnalysisRequest $request, array $config)
    {
        $score = 0;
        $message = '✗ No se ha encontrado política de privacidad. La política de privacidad es fundamental ya que en ella se determina los datos que se recogen, el tiempo que se conservan, a quien dirigirse en caso de querer ejercer los derechos de acceso, rectificacion, oposicion, cancelacion, portabilidad, olvido ...';

        foreach($this->getLinks($config['links']) as $link)
        {
            try
            {
                $xpath = "//a[substring(@href, string-length(@href) - string-length('$link') +1) = '$link']";
                $request->getWebdriver()->findElement(WebDriverBy::xpath($xpath));
                $score = 100;
                $message = '✓ El sito contiene política de privacidad.';
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