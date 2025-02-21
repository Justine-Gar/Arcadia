<?php

namespace App\Repositories;

use App\Models\FileH;
use lib\core\Logger;
use PDO;
use PDOException;

/** 
 * Repository pour la gestion des images Habitat
 * 
 */
class FileHRepository extends Repositories
{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Methode ajouter une nouvelle image pour l'habitat
     * @param FileH $file
     * @return FileH
     * @throws PDOException
     */
    public function insertImageToHabitat(FileH $file): FileH
    {
        try {

            Logger::info("Tentative d'insertion dans la BDD avec : " . json_encode([
                'fileName' => $file->getFileName(),
                'filePath' => $file->getFilePath(),
                'habitatId' => $file->getHabitatId()
            ]));
            
            $query = "INSERT INTO `fileH` (`fileName`, `filePath`, `id_habitat`) VALUES (:fileName, :filePath, :id_habitat)";
            $stmt = $this->db->prepare($query);
            $fileName = $file->getFileName();
            $filePath = $file->getFilePath();
            $habitatId = $file->getHabitatId();

            $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);
            $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':id_habitat', $habitatId, PDO::PARAM_INT);

            $stmt->execute();

            $id = $this->db->lastInsertId();
            $file->setIdFileH($id);

            Logger::info("Nouvelle image ajoutée (ID: $id) pour l'habitat ID $habitatId");
            return $file;
        } catch (PDOException $e) {
            Logger::error("Erreur lors de l'ajout d'une image pour l'habitat ID " . $file->getHabitatId() . ": " . $e->getMessage());
            throw new PDOException("Erreur lors de l'ajout de l'image : " . $e->getMessage());
        }
    }

    /**
     * Récupère une image par son ID
     * @param int $id
     * @return FileH|null
     */
    public function getById(int $id): ?FileH
    {
        try {
            $query = "SELECT * FROM fileH WHERE id_fileH = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) return null;

            return new FileH(
                $result['id_fileH'],
                $result['fileName'],
                $result['filePath'],
                $result['id_habitat']
            );
        } catch (PDOException $e) {
            Logger::error("Erreur lors de la récupération de l'image ID $id: " . $e->getMessage());
            throw new PDOException("Erreur lors de la récupération de l'image : " . $e->getMessage());
        }
    }

    /**
     * Recupere tout les id 
     * @return array
     */
    public function getAllFileHId(): array
    {
        try {
            $query = "SELECT id_fileH, filePath FROM fileH";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $files = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $files[$row['id_fileH']] = $row['filePath'];
            }
            return $files;
        } catch (PDOException $e) {
            Logger::error("Erreur lors de la récupération des fichiers: " . $e->getMessage());
            throw new PDOException("Erreur lors de la récupération des fichiers: " . $e->getMessage());
        }
    }

    /**
     * Récupère toutes les images d'un habitat
     * @param int $habitatId
     * @return array
     */
    public function getByHabitatId(int $habitatId): array
    {
        try {
            $query = "SELECT * FROM fileH WHERE id_habitat = :habitatId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':habitatId', $habitatId, PDO::PARAM_INT);
            $stmt->execute();

            $images = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $images[] = new FileH(
                    $row['id_fileH'],
                    $row['fileName'],
                    $row['filePath'],
                    $row['id_habitat']
                );
            }
            return $images;
        } catch (PDOException $e) {
            Logger::error("Erreur lors de la récupération des images pour l'habitat ID $habitatId: " . $e->getMessage());
            throw new PDOException("Erreur lors de la récupération des images : " . $e->getMessage());
        }
    }

    /**
     * Supprime une image
     * @param int $id
     * @return bool
     */
    public function deleteFileH(int $id): bool
    {
        try {
            $file = $this->getById($id);
            if ($file && file_exists($file->getFilePath())) {
                unlink($file->getFilePath()); // Supprime le fichier physique
            }

            $query = "DELETE FROM fileH WHERE id_fileH = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $result = $stmt->execute();
            if ($result) {
                Logger::info("Image supprimée (ID: $id)");
            }
            return $result;
        } catch (PDOException $e) {
            Logger::error("Erreur lors de la suppression de l'image ID $id: " . $e->getMessage());
            throw new PDOException("Erreur lors de la suppression de l'image : " . $e->getMessage());
        }
    }
}
