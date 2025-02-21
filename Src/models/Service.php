<?php

namespace App\Models;
use InvalidArgumentException;

/**
 * Class représentant les services dans le systeme
 */
class Service {

  private ?int $id_service;
  private string $name;
  private string $description;

  /** Constructeur de la class Service
   * 
   * @param ?int $id_service l'identifiant du service
   * @param string $name_service le nom du serice
   * @param string $description_service la description du service
   */
  public function __construct(?int $id_service, string $name, string $description) 
  {
    $this->setIdService($id_service);
    $this->setNameService($name);
    $this->setDescriptionService($description);
  }

  //GETTERS

  /** Obtient l'id du service
   * 
   * @return ?int $id_service l'id du service
   */
  public function getIdService(): ?int { return $this->id_service;}

  /** Obtient le nom du service
   * 
   * @return string le nom du service
   */
  public function getNameService(): string { return $this->name;}

  /** Obtient la description du service
   * 
   * @return string la dscription du service
   */
  public function getDescriptionService(): string { return $this->description;}


  //SETTERS

  /** Defint l'id du service
   * 
   * @param ?int le nouveau identifiant
   * @throws InvalidArgumentException
   */
  public function setIdService(?int $id_service): void 
  {
    $this->id_service = $id_service;
  }

  /** Defini le nom du service
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setNameService(string $name): void
  {
    $name = trim($name);
    if (empty($name))
    {
      throw new InvalidArgumentException("le nom ne peut etre vide");
    }
    if (strlen($name) > 50)
    {
      throw new InvalidArgumentException("Le nom ne peut dépasser 50 caractères");
    }
    $this->name = $name;
  }

  /** Defnit le description du service
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setDescriptionService(string $description): void
  {
    $description = trim($description);
    if (empty($description))
    {
      throw new InvalidArgumentException("la description ne peut etre vide");
    }
    $this->description = $description;
  }
} 