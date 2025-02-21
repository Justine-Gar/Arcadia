<?php
namespace App\Utils;

use lib\core\Logger;

class ImageUploader
{
    // Les constantes pour les chemins, tailles et types de fichiers
    private string $uploadDir;
    private string $publicPath;
    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10Mo
    private const THUMBNAIL_WIDTH = 300; // Largeur fixe des miniatures
    private const ALLOWED_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/avif' => 'avif',
        'image/webp' => 'webp'
    ];

    public function __construct(string $type = 'animals')
    {
        // Définition des chemins dans le constructeur
        $baseUploadDir = __DIR__ . '/../../public/assets/upload/';
        $basePublicPath = '/assets/upload/';
        //Les sous-dossier selon le type
        switch ($type) {
            case 'services':
                $folder = 'services';
                break;
            case 'habitats':
                $folder = 'habitats';
                break;
            default:
                $folder = 'animals';
        }

        $this->uploadDir = $baseUploadDir . $folder . '/';
        $this->publicPath = $basePublicPath . $folder . '/';

        // Création du dossier d'upload s'il n'existe pas
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Gère l'upload d'une image
     * 
     * @param array $file Données du fichier ($_FILES['key'])
     * @param string|null $customFileName Nom de fichier personnalisé
     * @return array Informations sur le fichier uploadé
     * @throws \RuntimeException Si une erreur survient
     */
    public function uploadImage(array $file, ?string $customFileName = null): array
    {
        Logger::info("Début uploadImage avec customFileName: " . $customFileName);
        Logger::info("Dossier d'upload: " . $this->uploadDir);
        Logger::info("Chemin public: " . $this->publicPath);

        $this->validateUpload($file);

        // Vérification du type MIME
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $this->detecteMimeType($file);

        if (!array_key_exists($mimeType, self::ALLOWED_TYPES)) {
            throw new \RuntimeException('Format de fichier non autorisé');
        }

        $extension = self::ALLOWED_TYPES[$mimeType];
        
        // Utilisation du nom personnalisé si fourni
        if ($customFileName) {
            // Nettoyage du nom de fichier
            $fileName = $this->sanitizeFileName($customFileName) . '.' . $extension;
            
            // Si le fichier existe déjà, ajouter un suffixe numérique
            $baseFileName = pathinfo($fileName, PATHINFO_FILENAME);
            $counter = 1;
            while (file_exists($this->uploadDir . $fileName)) {
                $fileName = $baseFileName . '_' . $counter . '.' . $extension;
                $counter++;
            }
        } else {
            // Fallback sur la génération d'un nom unique si aucun nom n'est fourni
            $fileName = uniqid('animal_') . '.' . $extension;
        }

        $filePath = $this->uploadDir . $fileName;
        $publicPath = $this->publicPath . $fileName;

        // Traitement différent selon le type d'image
        if ($mimeType === 'image/avif' || $mimeType === 'image/webp') {
            $this->handleSpecialFormats($file, $filePath);
        } else {
            $this->createThumbnail($file['tmp_name'], $filePath, $mimeType);
        }

        return [
            'fileName' => $fileName,
            'filePath' => $publicPath
        ];
    }

    /**
     * Nettoie le nom de fichier pour le rendre sûr
     */
    private function sanitizeFileName(string $fileName): string
    {
        // Supprime l'extension si présente
        $fileName = pathinfo($fileName, PATHINFO_FILENAME);
        
        // Remplace les caractères spéciaux et espaces
        $fileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $fileName);
        
        // Supprime les underscores multiples
        $fileName = preg_replace('/_+/', '_', $fileName);
        
        // Limite la longueur du nom de base (laissez de la place pour l'extension)
        $fileName = substr($fileName, 0, 180); // Réduit à 180 pour laisser de la place pour l'extension
        
        return $fileName;
    }

    /**
     * Valide les données de base du fichier
     * 
     * @param array $file
     * @throws \RuntimeException
     */
    private function validateUpload(array $file): void
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Erreur lors de l\'upload : ' . $this->getUploadErrorMessage($file['error']));
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new \RuntimeException('Fichier trop volumineux (maximum ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'Mo)');
        }
    }

    /**
     * Gère les formats spéciaux comme AVIF et WebP
     */
    private function handleSpecialFormats(array $file, string $destination): void
    {
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \RuntimeException('Échec de la sauvegarde du fichier');
        }
    }

    /**
     * Détecte le type MIME d'un fichier de plusieur façon
     * 
     */
    private function detecteMimeType(array $file): string
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($extension === 'avif') {
            return 'image/avif';
        }
        if ($extension === 'webp') {
            return 'image/webp';
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
    
        if ($mimeType === 'application/octet-stream' && in_array($extension, ['avif', 'webp'])) {
            return 'image/' . $extension;
        }
    
        return $mimeType;
    }
    /**
     * Crée une miniature de l'image
     */
    private function createThumbnail(string $sourcePath, string $destinationPath, string $mimeType): void
    {
        // Récupération des dimensions de l'image originale
        list($width, $height) = getimagesize($sourcePath);
        $newHeight = ($height / $width) * self::THUMBNAIL_WIDTH;

        // Création de la nouvelle image
        $thumb = imagecreatetruecolor(self::THUMBNAIL_WIDTH, $newHeight);

        // Chargement de l'image source
        $source = $this->createImageFromType($sourcePath, $mimeType);

        // Gestion de la transparence pour les PNG
        if ($mimeType === 'image/png') {
            $this->handlePngTransparency($thumb);
        }

        // Redimensionnement de l'image
        imagecopyresampled(
            $thumb,
            $source,
            0,
            0,
            0,
            0,
            self::THUMBNAIL_WIDTH,
            $newHeight,
            $width,
            $height
        );

        // Sauvegarde de l'image
        $this->saveImage($thumb, $destinationPath, $mimeType);

        // Libération de la mémoire
        imagedestroy($thumb);
        imagedestroy($source);
    }

    /**
     * Crée une ressource image selon le type MIME
     */
    private function createImageFromType(string $path, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            default:
                throw new \RuntimeException('Format non supporté pour la création de miniature');
        }
    }

    /**
     * Configure la transparence pour les images PNG
     */
    private function handlePngTransparency($image): void
    {
        imagealphablending($image, false);
        imagesavealpha($image, true);
    }

    /**
     * Sauvegarde l'image selon son type
     */
    private function saveImage($image, string $path, string $mimeType): void
    {
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($image, $path, 85);
                break;
            case 'image/png':
                imagepng($image, $path, 8);
                break;
            case 'image/gif':
                imagegif($image, $path);
                break;
        }
    }

    /**
     * Retourne un message d'erreur explicite pour les erreurs d'upload
     */
    private function getUploadErrorMessage(int $error): string
    {
        switch ($error) {
            case UPLOAD_ERR_INI_SIZE:
                return 'Le fichier dépasse la taille maximale autorisée par PHP';
            case UPLOAD_ERR_FORM_SIZE:
                return 'Le fichier dépasse la taille maximale autorisée par le formulaire';
            case UPLOAD_ERR_PARTIAL:
                return 'Le fichier n\'a été que partiellement uploadé';
            case UPLOAD_ERR_NO_FILE:
                return 'Aucun fichier n\'a été uploadé';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Le dossier temporaire est manquant';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Échec de l\'écriture du fichier sur le disque';
            case UPLOAD_ERR_EXTENSION:
                return 'Une extension PHP a arrêté l\'upload du fichier';
            default:
                return 'Erreur inconnue lors de l\'upload';
        }
    }
}
