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
use Ontic\Iuris\Service\Repository\AnalysisRepository;

class WebsiteAnalyzer
{
    /** @var PluginLoader */
    private $pluginLoader;
    /** @var PluginConfigurationLoader */
    private $pluginConfigurationLoader;
    /** @var Configuration */
    private $configuration;
    /** @var AnalysisRepository */
    private $analysisRepository;

    /**
     * @param PluginLoader $pluginLoader
     * @param PluginConfigurationLoader $pluginConfigurationLoader
     * @param Configuration $configuration
     * @param AnalysisRepository $analysisRepository
     */
    public function __construct
    (
        PluginLoader $pluginLoader,
        PluginConfigurationLoader $pluginConfigurationLoader,
        Configuration $configuration,
        AnalysisRepository $analysisRepository
    )
    {
        $this->pluginLoader = $pluginLoader;
        $this->configuration = $configuration;
        $this->pluginConfigurationLoader = $pluginConfigurationLoader;
        $this->analysisRepository = $analysisRepository;
    }

    /**
     * @param string $url
     * @return Analysis
     * @throws \Exception
     */
    public function analyze($url)
    {
        if($age = $this->configuration->getCache())
        {
            $analysis = $this->analysisRepository->find($url, $age);
            if($analysis !== null)
            {
                return $analysis;
            }
        }
        
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
            // Load plugin configuration
            $configuration = $this->pluginConfigurationLoader->loadConfigForPlugin($scanner);
            
            // Run analyzer
            $start = new \DateTime();
            $result = $scanner->analyze($analysisRequest, $configuration);
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