<?php

namespace Ontic\Iuris\Service;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverPlatform;
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
        $analysis = new Analysis($url, [], new \DateTime(), null);
        
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--headless']);
        $capabilities = new DesiredCapabilities([
            WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::CHROME,
            WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
        ]);
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
        
        $driver = RemoteWebDriver::create($this->configuration->getSeleniumHost(), $capabilities);
        $driver->get($url);
        
        $analysisRequest = new AnalysisRequest($driver, $analysis);
        foreach($this->pluginLoader->findAll() as $scanner)
        {
            // Run analyzer
            $start = new \DateTime();
            $result = $scanner->analyze($analysisRequest);
            $result = $result->setStartedAt($start)->setFinishedAt(new \DateTime());
            
            // Append obtained result to the global analysis
            $analysis = $analysis->addDetails($result);
            
            if($result->getFlags() & Flag::StopProcessing)
            {
                break;
            }
        }
        $driver->close();
        
        return $analysis->setFinishedAt(new \DateTime());
    }
}