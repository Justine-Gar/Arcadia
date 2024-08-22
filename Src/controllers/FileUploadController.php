<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\FileSRepository;
use App\Models\FileS;
use lib\core\Response;

class FileUploadController extends Controllers
{
  private $fileSRepository;

  public function __construct()
  {
    parent::__construct();
    $this->fileSRepository = new FileSRepository;
  }

  public function uploadFile() 
  {
    //verifier les donnée(fichier) uploadé
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {

      $response = new Response();
      $response->setStatusCode(400);
      $response->json(['error' => false, 'message' => 'Aucun fichier uploadé ou erreur lors de l\'upload']);
      return $response;
    }

    //récupere les donné puis vérifié si id_servoce existe
    $file = $_FILES['image'];
    $serviceId = $_POST['service_id'] ?? null; //doit etre envoyer avec la requetes

    if (!$serviceId) {
      $response = new Response();
      $response->setStatusCode(400);
      $response->json(['error' => false, 'message' => 'Id de service non fourni']);
      return $response;
    }

    // Créer le dossier de destination s'il n'existe pas
    $uploadDir = 'public/assets/upload/FileS';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    // Générer un nom de fichier unique
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid('service_' . $serviceId . '_') . '.' . $extension;
    $filePath = $uploadDir . '/' . $newFileName;

    // Déplacer le fichier uploadé
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
      $response = new Response();
      $response->setStatusCode(500);
      $response->json(['error' => false, 'message' => 'Erreur lors du déplacement du fichier uploadé']);
      return $response;  
    }

    // Créer une nouvelle entrée dans la base de données
    $fileS = new FileS(null, $newFileName, $filePath, $serviceId);
    $savedFile = $this->fileSRepository->insertFileSToService($fileS);

    if (!$savedFile) {
      // Si l'enregistrement en base de données échoue, supprimer le fichier uploadé
      unlink($filePath);
      $response = new Response();
      $response->setStatusCode(500);
      $response->json(['error' => false, 'message' => 'Erreur lors de l\'enregistrement de la base de donnée']);
      return $response;
    }

    $response = new Response();
    $response->setStatusCode(201);
    $response->json(['sucess' => true, 'file' => $savedFile]);
    return $response;
  }
}