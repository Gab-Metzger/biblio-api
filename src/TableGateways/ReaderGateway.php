<?php
namespace Src\TableGateways;

class ReaderGateway {

  private $db = null;

  public function __construct($db)
  {
      $this->db = $db;
  }

  public function findAll()
  {
      $statement = "
          SELECT 
              *
          FROM
              lec;
      ";

      try {
          $statement = $this->db->query($statement);
          $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
  }
}