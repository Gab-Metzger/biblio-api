<?php
namespace Src\Controller;

use Src\TableGateways\BookGateway;

class BookController {

    private $db;
    private $requestMethod;
    private $id;

    private $bookGateway;

    public function __construct($db, $requestMethod, $id)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;

        $this->bookGateway = new BookGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->createBookFromRequest();
                break;
            case 'PUT':
                $response = $this->updateBookFromRequest($this->id);
                break;
            case 'DELETE':
                $response = $this->deleteBook($this->id);
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

    private function createBookFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateBook($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->bookGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateBookFromRequest($id)
    {
        $result = $this->bookGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $this->bookGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteBook($id)
    {
        $result = $this->bookGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->bookGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateBook($input)
    {
        if (! isset($input['id'])) {
            return false;
        }
        if (! isset($input['title'])) {
            return false;
        }
        if (! isset($input['isbn'])) {
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