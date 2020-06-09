<?php

namespace Src\TableGateways;

class ReaderGateway
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
                lec
            WHERE N_lec = ?;
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

  public function insert(array $input)
  {
    $statement = "
    INSERT INTO lec 
        (N_lec, NOMPrenom, Email, Mdp)
    VALUES
        (:id, :name, :email, :password);
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'id' => (int) $input['id'],
        'name' => $input['name'],
        'email'  => $input['email'],
        'password'  => $input['password'] ?? "",
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
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

  public function update($id, array $input)
  {
    $statement = "
            UPDATE lec
            SET 
                NOMPrenom = :NOMPrenom,
                Email  = :Email
            WHERE N_lec = :N_lec;
        ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'N_lec' => (int) $id,
        'NOMPrenom' => $input['name'],
        'Email'  => $input['email']
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function delete($id)
  {
    $statement = "
            DELETE FROM lec
            WHERE N_lec = :id;
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
