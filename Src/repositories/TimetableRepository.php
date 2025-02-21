<?php

namespace App\Repositories;

use App\Models\Timetable;
use PDO;
use PDOException;
use lib\core\Logger;

class TimetableRepository extends Repositories
{
  public function __construct()
  {
    parent::__construct();
  }

  /** Methode pour créer un horaire
   * 
   * @param string $days
   * @param string $open_hours
   * @param string $close_hours
   * @return Timetable
   */
  public function createTimetable(string $days, string $open_hours, string $close_hours): Timetable
  {
    try{
      $query = "INSERT INTO `timetable` (days, open_hours, close_hours) 
                VALUES (:days, :open_hours, :close_hours)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':days', $days, PDO::PARAM_STR);
      $stmt->bindValue(':open_hours', $open_hours, PDO::PARAM_STR);
      $stmt->bindValue(':close_hours', $close_hours, PDO::PARAM_STR);
      $stmt->execute();
  
      $id_timetable = $this->db->lastInsertId();
      
      return new Timetable($id_timetable, $days, $open_hours, $close_hours);

    } catch(PDOException $e) {
      Logger::error("Erreur lors de la création de l'horaire: " . $e->getMessage());
      throw $e;
    }
  }

  /** Methode pour modifier un horaire
   * 
   * @param int $id_timetible
   * @param string $days
   * @param string $open_hours
   * @param string $close_hours
   * @return ?Timetable
   */
  public function updateTimetable(int $id_timetable, string $days, string $open_hours, string $close_hours): ?Timetable
  {
    try{
      $query = "UPDATE `timetable` SET `days` = :days, `open_hours` = :open_hours, `close_hours` = :close_hours
                WHERE `id_timetable` = :id_timetable";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_timetable', $id_timetable, PDO::PARAM_INT);
      $stmt->bindValue(':days', $days, PDO::PARAM_STR);
      $stmt->bindValue(':open_hours', $open_hours, PDO::PARAM_STR);
      $stmt->bindValue(':close_hours', $close_hours, PDO::PARAM_STR);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return new Timetable($id_timetable, $days, $open_hours, $close_hours);
      }

      Logger::warning("Aucun horaire trouvé avec l'ID: $id_timetable");
      return null;

    } catch(PDOException $e) {
      Logger::error("Aucun horaire trouvé avec ID: $id_timetable");
      return null;
    }
  }

  /** Methode pour récupérer un horaire spécifique
   * 
   * @param int $id_timetable
   * @return ?Timetable
   */
  public function getTimetable(int $id_timetable): ?Timetable
  {
    try {
      $query = "SELECT * FROM timetable WHERE id_timetable = :id_timetable";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_timetable', $id_timetable, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($result) {
        return new Timetable(
          $result['id_timetable'],
          $result['days'],
          $result['open_hours'],
          $result['close_hours']
        );
      }
      Logger::warning("Aucun horaire trouvé avec l'ID: $id_timetable");
      return null;

    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupération de l'horaire: " . $e->getMessage());
      return null;
    }
  }

  /** Methode pour récupérer tous les horaires
   * 
   * @param array
   */
  public function getAllTimetables(): array
  {
    try {
      $query = "SELECT * FROM timetable 
                ORDER BY FIELD(days, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche')";
      $stmt = $this->db->prepare($query);
      $stmt->execute();

      $timetables = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $timetables[] = new Timetable(
          $row['id_timetable'],
          $row['days'],
          $row['open_hours'],
          $row['close_hours']
        );
      }
      return $timetables;

    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupération des horaires: " . $e->getMessage());
      return [];
    }
  }

  /** Méthode pour supprimer un horaire
   * 
   * @param int $id_timetable
   * @return bool
   */
  public function deleteTimetable(int $id_timetable): bool
  {
    try {
      $query = "DELETE FROM timetable WHERE id_timetable = :id_timetable";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_timetable', $id_timetable, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        Logger::info("L'horaire ID : $id_timetable a été supprimé");
        return true;
      }
      Logger::warning("Aucun horaire trouvé avec l'ID $id_timetable pour la suppression.");
      return false;

    } catch (PDOException $e) {
      Logger::error("Erreur lors de la suppression de l'horaire: " . $e->getMessage());
      return false;
    }
  }
}