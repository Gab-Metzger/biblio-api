<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Src\System\DatabaseConnector;

if (file_exists(__DIR__ . '/.env')) {
  $dotenv = new DotEnv(__DIR__);
  $dotenv->load();
}

$dbConnection = (new DatabaseConnector())->getConnection();
