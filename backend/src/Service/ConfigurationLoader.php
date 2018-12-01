<?php

namespace Ontic\Iuris\Service;

use Ontic\Iuris\Model\Configuration;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader
{
    /** @var string */
    private $configFile;

    /**
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * @return Configuration
     */
    public function load()
    {
        $data = Yaml::parseFile($this->configFile);
        
        return new Configuration(
            $data['database']['host'],
            $data['database']['name'],
            $data['database']['user'],
            $data['database']['password'],
            $data['selenium']['host'],
            $data['general']['cache']
        );
    }
}