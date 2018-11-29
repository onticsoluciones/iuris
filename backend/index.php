<?php

require_once('vendor/autoload.php');

use Ontic\Iuris\Service\AnalysisService;
use Ontic\Iuris\Service\Factory\ContainerFactory;

try
{
    $url = $_GET['url'];
    /** @var AnalysisService $service */
    $service = ContainerFactory::get()->get(AnalysisService::class);
    $responseBody = $service->processUrl($url);
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo $responseBody;
    die;
}
catch (Exception $e)
{
    header('HTTP/1.1 500 Internal Server Error');
}

