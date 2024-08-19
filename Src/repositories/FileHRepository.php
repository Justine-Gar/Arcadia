<?php

namespace App\Repositories;

use lib\config\database;
use App\models\Habitat;
use App\models\FileH;
use lib\core\Logger;
use PDO;
use PDOException;

/** 
 * Repository pour la gestion des images Habitat
 * 
 */
class FileHRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = database::getInstance();
  }


  /**
   * Methode ajouter une nouvelle image pour l'habitat
   * @param FileH $file
   * @return FileH
   * @throws PDOException
   */
  public function insertImageToHabitat(FileH $file): FileH
  {
    try {
      $query = "INSERT INTO `fileH` (`fileName`, `filePath`, `id_habitat`) VALUES (:fileName, :filePath, :id_habitat)";
      $stmt = $this->db->prepare($query);
      $fileName = $file->getFileName();
      $filePath = $file->getFilePath();
      $serviceId = $file->getHabitatId();

      $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);
      $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
      $stmt->bindParam(':id_habitat', $habitatId, PDO::PARAM_INT);

      $stmt->execute();

      $id = $this->db->lastInsertId();
      $file->setIdFileH($id);

      Logger::info("Nouvelle image ajoutée (ID: $id) pour l'habitat ID $serviceId");
      return $file;

    }catch(PDOException $e)
    {
      Logger::error("Erreur lors de l'ajout d'une image pour l'habitat ID " . $file->getHabitatId() . ": " . $e->getMessage());
      throw new PDOException("Erreur lors de l'ajout de l'image : " . $e->getMessage());
    }
    
  }
}