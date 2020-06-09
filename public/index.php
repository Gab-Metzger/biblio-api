<?php
require "../bootstrap.php";

use Src\Controller\ReaderController;
use Src\Controller\LendingController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ($uri[2] !== 'reader' && $uri[2] !== 'lending' && $uri[2] !== 'book') {
  header("HTTP/1.1 404 Not Found");
  exit();
}

$userId = null;
if (isset($uri[3])) {
  $userId = (int) $uri[3];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($uri[2]) {
  case 'reader':
      $controller = new ReaderController($dbConnection, $requestMethod, $userId);
      $controller->processRequest();
      break;
  case 'lending':
    $controller = new LendingController($dbConnection, $requestMethod, $userId);
    $controller->processRequest();
  break;
  default:
      header("HTTP/1.1 404 Not Found");
      exit();
      break;
}
