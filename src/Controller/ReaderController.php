<?php
namespace Src\Controller;

use Src\TableGateways\ReaderGateway;

class ReaderController {

    private $db;
    private $requestMethod;
    private $id;

    private $readerGateway;

    public function __construct($db, $requestMethod, $id)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;

        $this->readerGateway = new ReaderGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->id) {
                    $response = $this->getReader($this->id);
                } else {
                    $response = $this->getAllReaders();
                };
                break;
            case 'POST':
                $response = $this->createReaderFromRequest();
                break;
            case 'PUT':
                $response = $this->updateReaderFromRequest($this->id);
                break;
            case 'DELETE':
                $response = $this->deleteReader($this->id);
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

    private function getAllReaders()
    {
        $result = $this->readerGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getReader($id)
    {
        $result = $this->readerGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result[0]);
        return $response;
    }

    private function createReaderFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateReader($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->readerGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateReaderFromRequest($id)
    {
        $result = $this->readerGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateReader($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->readerGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteReader($id)
    {
        $result = $this->readerGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->readerGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateReader($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['email'])) {
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