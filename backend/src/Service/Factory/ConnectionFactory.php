<?php

namespace Ontic\Iuris\Service\Factory;

use Ontic\Iuris\Model\Configuration;
use Ontic\Iuris\Model\Connection;

class ConnectionFactory
{
    /** @var Configuration */
    private $configuration;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return Connection
     */
    public function get()
    {
        $dsn = sprintf('mysql:dbname=%s;host=%s', 
            $this->configuration->getDbName(), 
            $this->configuration->getDbHost()
        );
        
        $connection = new Connection($dsn, $this->configuration->getDbUser(), $this->configuration->getDbPassword());
        $connection->setAttribute(Connection::ERRMODE_EXCEPTION, true);
        return $connection;
    }
}