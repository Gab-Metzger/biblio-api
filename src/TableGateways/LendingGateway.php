<?php

namespace Src\TableGateways;

class LendingGateway
{

  private $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function insert(array $input)
  {
    $statement = "
    INSERT INTO prets 
        (N_doc, N_Lect, Titre, Auteurs, COTE, DatePret)
    VALUES
        (:id, :id_reader, :title, :author, :cote, :date_lending);
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'id' => (int) $input['id'],
        'id_reader' => (int) $input['id_reader'],
        'title' => $input['title'],
        'author'  => $input['author'] ?? NULL,
        'cote'  => $input['cote'] ?? NULL,
        'date_lending'  => date('Y-m-d'),
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
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