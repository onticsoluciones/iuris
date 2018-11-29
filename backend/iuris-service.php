<?php

require_once('vendor/autoload.php');

use Ontic\Iuris\Service\Factory\ContainerFactory;
use Ontic\Iuris\Service\Repository\AnalysisRepository;
use Ontic\Iuris\Service\WebsiteAnalyzer;

$container = ContainerFactory::get();
/** @var WebsiteAnalyzer $scanner */
$scanner = $container->get(WebsiteAnalyzer::class); 
/** @var AnalysisRepository $repo */
$repo = $container->get(AnalysisRepository::class);


$analysis = $scanner->analyze('https://www.google.com');
$repo->save($analysis);
