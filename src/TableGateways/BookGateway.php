<?php

namespace Src\TableGateways;

class BookGateway
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
                book
            WHERE Ndoc = ?;
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
    INSERT INTO book 
        (Ndoc, Titre, Auteur, Infos, Cote, Isbn, Biborbdp)
    VALUES
        (:id, :title, :author, :infos, :cote, :isbn, :biborbdp);
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'id' => (int) $input['id'],
        'title' => $input['title'],
        'author'  => $input['author'] ?? NULL,
        'infos'  => $input['infos'] ?? NULL,
        'cote'  => $input['cote'] ?? NULL,
        'isbn'  => $input['isbn'],
        'biborbdp'  => $input['biborbdp'] ?? "BIB",
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function update($id, array $input)
  {
    $statement = "
            UPDATE book
            SET 
              Titre = :title,
              Auteur  = :author,
              Infos = :infos,
              Cote = :cote,
              Isbn = :isbn
            WHERE Ndoc = :id;
        ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'id' => (int) $id,
        'title' => $input['title'],
        'author'  => $input['author'],
        'infos' => $input['infos'],
        'cote' => $input['cote'],
        'isbn' => $input['isbn']
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function delete($id)
  {
    $statement = "
            DELETE FROM book
            WHERE Ndoc = :id;
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