<?php

namespace App\repositories;

use App\models\Animal;
use App\models\FileA;

/** 
 * Repository pour la gestion des images Animal
 * Etand Repository pour hérité des fonctionnalité
 */
class FileARepository extends Repository
{
  /**
   * Constructeur de FileARepository
   * Apple au parent pour initialiser la connexion à la bdd
   */
  public function __construct()
  {
    parent::__construct();
  }

  /** Insérer un nouveau FileA dans la BDD
   * 
   * @param FileA $fileA l'objet FileA à insérer
   */
  private function insert(FileA $fileA): void
  {
    $query = "INSERT INTO `fileA` (fileName, filePath, id_animal) VALUES (:fileName, :filePath, :id_animal)";
    $params = [
      ':fileName' => $fileA->getFileName(),
      ':filePath' => $fileA->getFilePath(),
      ':id_animal' => $fileA->getAnimal()->getId()
    ];

    $stmt = $this->execute($query, $params);
    $fileA->setIdFileA($this->db->lastInsertId());
  }
}