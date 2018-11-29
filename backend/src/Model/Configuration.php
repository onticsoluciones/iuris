<?php

namespace Ontic\Iuris\Model;

class Configuration
{
    /** @var string */
    private $dbHost;
    /** @var string */
    private $dbName;
    /** @var string */
    private $dbUser;
    /** @var string */
    private $dbPassword;
    /** @var string */
    private $seleniumHost;

    /**
     * @param string $dbHost
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     * @param $seleniumHost
     */
    public function __construct($dbHost, $dbName, $dbUser, $dbPassword, $seleniumHost)
    {
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->seleniumHost = $seleniumHost;
    }

    /**
     * @return string
     */
    public function getDbHost()
    {
        return $this->dbHost;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @return string
     */
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * @return string
     */
    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    /**
     * @return string
     */
    public function getSeleniumHost()
    {
        return $this->seleniumHost;
    }
}