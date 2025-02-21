<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\AnimalRepository;
use App\Repositories\ReportRepository;
use lib\core\Response;
use App\Models\Health;
use App\Models\Role;
use DateTime;
use lib\core\Logger;

class ReportController extends Controllers
{
  private $reportRepository;
  private $animalRepository;

  public function __construct()
  {
    parent::__construct();
    $this->reportRepository = new ReportRepository;
    $this->animalRepository = new AnimalRepository;
  }
  /** lire tout les rapport journalier
   * 
   */
  public function gestionJournal()
  {
    try {
      // Récupérer la page actuelle
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $reportsPerPage = 5; // Nombre de rapports par page
      $offset = ($page - 1) * $reportsPerPage;

      // Récupérer le nombre total de rapports pour la pagination
      $totalReports = $this->reportRepository->countTotalReports();
      $totalPages = ceil($totalReports / $reportsPerPage);

      // Récupérer les rapports pour la page actuelle
      $reports = $this->reportRepository->getReportsPaginated($offset, $reportsPerPage);
      $animals = $this->animalRepository->getAllAnimal();

      $data = [
          'title' => 'Gestion des Rapports',
          'reports' => $reports,
          'animals' => $animals,
          'healthStatus' => Health::cases(),
          'totalPages' => $totalPages,
          'pageActuelle' => $page,
          'roles' => Role::cases(),
          'selectedAnimalId' => $_GET['animal_id'] ?? null,
          'startDate' => $_GET['start'] ?? null
      ];

      return match ($_SESSION['user_role']) {
          Role::Admin => $this->renderAdmin('gestionJournal', $data),
          Role::Veto => $this->renderVeto('gestionJournalVeto', $data),
          Role::Staff => $this->renderStaff('gestionJournalStaff', $data),
          default => header('Location: /unauthorized')
      };

    } catch (\Exception $e) {
        Logger::error("Erreur dans gestionJournal: " . $e->getMessage());
        header('Location: /error');
        exit;
    }
  }

  /** Ajouter un rapport journalier
   * 
   */
  public function ajouterRapport() 
  {
    $message = '';
    $messageType = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      //Verification si User Connecter
      if (!isset($_SESSION['id_user'])) {
        $response = new Response();
        $response->setStatusCode(401);
        $response->json(['success' => false, 'message' => 'Utilisateur non connecté']);
        return $response;
      }

      // Vérification du rôle
      if ($_SESSION['user_role']->value !== 'Admin' && $_SESSION['user_role']->value !== 'Veto') {
        $response = new Response();
        $response->setStatusCode(403);
        $response->json([
            'success' => false,
            'message' => 'Accès non autorisé'
        ]);
        return $response;
      }

      $id_user = $_SESSION['id_user'];

      $jsonData = json_decode(file_get_contents('php://input'), true);
      Logger::info("Données reçues pour la création du rapport : " . json_encode($jsonData));

      if (empty($jsonData['add_health_status']) || empty($jsonData['add_passage']) || empty($jsonData['add_id_animal'])) {
        throw new \InvalidArgumentException("Données manquantes pour la création du rapport");
      }

      try {
        //récupere donner en json
        $health_status = Health::from($jsonData['add_health_status'] ?? '');
        $passage = new DateTime(); //date actuelle
        $prescription = $jsonData['add_prescription'] ?? null;
        $quantity = $jsonData['add_quantity'] ?? null;
        $habitat_condition = $jsonData['add_habitat_condition'] ?? null;
        $id_animal = intval($jsonData['add_id_animal'] ?? 0);

        //creation du nouveau rapport
        $newReport = $this->reportRepository->createReport(
          $health_status,
          $passage,
          $prescription,
          $quantity,
          $habitat_condition,
          $id_animal,
          $id_user
        );

        Logger::info("Rapport créé avec succès. ID: " . $newReport->getIdReport());
        $message = 'Rapport crée avec succès';
        $messageType = 'success';

        $response = new Response();
        $response->json([
          'success' => true,
          'message' => 'Rapport créé avec succès',
          'report' => $newReport
        ]);
        return $response;

      } catch (\Exception $e) {

        Logger::error("Erreur lors de la création du rapport:" . $e->getMessage());
        $message = 'Erreur lors de la création du rapport';
        $messageType = 'error';

        $response = new Response();
        $response->setStatusCode(500);
        $response->json([
          'success' => false,
          'message' => 'Erreur lors de la création du rapport: ' . $e->getMessage()
        ]);
        return $response;
      }
    }

    $reports = $this->reportRepository->getAllReports();

    $data = [
      'title' => 'Gestion des Rapports',
      'reports' => $reports,
      'message' => $message,
      'messageType' => $messageType
    ];

