<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Src\System\DatabaseConnector;
use Src\TableGateways\ReaderGateway;


$dotenv = new DotEnv(__DIR__);
$dotenv->load();


$dbConnection = (new DatabaseConnector())->getConnection();
$readerGateway = new ReaderGateway($dbConnection);

$result = $readerGateway->findAll();

var_dump($result);