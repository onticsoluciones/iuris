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
        
        $sql = 'INSERT INTO analysis(url, created) VALUES(:url, :created);';
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'url' => $analysis->getUrl(),
            'created' => $analysis->getDate()->format('Y-m-d H:i:s')
        ]);
        $analysisId = $this->connection->lastInsertId();
        
        $sql = '
            INSERT INTO analysis_detail(analysis_id, flags, score, message) 
            VALUES(:analysis_id, :flags, :score, :message);';
        $statement = $this->connection->prepare($sql);
        foreach($analysis->getDetails() as $detail)
        {
            $statement->execute([
                'analysis_id' => $analysisId,
                'flags' => $detail->getFlags(),
                'score' => $detail->getScore(),
                'message' => $detail->getMessage()
            ]);
        }
        
        $this->connection->commit();
    }
}