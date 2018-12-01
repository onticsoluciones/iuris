<?php

use Ontic\Iuris\Model\Connection;
use Ontic\Iuris\Service\Factory\ContainerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$container = ContainerFactory::get();
/** @var Connection $connection */
$connection = $container->get(Connection::class);

$sql = 'SELECT COUNT(1) FROM analysis;';
$statement = $connection->prepare($sql);
$statement->execute();

echo $statement->fetch()[0];


