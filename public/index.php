<?php
require "../bootstrap.php";

use Src\Controller\ReaderController;
use Src\Controller\LendingController;
use Src\Controller\BookController;

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

if (!getenv('BYPASS_AUTH') && !authenticate()) {
  header("HTTP/1.1 401 Unauthorized");
  exit('Unauthorized');
}

$entityId = null;
if (isset($uri[3])) {
  $entityId = (int) $uri[3];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($uri[2]) {
  case 'reader':
    $controller = new ReaderController($dbConnection, $requestMethod, $entityId);
    $controller->processRequest();
    break;
  case 'lending':
    $controller = new LendingController($dbConnection, $requestMethod, $entityId);
    $controller->processRequest();
    break;
  case 'book':
    $controller = new BookController($dbConnection, $requestMethod, $entityId);
    $controller->processRequest();
    break;
  default:
    header("HTTP/1.1 404 Not Found");
    exit();
    break;
}

function authenticate()
{
  try {
    switch (true) {
      case array_key_exists('HTTP_AUTHORIZATION', $_SERVER):
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        break;
      case array_key_exists('Authorization', $_SERVER):
        $authHeader = $_SERVER['Authorization'];
        break;
      default:
        $authHeader = null;
        break;
    }
    preg_match('/Bearer\s(\S+)/', $authHeader, $matches);
    if (!isset($matches[1])) {
      throw new \Exception('No Bearer Token');
    }
    $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
      ->setIssuer(getenv('OKTAISSUER'))
      ->setAudience('api://default')
      ->setClientId(getenv('OKTACLIENTID'))
      ->build();
    return $jwtVerifier->verify($matches[1]);
  } catch (\Exception $e) {
    return false;
  }
}
