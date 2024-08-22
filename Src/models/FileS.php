<?php
namespace App\Models;

use InvalidArgumentException;

/**
 * Class qui représente les images dans la systeme lier à Service
 */
class FileS {

  private ?int $id_fileS;
  private string $fileName;
  private string $filePath;
  private int $service_id;

  /** Constructeur de la classe File
   * 
   * @param ?int $id_fileh est l'identifiant du file
   * @param string $fileName le nom du file
   * @param string $filePath le chemin de l'image
   * @param ?int $id_animal l'identifiant du service
   */
  public function __construct(?int $id_fileS, string $fileName, string $filePath, int $service_id)
  {
    $this->setIdFileS($id_fileS);
    $this->setFileSName($fileName);
    $this->setFileSPath($filePath);
    $this->setServiceId($service_id);
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
  public function getFileSName(): string { return $this->fileName;}

  /** Obtient le chemin de l'image
   * 
   * @return string
   */
  public function getFileSPath(): string { return $this->filePath;}

  /** Obtient l'identifiant habitat du file
   * 
   * @return int
   */
  public function getServiceId(): int { return $this->service_id;}


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
  public function setFileSName(string $fileName): void
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
  public function setFileSPath(string $filePath): void
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
  public function setServiceId(int $service_id): void
  {
    $this->service_id = $service_id;
  }
}