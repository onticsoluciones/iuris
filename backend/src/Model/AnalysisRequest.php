<?php

namespace Ontic\Iuris\Model;

use Facebook\WebDriver\WebDriver;

class AnalysisRequest
{
    /** @var WebDriver */
    private $webdriver;

    /**
     * @param WebDriver $webdriver
     */
    public function __construct(WebDriver $webdriver)
    {
        $this->webdriver = $webdriver;
    }

    /**
     * @return WebDriver
     */
    public function getWebdriver()
    {
        return $this->webdriver;
    }
}