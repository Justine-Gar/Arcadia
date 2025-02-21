<?php

namespace App\Repositories;

use lib\config\database;
use App\Models\Animal;
use App\Models\FileA;
use lib\core\Logger;
use PDO;
use PDOException;

/** 
 * Repository pour la gestion des images Animal
 * 
 */
class FileARepository extends Repositories
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Methode ajouter une nouvelle image à un animal
     * @param FileA $file
     * @return FileA
     * @throws PDOException
     */
    public function insertImageToAnimal(FileA $file): FileA
    {
        try {
            $query = "INSERT INTO `fileA` (`fileName`, `filePath`, `id_animal`) VALUES (:fileName, :filePath, :id_animal)";
            $stmt = $this->db->prepare($query);
            $fileName = $file->getFileName();
            $filePath = $file->getFilePath();
            $animalId = $file->getAnimalId();

            $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);
            $stmt->bindParam(':filePath', $filePath, PDO::PARAM_STR);
            $stmt->bindParam(':id_animal', $animalId, PDO::PARAM_INT);

            $stmt->execute();

            $id = $this->db->lastInsertId();
            $file->setIdFileA($id);

            Logger::info("Nouvelle image ajoutée (ID: $id) pour l'animal ID $animalId");
            return $file;
        } catch (PDOException $e) {
            Logger::error("Erreur lors de l'ajout d'une image pour l'animal ID " . $file->getAnimalId() . ": " . $e->getMessage());
            throw new PDOException("Erreur lors de l'ajout de l'image : " . $e->getMessage());
        }
    }

    /**
     * Récupère une image par son ID
     * @param int $id
     * @return FileA|null
     */
    public function getById(int $id): ?FileA
    {
        try {
            $query = "SELECT * FROM fileA WHERE id_fileA = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) return null;

            return new FileA(
                $result['id_fileA'],
                $result['fileName'],
                $result['filePath'],
                $result['id_animal']
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
    public function getAllFileAId(): array
    {
        try {
            $query = "SELECT id_fileA, filePath FROM fileA";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $files = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $files[$row['id_fileA']] = $row['filePath'];
            }
            return $files;
        } catch (PDOException $e) {
            Logger::error("Erreur lors de la récupération des fichiers: " . $e->getMessage());
            throw new PDOException("Erreur lors de la récupération des fichiers: " . $e->getMessage());
        }
    }
    
    /**
     * Récupère toutes les images d'un animal
     * @param int $animalId
     * @return array
     */
    public function getByAnimalId(int $animalId): array
    {
        try {
            $query = "SELECT * FROM fileA WHERE id_animal = :animalId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':animalId', $animalId, PDO::PARAM_INT);
            $stmt->execute();

            $images = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $images[] = new FileA(
                    $row['id_fileA'],
                    $row['fileName'],
                    $row['filePath'],
                    $row['id_animal']
                );
            }
            return $images;
        } catch (PDOException $e) {
            Logger::error("Erreur lors de la récupération des images pour l'animal ID $animalId: " . $e->getMessage());
            throw new PDOException("Erreur lors de la récupération des images : " . $e->getMessage());
        }
    }

    /**
     * Supprime une image
     * @param int $id
     * @return bool
     */
    public function deleteFileA(int $id): bool
    {
        try {
            $file = $this->getById($id);
            if ($file && file_exists($file->getFilePath())) {
                unlink($file->getFilePath()); // Supprime le fichier physique
            }

            $query = "DELETE FROM fileA WHERE id_fileA = :id";
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
