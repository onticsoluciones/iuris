<?php

namespace Ontic\Iuris\Service;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Ontic\Iuris\Model\Analysis;
use Ontic\Iuris\Model\AnalysisRequest;
use Ontic\Iuris\Model\Configuration;
use Ontic\Iuris\Model\Flag;

class WebsiteAnalyzer
{
    /** @var PluginLoader */
    private $pluginLoader;
    /** @var Configuration */
    private $configuration;

    /**
     * @param PluginLoader $pluginLoader
     * @param Configuration $configuration
     */
    public function __construct
    (
        PluginLoader $pluginLoader,
        Configuration $configuration
    )
    {
        $this->pluginLoader = $pluginLoader;
        $this->configuration = $configuration;
    }

    /**
     * @param string $url
     * @return Analysis
     * @throws \Exception
     */
    public function analyze($url)
    {
        $results = [];

        $driver = RemoteWebDriver::create($this->configuration->getSeleniumHost(), DesiredCapabilities::chrome());
        $driver->get($url);
        
        $analysisRequest = new AnalysisRequest($driver);
        foreach($this->pluginLoader->findAll() as $scanner)
        {
            $result = $scanner->analyze($analysisRequest);
            $results[] = $result;
            if($result->getFlags() & Flag::StopProcessing)
            {
                break;
            }
        }
        $driver->close();
        
        return new Analysis($url, new \DateTime(), $results);
    }
}