<?php

namespace Ontic\Iuris\Model;

use Facebook\WebDriver\WebDriver;

class AnalysisRequest
{
    /** @var WebDriver */
    private $webdriver;
    /**
     * @var Analysis
     */
    private $analysis;

    /**
     * @param WebDriver $webdriver
     * @param Analysis $analysis
     */
    public function __construct
    (
        WebDriver $webdriver,
        Analysis $analysis
    )
    {
        $this->webdriver = $webdriver;
        $this->analysis = $analysis;
    }

    /**
     * @return WebDriver
     */
    public function getWebdriver()
    {
        return $this->webdriver;
    }

    /**
     * @return Analysis
     */
    public function getAnalysis()
    {
        return $this->analysis;
    }
}