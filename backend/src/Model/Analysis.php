<?php

namespace Ontic\Iuris\Model;

class Analysis
{
    /** @var string */
    private $url;
    /** @var AnalysisDetail[] */
    private $details;
    /** @var \DateTime */
    private $startedAt;
    /** @var \DateTime */
    private $finishedAt;

    /**
     * @param string $url
     * @param AnalysisDetail[] $results
     * @param \DateTime $startedAt
     * @param \DateTime $finishedAt
     */
    public function __construct
    (
        $url, 
        array $results, 
        \DateTime $startedAt = null, 
        \DateTime $finishedAt = null
    )
    {
        $this->url = $url;
        $this->details = $results;
        $this->startedAt = $startedAt;
        $this->finishedAt = $finishedAt;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return AnalysisDetail[]
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }
    
    /**
     * @param AnalysisDetail $detail
     * @return Analysis
     */
    public function addDetails(AnalysisDetail $detail)
    {
        $details = $this->details;
        $details[] = $detail;
        
        return new Analysis($this->url, $details, $this->startedAt, $this->finishedAt);
    }

    /**
     * @param \DateTime $finishedAt
     * @return Analysis
     */
    public function setFinishedAt(\DateTime $finishedAt)
    {
        return new Analysis($this->url, $this->details, $this->startedAt, $finishedAt);
    }

    /**
     * @return float|int
     */
    public function getGlobalScore()
    {
        $scorableLines = 0;
        $totalScore = 0;
        foreach($this->getDetails() as $detail)
        {
            if($detail->getFlags() & Flag::Scorable)
            {
                $scorableLines++;
                $totalScore += $detail->getScore();
            }
        }
        
        return round($totalScore / $scorableLines);
    }
}