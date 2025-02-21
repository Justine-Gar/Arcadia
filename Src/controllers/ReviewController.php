<?php

namespace App\Controllers;


use App\Controllers\Controllers;
use App\Models\Review;
use App\Repositories\ReviewRepository;
use lib\core\Response;
use lib\core\Logger;

class  ReviewController extends Controllers
{
  private $reviewRepository;

  public function __construct()
  {
    parent::__construct();
    $this->reviewRepository = new ReviewRepository;
  }


  public function gestionAvis()
  {
    // Vérification des droits d'accès
    if (!isset($_SESSION['id_user']) || !isset($_SESSION['user_role'])) {
      header('Location: /login');
      exit;
    }
    $reviews = $this->reviewRepository->getAllReviews();

    $data = [
      'title' => 'Gestion des Avis',
      'reviews' => $reviews
    ];

    return $this->renderStaff('gestionAvis', $data);
  }

  
  /**
   * Met à jour le statut d'un avis
   */
  public function modifierStatusAvis()
  {
    if (!isset($_SESSION['id_user'])) {
      $response = new Response();
      $response->setStatusCode(401);
      $response->json(['success' => false, 'message' => 'Non autorisé']);
      return $response;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $response = new Response();
      $response->setStatusCode(405);
      $response->json(['success' => false, 'message' => 'Méthode non autorisée']);
      return $response;
    }

    try {
      $jsonData = json_decode(file_get_contents('php://input'), true);

      if (!isset($jsonData['id_review']) || !isset($jsonData['status'])) {
        throw new \InvalidArgumentException("Données manquantes");
      }

      $id_review = (int)$jsonData['id_review'];
      $status = $jsonData['status'];

      if ($this->reviewRepository->updateReviewStatus($id_review, $status)) {
        $response = new Response();
        $response->json([
          'success' => true,
          'message' => 'Statut mis à jour avec succès'
        ]);
        return $response;
      }

      throw new \Exception("Erreur lors de la mise à jour du statut");
    } catch (\Exception $e) {
      Logger::error("Erreur lors de la mise à jour du statut: " . $e->getMessage());
      $response = new Response();
      $response->setStatusCode(500);
      $response->json([
        'success' => false,
        'message' => 'Erreur lors de la mise à jour du statut'
      ]);
      return $response;
    }
  }
}
