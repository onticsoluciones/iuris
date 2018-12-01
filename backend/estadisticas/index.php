<?php

use Ontic\Iuris\Model\Connection;
use Ontic\Iuris\Service\Factory\ContainerFactory;

require_once __DIR__ . '/../vendor/autoload.php';

//array $result;

$container = ContainerFactory::get();
/** @var Connection $connection */
$connection = $container->get(Connection::class);

//Total webs escaneadas
$sql = 'SELECT COUNT(*) FROM analysis;';
$statement = $connection->prepare($sql);
$statement->execute();
$analysisCount = $statement->fetch()[0];
$result['Total web escaneadas'] = $analysisCount;

//Total Modulos escaneados
$sql2 = 'SELECT COUNT(*) FROM analysis_detail;';
$statement2 = $connection->prepare($sql2);
$statement2->execute();
$result['Total de plugins lanzados'] = ($statement2->fetch()[0]);

//Total de plugin de lssi
$sql3 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'lssi\' AND score=100;';
$statement3 = $connection->prepare($sql3);
$statement3->execute();
$result['Webs con cumplimiento LSSI'] = $statement3->fetch()[0];

//Total de plugins que han obtenido una puntuacion de 100%
$sql4 = 'SELECT COUNT(1) FROM analysis_detail where score=100;';
$statement4 = $connection->prepare($sql4);
$statement4->execute();
$result['Plugins con puntuación 100%'] = $statement4->fetch()[0];

//Total de plugin de ssl
$sql5 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'ssl_certificate\' AND score=100;';
$statement5 = $connection->prepare($sql5);
$statement5->execute();
$result['Webs con SSL válido'] = $statement5->fetch()[0];

//Total confianza online 100%
$sql6 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'confianza_online\' AND score=100;';
$statement6 = $connection->prepare($sql6);
$statement6->execute();
$result['Webs con confianza online'] = $statement6->fetch()[0];

//Politica de privacidad 100%
$sql7 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'privacy_statement\' AND score=100;';
$statement7 = $connection->prepare($sql7);
$statement7->execute();
$result['Webs con politica de privacidad correcta'] = $statement7->fetch()[0];

//Politica de cookies
$sql8 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'cookie_notice\' AND score=100;';
$statement8 = $connection->prepare($sql8);
$statement8->execute();
$result['Webs politica de cookies correcta'] = $statement8->fetch()[0];


//Cookies ok
$sql9 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'unconsented_tracking_cokies\' AND score=100;';
$statement9 = $connection->prepare($sql9);
$statement9->execute();
$result['Sin cookies o son inocuas'] = $statement9->fetch()[0];

//Cookies desconocidas
$sql10 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'unconsented_tracking_cokies\' AND score=0;';
$statement10 = $connection->prepare($sql10);
$statement10->execute();
$result['Cookies sin consentimiento o malignas'] = $statement10->fetch()[0];


//Webs sin politica de privacidad
$sql11 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'privacy_statement\' AND score=0;';
$statement11 = $connection->prepare($sql11);
$statement11->execute();
$result['Webs sin politica de  privacidad'] = $statement11->fetch()[0];


//SIN plugin SSL
$sql12 = 'SELECT count(*) FROM analysis_detail WHERE analyzer=\'ssl_certificate\' AND score=0;';
$statement12 = $connection->prepare($sql12);
$statement12->execute();
$result['Webs sin SSL'] = $statement12->fetch()[0];

//salida a json
header('Content-Type: application/json');
echo json_encode($result);
exit();

?>




