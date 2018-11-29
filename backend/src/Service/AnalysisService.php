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
     * @return string
     * @throws \Exception
     */
    public function processUrl($url)
    {
        // Perform analysis
        $analysis = $this->analyzer->analyze($url);

        // Save the results to the database
        $this->repository->save($analysis);

        // Serve the results as a JSON object
        return json_encode($this->serializer->serialize($analysis));
    }
}