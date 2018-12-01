<?php

use Ontic\Iuris\Service\Factory\ContainerFactory;
use Ontic\Iuris\Service\Renderer\OdtRenderer;
use Ontic\Iuris\Service\Repository\AnalysisRepository;

require_once __DIR__ . '/../vendor/autoload.php';

try
{
    $analysisId = $_GET['id'];
    
    $container = ContainerFactory::get();
    /** @var AnalysisRepository $repository */
    $repository = $container->get(AnalysisRepository::class);
    /** @var OdtRenderer $renderer */
    $renderer = ContainerFactory::get()->get(OdtRenderer::class);
    
    if(!($analysis = $repository->load($analysisId)))
    {
        header('HTTP/1.1 404 Not Found');
        die;
    }
    
    header('Content-Type: application/vnd.oasis.opendocument.text');
    echo $renderer->getOdt($analysis);
    die;
}
catch (Exception $e)
{
    header('HTTP/1.1 500 Internal Server Error');
    die;
}

