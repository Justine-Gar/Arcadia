<?php

namespace App\Repositories;

use App\Models\Service;
use lib\core\Logger;
use PDO;
use PDOException;

class ServiceRepository extends Repositories
{

  public function __construct()
  {
    parent::__construct();
  }

  /** Methode de création d'un Service
   * 
   * @param string $name
   * @param string $description
   * @return Service
   */
  public function createService(string $name, string $description): Service
  {
    try {

      $query = "INSERT INTO `service` (`name`, `description`) VALUES (:name, :description)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':description', $description, PDO::PARAM_STR);
      $stmt->execute();

      $id_service = $this->db->lastInsertId();

      return new Service($id_service, $name, $description);

    } catch (PDOException $e) {

      Logger::error("Error lors de la création du service: " . $e->getMessage());
      throw $e;
    }
  }

  /** Methode pour modifier un service
   * 
   * @param int $id_service 
   * @param string $name
   * @param string $description
   * @return ?Service
   */
  public function updateService(int $id_service, string $name, string $description): ?Service
  {
    try {

      $query = "UPDATE `service` SET `name` = :name, `description` = :description WHERE `id_service` = :id_service";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_service', $id_service, PDO::PARAM_INT);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':name', $description, PDO::PARAM_STR);

      $stmt->execute();

      //vérification si service existe bien
      if($stmt->rowCount() > 0) {
        
        return new Service($id_service, $name, $description);

      } else {

        Logger::warning("Aucun service trouvé avec l'ID: $id_service");
        return null;
      }

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la modification du service: " . $e->getMessage());
      throw $e;
    }
  }

  /** Methode pour read Un service 
   * 
   * @param int $id_service
   * @return ?Service
   */
  public function getService(int $id_service): ?Service
  {
    try {

      $query = "SELECT `id_service`, `name`, `description` FROM `service` WHERE `id_service` = :id_service";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_service', $id_service, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {

        return new Service($result['id_service'], $result['name'], $result['description']);
      } else {

        Logger::info("Aucun service trouvé avec l'ID: $id_service");
        return null;
      }
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la récupération de l'id_service: " . $e->getMessage());
      return null;
    }
  }

  /** Methode pour read tout les services
   * 
   * @return array Un tableau d'objet d'Service
   */
  public function getAllService(): array
  {
    try {
      $query = "SELECT `id_service`, `name`, `description` FROM `service`";
      $stmt = $this->db->prepare($query);
      $stmt->execute();

      $services = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $services[] = new Service($row['id_service'], $row['name'], $row['description']);
      }

      return $services;

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la récupération de tous les services: " . $e->getMessage());
      return [];
    }
  }

  /** Methode pour supprimer un Service
   * 
   * @param int $id_service
   * @return bool
   */
  public function deleteService(int $id_service): bool 
  {
    try {
      $query = "DELETE FROM `service` WHERE `id_service` = :id_service";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_service', $id_service, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        Logger::info("Le Service Id : $id_service à bien été supprimé");
        return true;

      } else {

        Logger::warning("Aucun service trouvé avec l'ID $id_service pour la suppression.");
        return false;
      }
      
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la suppression du service: " . $e->getMessage());
      return false;
    }
  }

}