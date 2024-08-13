<?php
namespace App\models;

use InvalidArgumentException;

/**
 * Class qui représente les images dans la systeme lier à Service
 */
class FileS {

  private ?int $id_fileS;
  private string $file_name;
  private string $file_path;
  private int $serviceId;

  /** Constructeur de la classe File
   * 
   * @param ?int $id_fileh est l'identifiant du file
   * @param string $file_name le nom du file
   * @param string $file_path le chemin de l'image
   * @param ?int $id_animal l'identifiant de l'animal
   */
  public function __construct(?int $id_fileS, string $file_name, string $file_path, int $serviceId)
  {
    $this->setIdFileS($id_fileS);
    $this->setFileName($file_name);
    $this->setFilePath($file_path);
    $this->setServiceId($serviceId);
  }

  //GETTERS

  /** Obtient l'id du file
   * 
   * @return ?int
   */
  public function getIdFileS(): ?int { return $this->id_fileS;}

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
  public function getServiceId(): int { return $this->serviceId;}


  //SETTERS

  /** Definit l'id de l'habitat
   * 
   * @param ?int le nouveau identifiant
   * @throws InvalidArgumentException
   */
  public function setIdFileS(?int $id_fileS): void
  {
    if ($id_fileS !== null && $id_fileS<= 0)
    {
      throw new InvalidArgumentException("L'id doit un etre un entier positif");
    }
    $this->id_fileS = $id_fileS;
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
  public function setServiceId(int $serviceId): void
  {
    $this->serviceId = $serviceId;
  }
}