    return $this->renderAdmin('gestionJournal', $data);
  }

  /** Modifier un rapport journalier
   * 
   */
  public function modifierRapport() 
  {
    // verification connexion utilisateur
    if (!isset($_SESSION['id_user'])) {
      $response = new Response();
      $response->setStatusCode(401);
      $response->json([
          'success' => false, 
          'message' => 'Utilisateur non connecté'
      ]);
      return $response;
    }

    //verification method post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      try {
        //recupere les donnée en json
        $jsonData = json_decode(file_get_contents('php://input'), true);
        //Logger::info("Données reçues pour les modification: " . print_r($jsonData, true));

        //vérifier les donnée json acquise
        if (!isset($jsonData['id']) ||
          !isset($jsonData['modify_health_status']) ||
          !isset($jsonData['modify_prescription']) ||
          !isset($jsonData['modify_quantity']) ||
          !isset($jsonData['modify_habitat_condition'])) 
        {
          throw new \InvalidArgumentException("Données manquantes pour la modification du rapport");
        }

        //transformer donner json en variable
        $id_report = (int)$jsonData['id'];
        $health_status = Health::from($jsonData['modify_health_status']);
        $passage = new DateTime();
        $prescription = $jsonData['modify_prescription'];
        $quantity = $jsonData['modify_quantity'];
        $habitat_condition = $jsonData['modify_habitat_condition'];

        $updateReport = $this->reportRepository->updateReport($id_report, $health_status, $passage, $prescription, $quantity, $habitat_condition);

        //vérification du resultat
        if($updateReport) {
          Logger::info("Animal modifié avec succes.");
          $response = new Response();
          $response->json([
            'success' => true,
            'message' => 'Le rapport à été modifié avec succès',
            'date_passage' => $passage->format('Y-m-d H:i')
          ]);
          
          return $response;

        } else {

          Logger::error("Le rapport n\'a été modifié'");
          $response = new Response();
          $response->setStatusCode(404);
          $response->json([
              'success' => false,
              'message' => 'Le rapport n\'a été modifié'
          ]);
        }
      } catch (\Exception $e) {
        // Erreur serveur
        Logger::error("Erreur lors de la modification du rapport: " . $e->getMessage());
        $response = new Response();
        $response->setStatusCode(500);
        $response->json([
            'success' => false,
            'message' => 'Erreur lors de la modification du rapport: ' . $e->getMessage()
        ]);
        return $response;
      }

      $response = new Response();
      $response->setStatusCode(405);
      $response->json([
        'success' => false, 
        'message' => 'Méthode non autorisée'
      ]);
      return $response;
    }
  }

  /**
   * 
   */
  public function supprimerRapport()
  {
      if (!isset($_SESSION['id_user'])) {
          $response = new Response();
          $response->setStatusCode(401);
          $response->json(['success' => false, 'message' => 'Utilisateur non connecté']);
          return $response;
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          try {
              $jsonData = json_decode(file_get_contents('php://input'), true);
              
              if (!isset($jsonData['reportIds']) || !isset($jsonData['reportInfos'])) {
                  throw new \InvalidArgumentException("Données manquantes pour la suppression");
              }

              $reportIds = $jsonData['reportIds'];
              
              foreach ($reportIds as $id) {
                  $result = $this->reportRepository->deleteReport((int)$id);
                  if (!$result) {
                      throw new \Exception("Erreur lors de la suppression du rapport avec l'ID: $id");
                  }
              }

              $response = new Response();
              $response->json([
                  'success' => true,
                  'message' => 'Les rapports ont été supprimés avec succès'
              ]);
              return $response;

          } catch (\Exception $e) {
              Logger::error("Erreur lors de la suppression des rapports: " . $e->getMessage());
              $response = new Response();
              $response->setStatusCode(500);
              $response->json([
                  'success' => false,
                  'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
              ]);
              return $response;
          }
      }
      
      $response = new Response();
      $response->setStatusCode(405);
      $response->json([
          'success' => false,
          'message' => 'Méthode non autorisée'
      ]);
      return $response;
  }
  
  /** method pour filtrer un rapport
   * 
   */
  public function filtrer() 
  {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $byPage = 10;
    $offset = ($page - 1) * $byPage;

    $animalId = !empty($_GET['animal_id']) ? intval($_GET['animal_id']) : null;
    $startDate = !empty($_GET['start']) ? new DateTime($_GET['start']) : null;

    $totalReport = $this->reportRepository->countFilteredReports($animalId, $startDate);
    $totalPages = ceil($totalReport / $byPage);

    // Utilisons toujours getFilteredReportsPaginated, même sans filtres
    $reports = $this->reportRepository->getFilteredReportsPaginated($offset, $byPage, $animalId, $startDate);
    
    $animals = $this->animalRepository->getAllAnimal();

    $data = [
        'title' => 'Rapports Filtrés',
        'reports' => $reports,
        'animals' => $animals,
        'healthStatus' => Health::cases(),
        'selectedAnimalId' => $animalId,
        'startDate' => $startDate ? $startDate->format('Y-m-d') : '',
        'pageActuelle' => $page,
        'totalPages' => $totalPages,
        'animalId' => $animalId,
        'start' => $startDate ? $startDate->format('Y-m-d') : ''
    ];

    try {
      // Utiliser directement l'enum Role stocké dans la session
      return match ($_SESSION['user_role']) {
          Role::Admin => $this->renderAdmin('gestionJournal', $data),
          Role::Veto => $this->renderVeto('gestionJournalVeto', $data),
          Role::Staff => $this->renderStaff('gestionJournalStaff', $data),
          default => header('Location: /unauthorized')
      };

    } catch (\Exception $e) {
        Logger::error("Erreur dans gestionServices: " . $e->getMessage());
        header('Location: /error');
        exit;
    }
  }
}

