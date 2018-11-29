<?php

namespace Ontic\Iuris\Model;

class Analysis
{
    /** @var string */
    private $url;
    /** @var \DateTime */
    private $date;
    /** @var AnalysisDetail[] */
    private $details;

    /**
     * @param string $url
     * @param \DateTime $date
     * @param AnalysisDetail[] $results
     */
    public function __construct($url, \DateTime $date, array $results)
    {
        $this->url = $url;
        $this->date = $date;
        $this->details = $results;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return AnalysisDetail[]
     */
    public function getDetails()
    {
        return $this->details;
    }
}