<?php

namespace Ontic\Iuris\Service;

use Ontic\Iuris\Interfaces\IPlugin;
use Symfony\Component\Yaml\Yaml;

class PluginConfigurationLoader
{
    /** @var string */
    private $configDir;

    /**
     * @param string $configDir
     */
    public function __construct($configDir)
    {
        $this->configDir = $configDir;
    }

    /**
     * @param IPlugin $plugin
     * @return array
     */
    public function loadConfigForPlugin($plugin)
    {
        $fullyQualifiedClassName = get_class($plugin);
        $fragments = explode('\\', $fullyQualifiedClassName);
        $className = $fragments[count($fragments) - 1];
        
        $filePath = sprintf('%s/%s.yml', $this->configDir, $className);
        
        if(!file_exists($filePath))
        {
            return [];
        }
        
        return Yaml::parseFile($filePath);
    }
}