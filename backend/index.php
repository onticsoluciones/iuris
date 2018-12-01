<?php

require_once('vendor/autoload.php');

use Ontic\Iuris\Service\AnalysisService;
use Ontic\Iuris\Service\Factory\ContainerFactory;

try
{
    $url = $_GET['url'];
    $selectedPlugins = explode(',', $_GET['selected_plugins']);
    
    // Add the http:// prefix if needed
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) 
    {
        $url = "http://" . $url;
    }
    
    // Validate the URL
    if (filter_var($url, FILTER_VALIDATE_URL) === false) 
    {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Invalid URL']);
        die;
    }
    
    /** @var AnalysisService $service */
    $service = ContainerFactory::get()->get(AnalysisService::class);
    $responseBody = $service->processUrl($url, $selectedPlugins);
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo $responseBody;
    die;
}
catch (Exception $e)
{
    error_log($e);
    header('HTTP/1.1 500 Internal Server Error');
}

