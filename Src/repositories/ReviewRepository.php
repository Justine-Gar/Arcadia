<?php

namespace App\Repositories;

use App\Models\Review;
use lib\core\Logger;
use PDO;
use PDOException;


class ReviewRepository extends Repositories
{
  public function __construct()
  {
    parent::__construct();
  }

  /** Ajouter un nouvel avis
   * 
   * @param Review $review
   * @return bool
   */
  public function addReview(Review $review): bool
  {
    try {
      $query = "INSERT INTO review (name, description, score, status) 
                VALUES (:name, :description, :score, :status)";
      
      $stmt = $this->db->prepare($query);
      return $stmt->execute([
          'name' => $review->getNameReview(),
          'description' => $review->getDescriptionReview(),
          'score' => $review->getScore(),
          'status' => $review->getStatus()
      ]);

    } catch (PDOException $e) {
        Logger::error("Erreur lors de l'ajout d'un avis: " . $e->getMessage());
        return false;
    }
  }


  /** Mise à jour du status d'un avis
   * 
   * @param int $id_review
   * @param string $status
   * @return bool
   */
  public function updateReviewStatus(int $id_review, string $status): bool
  {
    try {
      $query = "UPDATE review SET status = :status WHERE id_review = :id";
      $stmt = $this->db->prepare($query);
      return $stmt->execute([
          'status' => $status,
          'id' => $id_review
      ]);

    } catch (PDOException $e) {
        Logger::error("Erreur lors de la mise à jour du statut: " . $e->getMessage());
        return false;
    }
  }

  /** Recupere un avis par son Id
   * 
   * @param int $id_review
   * @return Review|null
   */
  public function getReviewById(int $id_review): ?Review
  {
    try {
      $query = "SELECT * FROM review WHERE id_review = :id";
      $stmt = $this->db->prepare($query);
      $stmt->execute(['id' => $id_review]);

      if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return new Review(
          $row['id_review'],
          $row['name'],
          $row['description'],
          $row['score'],
          $row['status']
        );
      }
      return null;
    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupération de l'avis: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Récupère tous les avis
   * 
   * @return array
   */
  public function getAllReviews(): array
  {
    try {
      $query = "SELECT * FROM review ORDER BY id_review DESC";
      $stmt = $this->db->query($query);

      $reviews = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reviews[] = new Review(
          $row['id_review'],
          $row['name'],
          $row['description'],
          $row['score'],
          $row['status']
        );
      }
      return $reviews;
    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupération des avis: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Récupère les avis par status
   * 
   * @param string $status
   * @param int $limit Nombre d'avis à récupérer
   * @return array<Review>
   */
  public function getReviewsByStatus(string $status, int $limit = 0): array
  {
    try {
      $query = "SELECT * FROM review WHERE status = :status ORDER BY id_review DESC";
      
      if ($limit > 0) {
        $query .= " LIMIT :limit";
      }

      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        
      if ($limit > 0) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      }

      $stmt->execute();

      $reviews = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reviews[] = new Review(
          $row['id_review'],
          $row['name'],
          $row['description'],
          $row['score'],
          $row['status']
        );
      }
      return $reviews;
    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupération des avis par status: " . $e->getMessage());
      return [];
    }
  }


  /**
   * Supprime logiquement un avis (change le statut en "Supprimer")
   * 
   * @param int $id_review
   * @return bool
   */
  public function softDeleteReview(int $id_review): bool
  {
    return $this->updateReviewStatus($id_review, "Supprimer");
  }

  /**
   * Récupère uniquement les avis approuvés pour l'affichage public
   * 
   * @return array<Review>
   */
  public function getApprovedReviews(int $limit = 6): array
  {
    return $this->getReviewsByStatus("Approuvé", $limit);
  }
}