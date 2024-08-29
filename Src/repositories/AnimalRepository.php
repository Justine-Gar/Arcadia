<?php

namespace App\Repositories;

use App\Models\Animal;
use App\Models\Habitat;
use lib\core\Logger;
use PDO;
use PDOException;

class AnimalRepository extends Repositories
{

  public function __construct()
  {
    parent::__construct();
  }

  /** Methode de création d'un Animal
   * 
   * @param string $firstname
   * @param string $gender
   * @param string $species
   * @param string $diet
   * @param string $reproduction
   * @param Habitat Objt habitat
   * @return Animal
   */
  public function createAnimal(string $firstname, string $gender, string $species, string $diet, string $reproduction, int $id_habitat): Animal
  {
    try {

      $query = "INSERT INTO `animal` (`firstname`, `gender`, `species`, `diet`, `reproduction`, `id_habitat`) VALUES (:firstname, :gender, :species, :diet, :reproduction, :id_habitat)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
      $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindValue(':species', $species, PDO::PARAM_STR);
      $stmt->bindValue(':diet', $diet, PDO::PARAM_STR);
      $stmt->bindValue(':reproduction', $reproduction, PDO::PARAM_STR);
      $stmt->bindValue(':id_habitat', $id_habitat, PDO::PARAM_INT);
      $stmt->execute();

      $id_animal = $this->db->lastInsertId();

      return new Animal($id_animal, $firstname, $gender, $species, $diet, $reproduction, $id_habitat);

    } catch (PDOException $e) {

      Logger::error("Error lors de la création du service: " . $e->getMessage());
      throw $e;
    }
  }

  /** Methode pour modifier un animal
   * 
   * @param int $id_animal 
   * @param string $name
   * @param string $description
   * @return ?Animal
   */
  public function updateAnimal(int $id_animal, string $firstname, string $gender, string $species, string $diet, string $reproduction, int $id_habitat): ?Animal
  {
    try {

      $query = "UPDATE `animal` SET `firstname` = :firstname, `gender` = :gender, `species` = :species, `diet` = :diet, `reproduction` = :reproduction WHERE `id_animal` = :id_animal";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_animal', $id_animal, PDO::PARAM_INT);
      $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
      $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindValue(':species', $species, PDO::PARAM_STR);
      $stmt->bindValue(':diet', $diet, PDO::PARAM_STR);
      $stmt->bindValue(':reproduction', $reproduction, PDO::PARAM_STR);
      $stmt->bindValue(':id_habitat', $id_habitat, PDO::PARAM_INT);

      $stmt->execute();

      //vérification si service existe bien
      if($stmt->rowCount() > 0) {
        
        return new Animal($id_animal, $firstname, $gender,$species, $diet, $reproduction, $id_habitat);

      } else {

        Logger::warning("Aucun animal trouvé avec l'ID: $id_animal");
        return null;
      }

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la modification de l'animal: " . $e->getMessage());
      throw $e;
    }
  }

  /** Methode pour read Un Animal 
   * 
   * @param int $id_animal
   * @return ?Animal
   */
  public function getAnimal(int $id_animal): ?Animal
  {
    try {

      $query = "SELECT `id_animal`, `firstname`, `gender`, `species`, `diet`, `reproduction`, `id_habitat` FROM `animal` WHERE `id_animal` = :id_animal";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_animal', $id_animal, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {

        return new Animal($result['id_animal'], $result['firstname'], $result['gender'], $result['species'], $result['diet'], $result['reproduction'], $result['id_habitat']);
      } else {

        Logger::info("Aucun animal trouvé avec l'ID: $id_animal");
        return null;
      }
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la récupération de l'id_animal: " . $e->getMessage());
      return null;
    }
  }

  /** Methode pour read tout les animaux
   * 
   * @return array Un tableau d'objet Animal
   */
  public function getAllAnimal(): array
  {
    try {
      $query = "SELECT `id_animal`, `firstname`, `gender`, `species`, `diet`, `reproduction`, `id_habitat` FROM `animal`";
      $stmt = $this->db->prepare($query);
      $stmt->execute();

      $animals = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $animals[] = new Animal($row['id_animal'], $row['firstname'], $row['gender'], $row['species'], $row['diet'], $row['reproduction'], $row['id_habitat']);
      }

      return $animals;

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la récupération de tous les animaux: " . $e->getMessage());
      return [];
    }
  }

  /** Methode pour supprimer un Animal
   * 
   * @param int $id_animal
   * @return bool
   */
  public function deleteAnimal(int $id_animal): bool 
  {
    try {
      $query = "DELETE FROM `animal` WHERE `id_animal` = :id_animal";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_animal', $id_animal, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        Logger::info("L'Animal Id : $id_animal à bien été supprimé");
        return true;

      } else {

        Logger::warning("Aucun animal trouvé avec l'ID $id_animal pour la suppression.");
        return false;
      }
      
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la suppression de l'animal: " . $e->getMessage());
      return false;
    }
  }

}