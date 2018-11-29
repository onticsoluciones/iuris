<?php

namespace Ontic\Iuris\Service;

use DirectoryIterator;
use Ontic\Iuris\Interfaces\IPlugin;

class PluginLoader
{
    /** @var string */
    private $pluginDir;

    /**
     * @param string $pluginDir
     */
    public function __construct($pluginDir)
    {
        $this->pluginDir = $pluginDir;
    }

    /**
     * @return IPlugin[]
     */
    public function findAll()
    {
        $plugins = [];
        $namespace = 'Ontic\\Iuris\\Plugin\\';
        
        foreach(new DirectoryIterator($this->pluginDir) as $fileInfo) 
        {
            if($fileInfo->isDot())
            {
                continue;
            }
            
            $fullyQualifiedClassName = $namespace . $fileInfo->getBasename('.php');
            
            if(!class_exists($fullyQualifiedClassName))
            {
                continue;
            }

            if(!in_array(IPlugin::class, class_implements($fullyQualifiedClassName)))
            {
                continue;
            }
            
            /** @var IPlugin $instance */
            $plugins[] = new $fullyQualifiedClassName();
        }
        
        return $plugins;
    }
}