<?php

namespace App\Controllers;

use App\Utils\ImageUploader;
use App\Models\FileA;
use App\Models\FileS;
use App\Models\FileH;
use App\Repositories\FileARepository;
use App\Repositories\FileSRepository;
use App\Repositories\FileHRepository;
use lib\core\Response;
use lib\core\Logger;

class FileController extends Controllers
{
  private $fileARepository;
  private $fileSRepository;
  private $fileHRepository;
  private $imageUploader;

  public function __construct()
  {
    parent::__construct();
    $this->fileARepository = new FileARepository();
    $this->fileSRepository = new FileSRepository();
    $this->fileHRepository = new FileHRepository();
    $this->imageUploader = new ImageUploader();
  }

  /**
   * Upload d'images pour les animaux
   */
  public function uploadAnimalImage()
  {
    // Vérification connexion utilisateur
    if (!isset($_SESSION['id_user'])) {
      $response = new Response();
      $response->setStatusCode(401);
      $response->json([
        'success' => false,
        'message' => 'Utilisateur non connecté'
      ]);
      return $response;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      try {
        // Initialiser l'uploader avec le type 'animals'
        $this->imageUploader = new ImageUploader('animals');
        // Vérification des données reçues
        if (!isset($_POST['file_id_animal']) || !isset($_POST['file_name'])) {
          throw new \RuntimeException('Données manquantes');
        }

        $uploadedFiles = [];
        $errors = [];

        // Traitement de chaque fichier
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
          try {
            if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
              // Créer un tableau pour chaque fichier
              $singleFile = [
                'name' => $_FILES['image']['name'][$key],
                'type' => $_FILES['image']['type'][$key],
                'tmp_name' => $tmp_name,
                'error' => $_FILES['image']['error'][$key],
                'size' => $_FILES['image']['size'][$key]
              ];

              // Générer un nom unique pour chaque fichier
              $customFileName = pathinfo($_POST['file_name'], PATHINFO_FILENAME)
                . '_' . ($key + 1);

              // Upload du fichier
              $uploadResult = $this->imageUploader->uploadImage($singleFile, $customFileName);

              // Création de l'entrée dans la base de données
              $fileA = new FileA(
                null,
                $uploadResult['fileName'],
                $uploadResult['filePath'],
                $_POST['file_id_animal']
              );

              $this->fileARepository->insertImageToAnimal($fileA);
              $uploadedFiles[] = $uploadResult;
            }
          } catch (\Exception $e) {
            $errors[] = "Erreur avec le fichier " . $_FILES['image']['name'][$key] . ": " . $e->getMessage();
            Logger::error("Erreur upload fichier: " . $e->getMessage());
          }
        }

        $response = new Response();
        if (empty($uploadedFiles) && !empty($errors)) {
          $response->json([
            'success' => false,
            'message' => 'Erreurs lors de l\'upload : ' . implode(', ', $errors)
          ]);
        } else {
          $response->json([
            'success' => true,
            'message' => count($uploadedFiles) . ' image(s) ajoutée(s) avec succès',
            'data' => [
              'uploaded' => $uploadedFiles,
              'errors' => $errors
            ]
          ]);
        }
        return $response;
      } catch (\Exception $e) {
        Logger::error("Erreur lors de l'upload multiple: " . $e->getMessage());
        $response = new Response();
        $response->setStatusCode(500);
        $response->json([
          'success' => false,
          'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
        ]);
        return $response;
      }
    }

    // Si la méthode n'est pas POST
    $response = new Response();
    $response->setStatusCode(405);
    $response->json([
      'success' => false,
      'message' => 'Méthode non autorisée'
    ]);
    return $response;
  }

  /**
   * Upload d'images pour les services
   */
  public function uploadServiceImage()
  {
    
    // Vérification connexion utilisateur
    if (!isset($_SESSION['id_user'])) {
      $response = new Response();
      $response->setStatusCode(401);
      $response->json([
        'success' => false,
        'message' => 'Utilisateur non connecté'
      ]);
      return $response;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      if ($_SESSION['user_role']->value !== 'Admin' && $_SESSION['user_role']->value !== 'Staff') {
        $response = new Response();
        $response->setStatusCode(403);
        $response->json([
            'success' => false,
            'message' => 'Accès non autorisé'
        ]);
        return $response;
      }

      try {
        // Initialiser l'uploader avec le type 'services'
        $this->imageUploader = new ImageUploader('services');
        // Vérification des données reçues
        if (!isset($_POST['file_id_service']) || !isset($_POST['file_name'])) {
          Logger::error("Données manquantes - file_id_service: " . isset($_POST['file_id_service']) . ", file_name: " . isset($_POST['file_name']));
          throw new \RuntimeException('Données manquantes');
        }

        $uploadedFiles = [];
        $errors = [];

        // Traitement de chaque fichier
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
          try {
            if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
              $singleFile = [
                'name' => $_FILES['image']['name'][$key],
                'type' => $_FILES['image']['type'][$key],
                'tmp_name' => $tmp_name,
                'error' => $_FILES['image']['error'][$key],
                'size' => $_FILES['image']['size'][$key]
              ];

              $customFileName = pathinfo($_POST['file_name'], PATHINFO_FILENAME) . '_' . ($key + 1);
              $uploadResult = $this->imageUploader->uploadImage($singleFile, $customFileName);

              $fileS = new FileS(
                null,
                $uploadResult['fileName'],
                $uploadResult['filePath'],
                $_POST['file_id_service']
              );

              $this->fileSRepository->insertFileSToService($fileS);
              $uploadedFiles[] = $uploadResult;
            }
          } catch (\Exception $e) {
            $errors[] = "Erreur avec le fichier " . $_FILES['image']['name'][$key] . ": " . $e->getMessage();
            Logger::error("Erreur upload fichier: " . $e->getMessage());
          }
        }

        // Préparation de la réponse
        $response = new Response();
        if (empty($uploadedFiles) && !empty($errors)) {
          $response->json([
            'success' => false,
            'message' => 'Erreurs lors de l\'upload : ' . implode(', ', $errors)
          ]);
        } else {
          $response->json([
            'success' => true,
            'message' => count($uploadedFiles) . ' image(s) ajoutée(s) avec succès',
            'data' => [
              'uploaded' => $uploadedFiles,
              'errors' => $errors
            ]
          ]);
        }
        return $response;

      } catch (\Exception $e) {
        Logger::error("Erreur lors de l'upload multiple: " . $e->getMessage());
        $response = new Response();
        $response->setStatusCode(500);
        $response->json([
          'success' => false,
          'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
        ]);
        return $response;
      }
    }

    // Si la méthode n'est pas POST
    $response = new Response();
    $response->setStatusCode(405);
    $response->json([
      'success' => false,
      'message' => 'Méthode non autorisée'
    ]);
    return $response;
  }

  /**
   * Upload d'images pour les habitats
   */
  public function uploadHabitatImage()
  {
    // Vérification connexion utilisateur
    if (!isset($_SESSION['id_user'])) {
      $response = new Response();
      $response->setStatusCode(401);
      $response->json([
        'success' => false,
        'message' => 'Utilisateur non connecté'
      ]);
      return $response;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      try {
        // Initialiser l'uploader avec le type 'habitats'
        $this->imageUploader = new ImageUploader('habitats');
        // Vérification des données reçues
        if (!isset($_POST['file_id_habitat']) || !isset($_POST['file_name'])) {
          throw new \RuntimeException('Données manquantes');
        }

        $uploadedFiles = [];
        $errors = [];

        // Traitement de chaque fichier
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
          try {
            if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
              // Créer un tableau pour chaque fichier
              $singleFile = [
                'name' => $_FILES['image']['name'][$key],
                'type' => $_FILES['image']['type'][$key],
                'tmp_name' => $tmp_name,
                'error' => $_FILES['image']['error'][$key],
                'size' => $_FILES['image']['size'][$key]
              ];

              // Générer un nom unique pour chaque fichier
              $customFileName = pathinfo($_POST['file_name'], PATHINFO_FILENAME)
                . '_' . ($key + 1);

              // Upload du fichier
              $uploadResult = $this->imageUploader->uploadImage($singleFile, $customFileName);

              // Création de l'entrée dans la base de données
              $fileH = new FileH(
                null,
                $uploadResult['fileName'],
                $uploadResult['filePath'],
                $_POST['file_id_habitat']
              );

              $this->fileHRepository->insertImageToHabitat($fileH);
              $uploadedFiles[] = $uploadResult;
            }
          } catch (\Exception $e) {
            $errors[] = "Erreur avec le fichier " . $_FILES['image']['name'][$key] . ": " . $e->getMessage();
            Logger::error("Erreur upload fichier: " . $e->getMessage());
          }
        }

        $response = new Response();
        if (empty($uploadedFiles) && !empty($errors)) {
          $response->json([
            'success' => false,
            'message' => 'Erreurs lors de l\'upload : ' . implode(', ', $errors)
          ]);
        } else {
          $response->json([
            'success' => true,
            'message' => count($uploadedFiles) . ' image(s) ajoutée(s) avec succès',
            'data' => [
              'uploaded' => $uploadedFiles,
              'errors' => $errors
            ]
          ]);
        }
        return $response;
      } catch (\Exception $e) {
        Logger::error("Erreur lors de l'upload multiple: " . $e->getMessage());
        $response = new Response();
        $response->setStatusCode(500);
        $response->json([
          'success' => false,
          'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
        ]);
        return $response;
      }
    }

    // Si la méthode n'est pas POST
    $response = new Response();
    $response->setStatusCode(405);
    $response->json([
      'success' => false,
      'message' => 'Méthode non autorisée'
    ]);
    return $response;
  }


  /**
   * Supprime une image (méthode générique)
   */
  public function deleteImage(string $type, int $id): Response
  {
    try {
      $success = false;
      switch ($type) {
        case 'animal':
          $success = $this->fileARepository->deleteFileA($id);
          break;
        case 'service':
          $success = $this->fileSRepository->deleteFileS($id);
          break;
        case 'habitat':
          $success = $this->fileHRepository->deleteFileH($id);
          break;
        default:
          throw new \RuntimeException('Type d\'image invalide');
      }

      $response = new Response();
      if ($success) {
        $response->json([
          'success' => true,
          'message' => 'Image supprimée avec succès'
        ]);
      } else {
        $response->json([
          'success' => false,
          'message' => 'Erreur lors de la suppression de l\'image'
        ], 400);
      }
      return $response;
    } catch (\Exception $e) {
      Logger::error("Erreur lors de l'upload multiple: " . $e->getMessage());
      $response = new Response();
      $response->setStatusCode(500);
      $response->json([
        'success' => false,
        'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
      ]);
      return $response;
    }
  }
}
