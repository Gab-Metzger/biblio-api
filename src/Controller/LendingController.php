<?php
namespace Src\Controller;

use Src\TableGateways\LendingGateway;

class LendingController {

    private $db;
    private $requestMethod;
    private $id;

    private $lendingGateway;

    public function __construct($db, $requestMethod, $id)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;

        $this->lendingGateway = new LendingGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'DELETE':
                $response = $this->deleteLending($this->id);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function deleteLending($id)
    {
        $result = $this->lendingGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->lendingGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}