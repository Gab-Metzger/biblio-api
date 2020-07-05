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
            case 'POST':
                $response = $this->createLendingFromRequest();
                break;
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

    private function createLendingFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateLending($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->lendingGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
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

    private function validateLending($input)
    {
        if (! isset($input['id'])) {
            return false;
        }
        if (! isset($input['id_reader'])) {
            return false;
        }
        if (! isset($input['title'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}