<?php

namespace Ontic\Iuris\Service\Repository;

use Ontic\Iuris\Model\Analysis;
use Ontic\Iuris\Model\Connection;

class AnalysisRepository
{
    /** @var Connection */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Analysis $analysis
     */
    public function save(Analysis $analysis)
    {
        $this->connection->beginTransaction();
        
        $sql = 'INSERT INTO analysis(url, started_at, finished_at) VALUES(:url, :started_at, :finished_at);';
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'url' => $analysis->getUrl(),
            'started_at' => $analysis->getStartedAt()->format('Y-m-d H:i:s'),
            'finished_at' => $analysis->getFinishedAt()->format('Y-m-d H:i:s'),
        ]);
        $analysisId = $this->connection->lastInsertId();
        
        $sql = '
            INSERT INTO analysis_detail(analysis_id, analyzer, flags, score, message, started_at, finished_at) 
            VALUES(:analysis_id, :analyzer, :flags, :score, :message, :started_at, :finished_at);';
        $statement = $this->connection->prepare($sql);
        foreach($analysis->getDetails() as $detail)
        {
            $statement->execute([
                'analysis_id' => $analysisId,
                'analyzer' => $detail->getAnalyzer(),
                'flags' => $detail->getFlags(),
                'score' => $detail->getScore(),
                'message' => $detail->getMessage(),
                'started_at' => $detail->getStartedAt()->format('Y-m-d H:i:s'),
                'finished_at' => $detail->getFinishedAt()->format('Y-m-d H:i:s'),
            ]);
        }
        
        $this->connection->commit();
    }
}