<?php

namespace App\Repositories;

use App\Models\Report;
use App\Models\Health;
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
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la création du rapport: " . $e->getMessage());
      throw $e;
    }
  }
}