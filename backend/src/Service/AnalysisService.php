<?php

namespace Ontic\Iuris\Service;

use Ontic\Iuris\Service\Repository\AnalysisRepository;
use Ontic\Iuris\Service\Serializer\AnalysisSerializer;

class AnalysisService
{
    /** @var WebsiteAnalyzer */
    private $analyzer;
    /** @var AnalysisRepository */
    private $repository;
    /** @var AnalysisSerializer */
    private $serializer;

    /**
     * @param WebsiteAnalyzer $analyzer
     * @param AnalysisRepository $repository
     * @param AnalysisSerializer $serializer
     */
    public function __construct
    (
        WebsiteAnalyzer $analyzer, 
        AnalysisRepository $repository, 
        AnalysisSerializer $serializer
    )
    {
        $this->analyzer = $analyzer;
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param string $url
     * @param string[] $selectedPlugins
     * @return string
     * @throws \Exception
     */
    public function processUrl($url, $selectedPlugins)
    {
        // Perform analysis
        $analysis = $this->analyzer->analyze($url, $selectedPlugins);

        // Save the results to the database
        $analysisId = $this->repository->save($analysis);

        // Serve the results as a JSON object
        $analysisData = $this->serializer->serialize($analysis);
        $analysisData['id'] = $analysisId;
        return json_encode($analysisData);
    }
}