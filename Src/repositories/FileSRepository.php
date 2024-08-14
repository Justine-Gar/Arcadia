<?php

namespace App\repositories;

use lib\config\database;
use App\models\Service;
use App\models\FileS;
use lib\core\Logger;
use PDO;
use PDOException;

/** 
 * Repository pour la gestion des images Service
 * 
 */
class FileSRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = database::getConnection();
  }


  /**
   * Methode ajouter une nouvelle image pour le service
   * @param FileS $file
   * @return FileS
   * @throws PDOException
   */
  public function insertImageToService(FileS $file): FileS
  {
    try {
      $query = "INSERT INTO `fileS` (`fileName`, `filePath`, `id_service`) VALUES (:fileName, :filePath, :id_service)";
      $stmt = $this->db->prepare($query);
      $fileName = $file->getFileName();
      $filePath = $file->getFilePath();
      $serviceId = $file->getServiceId();

      $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);
      $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
      $stmt->bindParam(':id_service', $serviceId, PDO::PARAM_INT);

      $stmt->execute();

      $id = $this->db->lastInsertId();
      $file->setIdFileS($id);

      Logger::info("Nouvelle image ajoutée (ID: $id) pour le service ID $serviceId");
      return $file;

    }catch(PDOException $e)
    {
      Logger::error("Erreur lors de l'ajout d'une image pour le service ID " . $file->getServiceId() . ": " . $e->getMessage());
      throw new PDOException("Erreur lors de l'ajout de l'image : " . $e->getMessage());
    }
    
  }
}