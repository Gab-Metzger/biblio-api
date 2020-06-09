<?php

namespace Src\TableGateways;

class LendingGateway
{

  private $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function find($id)
  {
    $statement = "
            SELECT 
                *
            FROM
                prets
            WHERE N_doc = ?;
        ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array($id));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function delete($id)
  {
    $statement = "
            DELETE FROM prets
            WHERE N_doc = :id;
        ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array('id' => $id));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}