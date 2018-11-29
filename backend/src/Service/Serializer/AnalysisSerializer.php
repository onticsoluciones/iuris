<?php

namespace Ontic\Iuris\Service\Serializer;

use Ontic\Iuris\Model\Analysis;
use Ontic\Iuris\Model\AnalysisDetail;

class AnalysisSerializer
{
    /**
     * @param Analysis $analysis
     * @return array
     */
    public function serialize(Analysis $analysis)
    {
        return [
            'url' => $analysis->getUrl(),
            'score' => $analysis->getGlobalScore(),
            'started_at' => $analysis->getStartedAt()->format('Y-m-d H:i:s'),
            'finished_at' => $analysis->getFinishedAt()->format('Y-m-d H:i:s'),
            'details' => array_map(function(AnalysisDetail $detail)
            {
                return static::serializeDetail($detail);
            }, $analysis->getDetails())
        ];
    }

    /**
     * @param AnalysisDetail $detail
     * @return array
     */
    private static function serializeDetail(AnalysisDetail $detail)
    {
        return [
            'analyzer' => $detail->getAnalyzer(),
            'flags' => $detail->getFlags(),
            'score' => $detail->getScore(),
            'message' => $detail->getMessage(),
            'started_at' => $detail->getStartedAt()->format('Y-m-d H:i:s'),
            'finished_at' => $detail->getFinishedAt()->format('Y-m-d H:i:s')
        ];
    }
}