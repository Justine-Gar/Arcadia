<?php
namespace App\Models;

use InvalidArgumentException;

/**
 * Class qui représente les images dans la systeme lier à Habitat
 */
class FileH {

  private ?int $id_fileH;
  private string $fileName;
  private string $filePath;
  private int $habitat_id;

  /** Constructeur de la classe FileH
   * 
   * @param ?int $id_fileh est l'identifiant du file
   * @param string $fileName le nom du file
   * @param string $file_path le chemin de l'image
   * @param ?int $id_habitat l'identifiant de l'habitat
   */
  public function __construct(?int $id_fileH, string $fileName, string $filePath, int $habitat_id)
  {
    $this->setIdFileH($id_fileH);
    $this->setFileName($fileName);
    $this->setFilePath($filePath);
    $this->setHabitatId($habitat_id);
  }

  //GETTERS

  /** Obtient l'id du file
   * 
   * @return ?int
   */
  public function getIdFileH(): ?int { return $this->id_fileH;}

  /** Obtient le nom du file
   * 
   * @return string
   */
  public function getFileName(): string { return $this->fileName;}

  /** Obtient le chemin de l'image
   * 
   * @return string
   */
  public function getFilePath(): string { return $this->filePath;}

  /** Obtient l'identifiant habitat du file
   * 
   * @return int
   */
  public function getHabitatId(): int { return $this->habitat_id;}


  //SETTERS

  /** Definit l'id de l'habitat
   * 
   * @param ?int le nouveau identifiant
   * @throws InvalidArgumentException
   */
  public function setIdFileH(?int $id_fileH): void
  {
    if ($id_fileH !== null && $id_fileH <= 0)
    {
      throw new InvalidArgumentException("L'id doit un etre un entier positif");
    }
    $this->id_fileH = $id_fileH;
  }

  /** Defini le nom ddu file
   * 
   * @param string nom de l'image
   * @throws InvalidArgumentException
   */
  public function setFileName(string $fileName): void
  {
    $fileName = trim($fileName);
    if (empty($fileName))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($fileName) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->fileName = $fileName;
  }

  /** Defini le chemin de l'image
   * 
   * @param string chemin de l'image
   * @throws InvalidArgumentException
   */
  public function setFilePath(string $filePath): void
  {
    $filePath = trim($filePath);
    if (empty($filePath))
    {
      throw new InvalidArgumentException("Le chemin de l'image ne peut etre vide");
    }
    $this->filePath = $filePath;
  }

  /** Defini l'id_habitat de l'animal
   * 
   * @param int id_habitat de table habitat
   * @throws InvalidArgumentException
   */
  public function setHabitatId(int $habitat_id): void
  {
    if ($habitat_id <= 0) {
      throw new InvalidArgumentException("L'ID de l'habitat doit être un entier positif");
    }
    $this->habitat_id = $habitat_id;
  }
}