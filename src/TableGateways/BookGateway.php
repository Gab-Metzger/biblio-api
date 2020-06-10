<?php

namespace Src\TableGateways;

class BookGateway
{

  private $db = null;

  public function __construct($db)
  {
    $this->db = $db;
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
}