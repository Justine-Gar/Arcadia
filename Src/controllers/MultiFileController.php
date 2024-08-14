<?php

namespace App\Controllers;

use App\repositories\FileARepository;
use App\repositories\FileSRepository;
use App\repositories\FileHRepository;
use App\models\FileA;
use App\models\FileS;
use App\models\FileH;
use lib\core\Logger;

class MultiFileController extends Controllers
{
    private FileARepository $fileARepository;
    private FileSRepository $fileSRepository;
    private FileHRepository $fileHRepository;

    public function __construct(FileARepository $fileARepository, FileSRepository $fileSRepository, FileHRepository $fileHRepository)
    {
        $this->fileARepository = $fileARepository;
        $this->fileSRepository = $fileSRepository;
        $this->fileHRepository = $fileHRepository;
    }

    /**
     * Gère le processus d'upload d'image
     */
    public function handleImageUpload()
    {
        // Vérifie si la méthode de requête est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respondWithError("Méthode non autorisée", 405);
            return;
        }

        // Vérifie si tous les champs nécessaires sont présents
        if (!isset($_POST['entity_type']) || !isset($_POST['entity_id']) || !isset($_FILES['image'])) {
            $this->respondWithError("Données manquantes", 400);
            return;
        }

        $entityType = $_POST['entity_type'];
        // Valide et filtre l'ID de l'entité
        $entityId = filter_input(INPUT_POST, 'entity_id', FILTER_VALIDATE_INT);
        if ($entityId === false || $entityId === null) {
            $this->respondWithError('ID d\'entité invalide ou manquant', 400);
            return;
        }
        $uploadedFile = $_FILES['image'];

        // Vérifie s'il y a eu une erreur lors de l'upload
        if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            $this->respondWithError("Erreur lors de l'upload du fichier", 400);
            return;
        }

        // Vérifie la taille du fichier
        if ($uploadedFile['size'] > MAX_FILE_SIZE) {
            $this->respondWithError("Le fichier est trop volumineux. Taille maximale: " . (MAX_FILE_SIZE / 1000000) . "MB", 400);
            return;
        }

        // Vérifie le type MIME du fichier
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $uploadedFile['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, ALLOWED_MIME_TYPES)) {
            $this->respondWithError("Type de fichier non autorisé. Types autorisés: " . implode(', ', ALLOWED_EXTENSIONS), 400);
            return;
        }

        // Vérifie l'extension du fichier
        $extension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), ALLOWED_EXTENSIONS)) {
            $this->respondWithError("Extension de fichier non autorisée. Extensions autorisées: " . implode(', ', ALLOWED_EXTENSIONS), 400);
            return;
        }

        // Génère un nom de fichier unique
        $fileName = uniqid() . '_' . basename($uploadedFile['name']);
        $filePath = UPLOAD_DIR . $fileName;

        // Déplace le fichier uploadé vers le répertoire de destination
        if (!move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
            $this->respondWithError("Échec du déplacement du fichier uploadé", 500);
            return;
        }

        try {
            $fileId = null;
            // Utilise le repository approprié selon le type d'entité
            switch ($entityType) {
                case 'animal':
                    $fileA = new FileA(null, $fileName, $filePath, $entityId);
                    $fileId = $this->fileARepository->insertImageToAnimal($fileA);
                    break;
                case 'service':
                    $fileS = new FileS(null, $fileName, $filePath, $entityId);
                    $fileId = $this->fileSRepository->insertImageToService($fileS);
                    break;
                case 'habitat':
                    $fileH = new FileH(null, $fileName, $filePath, $entityId);
                    $fileId = $this->fileHRepository->insertImageToHabitat($fileH);
                    break;
                default:
                    $this->respondWithError("Type d'entité non reconnu", 400);
                    return;
            }

            // Répond avec un succès si l'insertion s'est bien passée
            $this->respondWithSuccess([
                'message' => "Image uploadée avec succès pour $entityType",
                'file_id' => $fileId
            ]);
        } catch (\PDOException $e) {
            // Log l'erreur et répond avec une erreur en cas de problème avec la base de données
            Logger::error("Erreur lors de l'insertion de l'image dans la base de données : " . $e->getMessage());
            $this->respondWithError("Erreur lors de l'enregistrement de l'image", 500);
        }
    }

    /**
     * Envoie une réponse d'erreur en JSON
     * 
     * @param string $message Message d'erreur
     * @param int $statusCode Code de statut HTTP
     */
    private function respondWithError($message, $statusCode)
    {
        http_response_code($statusCode);
        echo json_encode(['error' => $message]);
    }

    /**
     * Envoie une réponse de succès en JSON
     * 
     * @param array $data Données à envoyer
     */
    private function respondWithSuccess($data)
    {
        http_response_code(200);
        echo json_encode($data);
    }
}
