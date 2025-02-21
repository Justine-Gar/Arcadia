<?php

namespace App\Repositories;

use App\Models\Report;
use App\Models\Health;
use App\Models\Animal;
use App\Models\User;
use App\Models\Role;
use lib\core\Logger;
use DateTime;
use PDO;
use PDOException;

class ReportRepository extends Repositories
{
  public function __construct()
  {
    parent::__construct();
  }

  /** %ethode de creation d'un rapport
   * 
   * @param Health $health_status
   * @param DateTime $passage
   * @param ?string $prescription
   * @param ?string $quantity
   * @param ?string $habitat_condition
   * @param int $id_animal
   * @param int $id_user
   * @return Report
   */
  public function createReport(Health $health_status, DateTime $passage, ?string $prescription, ?string $quantity, ?string $habitat_condition, int $id_animal, int $id_user): Report
  {
    try {

      $query = "INSERT INTO `report` (`health_status`, `passage`, `prescription`, `quantity`, `habitat_condition`, `id_animal`, `id_user`)
                VALUES (:health_status, :passage, :prescription, :quantity, :habitat_condition, :id_animal, :id_user)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':health_status', $health_status->value, PDO::PARAM_STR);
      $stmt->bindValue(':passage', $passage->format('Y-m-d H:i:s'), PDO::PARAM_STR);
      $stmt->bindValue(':prescription', $prescription);
      $stmt->bindValue(':quantity', $quantity, PDO::PARAM_STR);
      $stmt->bindValue(':habitat_condition', $habitat_condition, PDO::PARAM_STR);
      $stmt->bindValue(':id_animal', $id_animal, PDO::PARAM_INT);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      $stmt->execute();

      $id_report = $this->db->lastInsertId();

      return new Report($id_report, $health_status, $passage, $prescription, $quantity, $habitat_condition, $id_animal, $id_user);
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la création du rapport: " . $e->getMessage());
      throw $e;
    }
  }

  /**
   * Méthode pour modifier un rapport
   * 
   * @param int $id_report
   * @param Health $health_status
   * @param DateTime $passage
   * @param ?string $prescription
   * @param ?string $quantity
   * @param ?string $habitat_condition
   * @return ?Report
   */
  public function updateReport(int $id_report, Health $health_status, DateTime $passage, ?string $prescription, ?string $quantity, ?string $habitat_condition): ?Report
  {
    try {
      $query = "UPDATE `report` SET 
        `health_status` = :health_status, 
        `passage` = :passage, 
        `prescription` = :prescription, 
        `quantity` = :quantity, 
        `habitat_condition` = :habitat_condition 
        WHERE `id_report` = :id_report";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_report', $id_report, PDO::PARAM_INT);
      $stmt->bindValue(':health_status', $health_status->value, PDO::PARAM_STR);
      $stmt->bindValue(':passage', $passage->format('Y-m-d H:i:s'), PDO::PARAM_STR);
      $stmt->bindValue(':prescription', $prescription, PDO::PARAM_STR);
      $stmt->bindValue(':quantity', $quantity, PDO::PARAM_STR);
      $stmt->bindValue(':habitat_condition', $habitat_condition, PDO::PARAM_STR);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return $this->getReport($id_report);
      } else {
        Logger::warning("Aucun rapport trouvé avec l'ID: $id_report");
        return null;
      }
    } catch (PDOException $e) {
      Logger::error("Erreur lors de la modification du rapport: " . $e->getMessage());
      throw $e;
    }
  }

  /**
   * Méthode pour récupérer un rapport
   * 
   * @param int $id_report
   * @return ?Report
   */
  public function getReport(int $id_report): ?Report
  {
    try {
      $query = "SELECT * FROM `report` WHERE `id_report` = :id_report";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_report', $id_report, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        return new Report(
          $result['id_report'],
          Health::from($result['health_status']),
          new DateTime($result['passage']),
          $result['prescription'],
          $result['quantity'],
          $result['habitat_condition'],
          $result['id_animal'],
          $result['id_user']
        );
      } else {
        Logger::info("Aucun rapport trouvé avec l'ID: $id_report");
        return null;
      }
    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupération du rapport: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Méthode pour récupérer tous les rapports
   * 
   * @return array Un tableau d'objets Report
   */
  public function getAllReports(): array
  {
    try {
      $query = "SELECT r.*, a.firstname, a.gender, a.species, a.diet, a.reproduction, a.id_habitat, 
                        u.username, u.email, u.password, u.role
                FROM `report` r
                LEFT JOIN `animal` a ON r.id_animal = a.id_animal
                LEFT JOIN `user` u ON r.id_user = u.id_user
                ORDER BY r.passage DESC";
      $stmt = $this->db->prepare($query);
      $stmt->execute();

      $reports = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $report = new Report(
          $row['id_report'],
          Health::from($row['health_status']),
          new DateTime($row['passage']),
          $row['prescription'],
          $row['quantity'],
          $row['habitat_condition'],
          $row['id_animal'],
          $row['id_user']
        );

        if ($row['firstname']) {
          $animal = new Animal(
            $row['id_animal'],
            $row['firstname'],
            $row['gender'],
            $row['species'],
            $row['diet'],
            $row['reproduction'],
            $row['id_habitat']
          );
          $report->setAnimalReport($animal);
        }

        if ($row['username']) {
          $user = new User(
            $row['id_user'],
            $row['username'],
            $row['email'],
            $row['password'],
            Role::from($row['role'])
          );
          $report->setUserReport($user);
        }

        $reports[] = $report;
      }

      return $reports;
    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupération de tous les rapports: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Méthode pour supprimer un rapport
   * 
   * @param int $id_report
   * @return bool
   */
  public function deleteReport(int $id_report): bool
  {
    try {
      $query = "DELETE FROM `report` WHERE `id_report` = :id_report";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_report', $id_report, PDO::PARAM_INT);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        Logger::info("Le rapport ID : $id_report a bien été supprimé");
        return true;
      } else {

        Logger::warning("Aucun rapport trouvé avec l'ID $id_report pour la suppression.");
        return false;
      }
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la suppression du rapport: " . $e->getMessage());
      return false;
    }
  }

  
  /** Methode pour filtrer les rapport animal et/ou date
   * 
   * @param ?int $id_animal
   * @param ?DateTime $start_date
   * @return array tbleu d'objet Report
   */
  public function getFiltrer(?int $id_animal = null, ?DateTime $start_date = null): array
  {
    return $this->getFilteredReportsPaginated(0, PHP_INT_MAX, $id_animal, $start_date, );
  }

  /** Methode pour recupérer les rapport du jours
   * 
   */
  public function getReportsByDate(DateTime $date): array
  {
    try {
        $query = "SELECT r.*, a.firstname, a.gender, a.species, a.diet, a.reproduction, a.id_habitat, 
                        u.username, u.email, u.password, u.role
                  FROM `report` r
                  LEFT JOIN `animal` a ON r.id_animal = a.id_animal
                  LEFT JOIN `user` u ON r.id_user = u.id_user
                  WHERE DATE(r.passage) = :date
                  ORDER BY r.passage DESC";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':date', $date->format('Y-m-d'), PDO::PARAM_STR);
        $stmt->execute();

        $reports = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $report = new Report(
                $row['id_report'],
                Health::from($row['health_status']),
                new DateTime($row['passage']),
                $row['prescription'],
                $row['quantity'],
                $row['habitat_condition'],
                $row['id_animal'],
                $row['id_user']
            );

            if ($row['firstname']) {
                $animal = new Animal(
                    $row['id_animal'],
                    $row['firstname'],
                    $row['gender'],
                    $row['species'],
                    $row['diet'],
                    $row['reproduction'],
                    $row['id_habitat']
                );
                $report->setAnimalReport($animal);
            }

            if ($row['username']) {
                $user = new User(
                    $row['id_user'],
                    $row['username'],
                    $row['email'],
                    $row['password'],
                    Role::from($row['role'])
                );
                $report->setUserReport($user);
            }

            $reports[] = $report;
        }

        return $reports;

    } catch (PDOException $e) {
        Logger::error("Erreur lors de la récupération des rapports du jour: " . $e->getMessage());
        return [];
    }
  }

  /** Methode pour obtenir le nbr de rapport
   * 
   */
  public function countFilteredReports(?int $id_animal = null, ?DateTime $start_date = null): int
  {
    try {
        $query = "SELECT COUNT(*) FROM `report` r WHERE 1=1";
        $params = [];

        if ($id_animal !== null && $id_animal !== 0) {
            $query .= " AND r.id_animal = :id_animal";
            $params[':id_animal'] = $id_animal;
        }

        if ($start_date !== null) {
            $query .= " AND r.passage >= :start_date";
            $params[':start_date'] = $start_date->format('Y-m-d H:i:s');
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        Logger::error("Erreur lors du comptage des rapports filtrés: " . $e->getMessage());
        return 0;
    }
  }

  /** Methode pour obtenir un ensemble de rapport par page
   * 
   * @param int $offset
   * @param int $byPage
   * @return array
   */
  public function getFilteredReportsPaginated(int $offset, int $limit, ?int $id_animal = null, ?DateTime $start_date = null, ): array
  {
    try {
        $query = "SELECT r.*, a.firstname, a.gender, a.species, a.diet, a.reproduction, a.id_habitat, 
                        u.username, u.email, u.password, u.role
                  FROM `report` r
                  LEFT JOIN `animal` a ON r.id_animal = a.id_animal
                  LEFT JOIN `user` u ON r.id_user = u.id_user
                  WHERE 1=1";
        $params = [];

        if ($id_animal !== null && $id_animal !== 0) {
            $query .= " AND r.id_animal = :id_animal";
            $params[':id_animal'] = $id_animal;
        }

        if ($start_date !== null) {
            $query .= " AND r.passage >= :start_date";
            $params[':start_date'] = $start_date->format('Y-m-d H:i:s');
        }

        $query .= " ORDER BY r.passage DESC LIMIT :offset, :limit";
        $params[':offset'] = $offset;
        $params[':limit'] = $limit;

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        $reports = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $report = new Report(
            $row['id_report'],
            Health::from($row['health_status']),
            new DateTime($row['passage']),
            $row['prescription'],
            $row['quantity'],
            $row['habitat_condition'],
            $row['id_animal'],
            $row['id_user']
          );
  
          if ($row['firstname']) {
            $animal = new Animal(
              $row['id_animal'],
              $row['firstname'],
              $row['gender'],
              $row['species'],
              $row['diet'],
              $row['reproduction'],
              $row['id_habitat']
            );
            $report->setAnimalReport($animal);
          }
  
          if ($row['username']) {
            $user = new User(
              $row['id_user'],
              $row['username'],
              $row['email'],
              $row['password'],
              Role::from($row['role'])
            );
            $report->setUserReport($user);
          }
  
          $reports[] = $report;
        }
  
        return $reports;

    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupérationdes des rapports filtrés et paginés: " . $e->getMessage());
      return [];
    }
  }

  public function countTotalReports(): int
  {
    try {
        $query = "SELECT COUNT(*) FROM `report`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        Logger::error("Erreur lors du comptage des rapports: " . $e->getMessage());
        return 0;
    }
  }

  public function getReportsPaginated(int $offset, int $limit): array
  {
    try {
        $query = "SELECT r.*, a.firstname, a.gender, a.species, a.diet, a.reproduction, a.id_habitat, 
                        u.username, u.email, u.password, u.role
                  FROM `report` r
                  LEFT JOIN `animal` a ON r.id_animal = a.id_animal
                  LEFT JOIN `user` u ON r.id_user = u.id_user
                  ORDER BY r.passage DESC
                  LIMIT :offset, :limit";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $reports = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Votre code existant pour créer les objets Report
            $report = new Report(
                $row['id_report'],
                Health::from($row['health_status']),
                new DateTime($row['passage']),
                $row['prescription'],
                $row['quantity'],
                $row['habitat_condition'],
                $row['id_animal'],
                $row['id_user']
            );

            // Ajouter l'animal si présent
            if ($row['firstname']) {
                $animal = new Animal(
                    $row['id_animal'],
                    $row['firstname'],
                    $row['gender'],
                    $row['species'],
                    $row['diet'],
                    $row['reproduction'],
                    $row['id_habitat']
                );
                $report->setAnimalReport($animal);
            }

            // Ajouter l'utilisateur si présent
            if ($row['username']) {
                $user = new User(
                    $row['id_user'],
                    $row['username'],
                    $row['email'],
                    $row['password'],
                    Role::from($row['role'])
                );
                $report->setUserReport($user);
            }

            $reports[] = $report;
        }

        return $reports;
    } catch (PDOException $e) {
        Logger::error("Erreur lors de la récupération des rapports paginés: " . $e->getMessage());
        return [];
    }
  }
}
