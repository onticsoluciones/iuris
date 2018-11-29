<?php

namespace Ontic\Iuris\Model;

class AnalysisDetail
{
    /** @var int */
    private $flags;
    /** @var int */
    private $score;
    /** @var string */
    private $message;

    /**
     * @param int $flags
     * @param int $score
     * @param string $message
     */
    public function __construct($flags, $score, $message)
    {
        $this->flags = $flags;
        $this->score = $score;
        $this->message = $message;
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
}