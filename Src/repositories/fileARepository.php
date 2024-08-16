<?php

namespace App\repositories;

use lib\config\database;
use App\models\Animal;
use App\models\FileA;
use lib\core\Logger;
use PDO;
use PDOException;

/** 
 * Repository pour la gestion des images Animal
 * 
 */
class FileARepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = database::getInstance();
  }


  /**
   * Methode ajouter une nouvelle image à un animal
   * @param FileA $file
   * @return FileA
   * @throws PDOException
   */
  public function insertImageToAnimal(FileA $file): FileA
  {
    try {
      $query = "INSERT INTO `fileA` (`fileName`, `filePath`, `id_animal`) VALUES (:fileName, :filePath, :id_animal)";
      $stmt = $this->db->prepare($query);
      $fileName = $file->getFileName();
      $filePath = $file->getFilePath();
      $animalId = $file->getAnimalId();

      $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);
      $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
      $stmt->bindParam(':id_animal', $animalId, PDO::PARAM_INT);

      $stmt->execute();

      $id = $this->db->lastInsertId();
      $file->setIdFileA($id);

      Logger::info("Nouvelle image ajoutée (ID: $id) pour l'animal ID $animalId");
      return $file;

    }catch(PDOException $e)
    {
      Logger::error("Erreur lors de l'ajout d'une image pour l'animal ID " . $file->getAnimalId() . ": " . $e->getMessage());
      throw new PDOException("Erreur lors de l'ajout de l'image : " . $e->getMessage());
    }
    
  }
}