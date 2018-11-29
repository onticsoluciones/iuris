<?php

namespace Ontic\Iuris\Model;

class AnalysisDetail
{
    /** @var string */
    private $analyzer;
    /** @var int */
    private $flags;
    /** @var int */
    private $score;
    /** @var string */
    private $message;
    /** @var \DateTime */
    private $startedAt;
    /** @var \DateTime */
    private $finishedAt;

    /**
     * @param string $analyzer
     * @param int $flags
     * @param int $score
     * @param string $message
     * @param \DateTime $startedAt
     * @param \DateTime $finishedAt
     */
    public function __construct
    (
        $analyzer,
        $flags,
        $score,
        $message,
        \DateTime $startedAt = null,
        \DateTime $finishedAt = null
    )
    {
        $this->flags = $flags;
        $this->score = $score;
        $this->message = $message;
        $this->startedAt = $startedAt;
        $this->finishedAt = $finishedAt;
        $this->analyzer = $analyzer;
    }

    /**
     * @return string
     */
    public function getAnalyzer()
    {
        return $this->analyzer;
    }

    /**
     * @return int
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
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
     * @param \DateTime $startedAt
     * @return AnalysisDetail
     */
    public function setStartedAt(\DateTime $startedAt)
    {
        return new AnalysisDetail(
            $this->analyzer,
            $this->flags,
            $this->score,
            $this->message,
            $startedAt,
            $this->finishedAt
        );
    }

    /**
     * @param \DateTime $finishedAt
     * @return AnalysisDetail
     */
    public function setFinishedAt(\DateTime $finishedAt)
    {
        return new AnalysisDetail(
            $this->analyzer,
            $this->flags,
            $this->score,
            $this->message,
            $this->startedAt,
            $finishedAt
        );
    }
}