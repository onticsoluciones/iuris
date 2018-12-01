<?php

use Ontic\Iuris\Service\Factory\ContainerFactory;
use Ontic\Iuris\Service\PluginLoader;

require_once __DIR__ . '/../vendor/autoload.php';

try
{
    $analysisId = $_GET['id'];
    
    $container = ContainerFactory::get();
    /** @var PluginLoader $pluginLoader */
    $pluginLoader = $container->get(PluginLoader::class);
    
    $data = [];
    foreach($pluginLoader->findAll() as $plugin)
    {
        $data[$plugin->getCode()] = $plugin->getShortName();
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
    die;
}
catch (Exception $e)
{
    header('HTTP/1.1 500 Internal Server Error');
    die;
}

