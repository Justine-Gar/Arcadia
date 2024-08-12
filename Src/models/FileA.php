<?php
namespace src\models;

use InvalidArgumentException;

/**
 * Class qui représente les images dans la systeme lier à Animal
 */
class FileA {

  private ?int $id_fileH;
  private string $file_name;
  private string $file_path;
  private ?Animal $animal = null;

  /** Constructeur de la classe FileA
   * 
   * @param ?int $id_fileA est l'identifiant du file
   * @param string $file_name le nom du file
   * @param string $file_path le chemin de l'image
   * @param ?int $id_animal l'identifiant de l'animal
   */
  public function __construct(?int $id_fileH, string $file_name, string $file_path, ?Animal $animal = null)
  {
    $this->setIdFileA($id_fileH);
    $this->setFileName($file_name);
    $this->setFilePath($file_path);
    $this->setAnimal($animal);
  }

  //GETTERS

  /** Obtient l'id du file
   * 
   * @return ?int
   */
  public function getIdFileA(): ?int { return $this->id_fileH;}

  /** Obtient le nom du file
   * 
   * @return string
   */
  public function getFileName(): string { return $this->file_name;}

  /** Obtient le chemin de l'image
   * 
   * @return string
   */
  public function getFilePath(): string { return $this->file_path;}

  /** Obtient l'identifiant habitat du file
   * 
   * @return int
   */
  public function getAnimal(): ?Animal { return $this->animal;}


  //SETTERS

  /** Definit l'id de l'habitat
   * 
   * @param ?int le nouveau identifiant
   * @throws InvalidArgumentException
   */
  public function setIdFileA(?int $id_fileH): void
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
  public function setFileName(string $file_name): void
  {
    $file_name = trim($file_name);
    if (empty($file_name))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($file_name) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->file_name = $file_name;
  }

  /** Defini le chemin de l'image
   * 
   * @param string chemin de l'image
   * @throws InvalidArgumentException
   */
  public function setFilePath(string $file_path): void
  {
    $file_path = trim($file_path);
    if (empty($file_path))
    {
      throw new InvalidArgumentException("Le chemin de l'image ne peut etre vide");
    }
    $this->file_path = $file_path;
  }

  /** Defini l'id_habitat de l'animal
   * 
   * @param int id_habitat de table habitat
   * @throws InvalidArgumentException
   */
  public function setAnimal(Animal $animal): void
  {
    $this->animal = $animal;
  }
}