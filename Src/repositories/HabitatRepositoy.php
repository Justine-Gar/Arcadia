<?php

namespace App\Repositories;

use App\Models\Habitat;
use lib\core\Logger;
use PDO;
use PDOException;


class HabitatRepository extends Repositories
{
  public function __construct()
  {
    parent::__construct();
  }

  /** Création d'un Habitat
   * 
   * @param string $name
   * @param string $description
   * @return Habitat
   */
  public function createHabitat(string $name, string $description): Habitat
  {
    try {

      $query = "INSERT INTO `habitat` (`name`, `description`) VALUES (:name, :description)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':name', $description, PDO::PARAM_STR);

      $id_habitat = $this->db->lastInsertId();

      return new Habitat($id_habitat, $name, $description);

    } catch (PDOException $e) {

      Logger::error("Error lors de la création de l'habitat: " . $e->getMessage());
    }
  }

  /** Methode pour modifier un habitat
   * 
   * @param int $id_habitat 
   * @param string $name
   * @param string $description
   * @return ?Habitat
   */
  public function updateHabitat(int $id_habitat, string $name, string $description): ?Habitat
  {
    try {

      $query = "UPDATE `habitat` SET `name` = :name, `description` = :description WHERE `id_habitat` = :id_habitat";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_habitat', $id_habitat, PDO::PARAM_INT);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':name', $description, PDO::PARAM_STR);

      $stmt->execute();

      //vérification habitat bien modifier
      if($stmt->rowCount() > 0) {
        
        return new Habitat($id_habitat, $name, $description);

      } else {

        Logger::warning("Aucun habitat trouvé avec l'ID: $id_habitat");
        return null;
      }

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la modification de l'habitat: " . $e->getMessage());
    }
  }

  /** Methode pour read Un habitat 
   * 
   * @param int $id_habitat
   * @return ?Habitat
   */
  public function getHabitat(int $id_habitat): ?Habitat
  {
    try {

      $query = "SELECT `id_habitat`, `name`, `description` FROM `habitat` WHERE `id_habitat` = :id_habitat";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_habitat', $id_habitat, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {

        return new Habitat($result['id_habitat'], $result['name'], $result['description']);
      } else {

        Logger::info("Aucun habitat trouvé avec l'ID: $id_habitat");
        return null;
      }
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la récupération de l'habitat: " . $e->getMessage());
      return null;
    }
  }

  /** Methode pour read tout les habitats
   * 
   * @return array Un tableau d'objet d'Habitat
   */
  public function getAllHabitat(): array
  {
    try {
      $query = "SELECT `id_habitat`, `name`, `description` FROM `habitat`";
      $stmt = $this->db->prepare($query);
      $stmt->execute();

      $habitats = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $habitats[] = new Habitat($row['id_habitat'], $row['name'], $row['description']);
      }

      return $habitats;

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la récupération de tous les habitats: " . $e->getMessage());
      return [];
    }
  }

  /** Methode pour supprimer un Habitat
   * 
   * @param int $id_habitat
   * @return bool
   */
  public function deleteHabitat(int $id_habitat): bool 
  {
    try {
      $query = "DELETE FROM `habitat` WHERE `id_habitat` = :id_habitat";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_habitat', $id_habitat, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        Logger::info("L'Habitat Id : $id_habitat à bien été supprimé");
        return true;

      } else {

        Logger::warning("Aucun habitat trouvé avec l'ID $id_habitat pour la suppression.");
        return false;
      }
      
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la suppression de l'habitat: " . $e->getMessage());
      return false;
    }
  }
}