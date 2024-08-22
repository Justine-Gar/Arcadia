<?php

namespace App\Repositories;

use App\models\FileS;
use lib\core\Logger;
use PDO;
use PDOException;

/** 
 * Repository pour la gestion des images Service
 * 
 */
class FileSRepository extends Repositories
{
  
  public function __construct()
  {
    parent::__construct();
  }


  /** Methode ajouter une nouvelle image pour le service
   * 
   * @param FileS $file
   * @return FileS
   * @throws PDOException
   */
  public function insertFileSToService(FileS $file): FileS
  {
    try {
      $query = "INSERT INTO `fileS` (`fileName`, `filePath`, `id_service`) VALUES (:fileName, :filePath, :id_service)";
      $stmt = $this->db->prepare($query);
      $fileName = $file->getFileSName();
      $filePath = $file->getFileSPath();
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
      throw $e;
    }
    
  }

  
  /** Method pour recupere image par son Id
   * 
   * @param int $id_fileS
   * @return ?FileS
   */
  public function getFileSById(int $id_fileS): ?FileS
  {
    try {

      $query = "SELECT * FROM `fileS` WHERE id_fileS = :id_fileS";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':id_fileS', $id_fileS, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {

        return new FileS($result['id_fileS'], $result['fileName'], $result['filePath'], $result['id_service']);
      }
      return null;
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la récupération de l'image Id: $id_fileS" . $e->getMessage());
      return null;
    }
  }


  /** Method pour récuperer une image pour un service donnée
   * 
   * @param int $serviceId
   * @param int $id_fileS
   * @return ?FileS
   */
  public function getOneFileSForOneService(int $serviceId, int $id_fileS): ?FileS
  {
    try {

      $query = "SELECT * FROM `fileS` WHERE `id_service` = :serviceId AND `id_fileS` = :id_fileS";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':serviceId', $serviceId, PDO::PARAM_INT);
      $stmt->bindParam(':id_fileS', $id_fileS, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {

        return new FileS($result['id_fileS'], $result['fileName'], $result['filePath'], $result['id_service']);
      }
      return null;
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la récupération de l'image (service id et image id)" . $e->getMessage());
      return null;
    }
  }


  /** methode pour mettre à jours une image dan sla bdd
   * 
   * @param FileS $file
   * @return bool
   */
  public function updateFileS(FileS $file): bool
  {
    try {

      $query = "UPDATE `fileS` SET `fileName` = :fileName, `filePath` = :filePath, `id_service` = :is_service
                WHERE `id_fileS` = :id_fileS";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':fileName', $file->getFileSName(), PDO::PARAM_STR);
      $stmt->bindValue(':filePath', $file->getFileSPath(), PDO::PARAM_STR);
      $stmt->bindValue(':id_service', $file->getServiceId(), PDO::PARAM_INT);
      $stmt->bindValue(':id_fileS', $file->getIdFileS(), PDO::PARAM_INT);

      return $stmt->execute();

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la mise à jour de l'image ID " . $file->getIdFileS() . ": " . $e->getMessage());
      return null;
    }
  }


  /** Method pour supprimer une image
   * 
   * @param int $id_fileS
   * @return bool
   */
  public function deleteFileS(int $id_fileS): bool
  {
    try {

      $query = "DELETE FROM `fileS` WHERE `id_fileS` = :id_fileS";
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':id_fileS', $id_fileS, PDO::PARAM_INT);

      return $stmt->execute();

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la suppression de l'image ID: " . $e->getMessage());
      return false;
    }
  }
}