<?php

namespace src\models;
use InvalidArgumentException;

/**
 * Class représentant les services dans le systeme
 */
class Service {

  private ?int $id_service;
  private string $name_service;
  private string $description_service;

  /** Constructeur de la class Service
   * 
   * @param ?int $id_service l'identifiant du service
   * @param string $name_service le nom du serice
   * @param string $description_service la description du service
   */
  public function __construct(?int $id_service, string $name_service, string $description_service) 
  {
    $this->setIdService($id_service);
    $this->setNameService($name_service);
    $this->setDescriptionService($description_service);
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
  public function getNameService(): string { return $this->name_service;}

  /** Obtient la description du service
   * 
   * @return string la dscription du service
   */
  public function getDescrptionService(): string { return $this->description_service;}


  //SETTERS

  /** Defint l'id du service
   * 
   * @param ?int le nouveau identifiant
   * @throws InvalidArgumentException
   */
  public function setIdService(?int $id_service): void 
  {
    if ($id_service !== null && $id_service <= 0)
    {
      throw new InvalidArgumentException("L'id_service doit etre un entier positif");
    }
    $this->id_service = $id_service;
  }

  /** Defini le nom du service
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setNameService(string $name_service): void
  {
    $name_service = trim($name_service);
    if (empty($name_service))
    {
      throw new InvalidArgumentException("le nom ne peut etre vide");
    }
    if (strlen($name_service) > 50)
    {
      throw new InvalidArgumentException("Le nom ne peut dépasser 50 caractères");
    }
    $this->name_service = $name_service;
  }

  /** Defnit le description du service
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setDescriptionService(string $description_service): void
  {
    $description_service = trim($description_service);
    if (empty($description_service))
    {
      throw new InvalidArgumentException("la description ne peut etre vide");
    }
    $this->description_service = $description_service;
  }
} 