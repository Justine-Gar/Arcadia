<?php

namespace App\models;

use DateTime;
use InvalidArgumentException;

/**
 * Class qui représente les raports dans le systeme
 */
class Report {

  private const VALID_HEALTH_STATUSES = [
      'Bonne santé',
      'Convalescence',
      'Sous traitement Médical',
      'Sous observation',
      'Enrichissement environnemental',
      'En quarantaine',
      'En gestation',
      'En période de mue',
      'En réhabilitation',
      'Vieillesse'
  ];

  private ?int $id_report;
  private string $health_status;
  private DateTime $passage;
  private ?string $prescription;
  private ?string $quantity;
  private ?string $habitat_condition;
  private int $id_animal;
  private int $id_user;
  private ?Animal $animal = null;
  private ?User $user = null;
  
  /** Constructeur de la classe Report
   * 
   * @param ?int $id_report identifiant du rapport
   * @param string $health_status Le statut de santé
   * @param DateTime $passage La date et l'heure du passage
   * @param ?string $prescription La prescription 
   * @param ?string $quantity La quantité 
   * @param ?string $habitat_condition La condition de l'habitat
   * @param int $id_animal identifiant de l'animal
   * @param int $id_user identifiant de l'utilisateur
  */
  public function __construct(
    ?int $id_report,
    string $health_status,
    DateTime $passage,
    ?string $prescription,
    ?string $quantity,
    ?string $habitat_condition,
    int $id_animal,
    int $id_user) 
  {
    $this->setIdReport($id_report);
    $this->setHealthStatus($health_status);
    $this->setPassage($passage);
    $this->setPrescription($prescription);
    $this->setQuantity($quantity);
    $this->setHabitatCondition($habitat_condition);
    $this->setIdAnimal($id_animal);
    $this->setIdUser($id_user);
  }


  //GETTERS

  /** Obtient l'id du rapport
   * 
   * @return ?int
   */
  public function getIdReport(): ?int { return $this->id_report; }

  /** Obtient le status de santé
   * 
   * @return string
   */
  public function getHealthStatus(): string { return $this->health_status; }

  /** Obtient le passage du medecin
   * 
   * @return Datetime
   */
  public function getPassage(): DateTime { return $this->passage; }

  /** Obtient la prescription du medecin
   * 
   * @return ?string
   */
  public function getPrescription(): ?string { return $this->prescription; }

  /** Obtient la quantité de nourriture
   * 
   * @return ?string
   */
  public function getQuantity(): ?string { return $this->quantity; }

  /** Obtient la condition de l'habitat
   * 
   * @@return ?string
   */
  public function getHabitatCondition(): ?string { return $this->habitat_condition; }

  /** Obtient l'identifiant de l'animal
   * 
   * @return int
   */
  public function getIdAnimal(): int { return $this->id_animal; }

  /** Obtient l'identifiant de l'utilisateur 
   * 
   * @return int
  */
  public function getIdUser(): int { return $this->id_user; }


  public function getAnimal(): ?Animal {
    return $this->animal;
  }

  public function getUser(): ?User {
    return $this->user;
  }

  //SETTERS 

  /** Defini l'id du rapport
   * 
   * @param ?int
   * @throws InvalidArgumentException
   */
  public function setIdReport(?int $id_report): void
  {
      if ($id_report !== null && $id_report <= 0) {
          throw new InvalidArgumentException("L'id du rapport doit être un entier positif");
      }
      $this->id_report = $id_report;
  }

  /** Defini le status de santé de l'animal
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setHealthStatus(string $health_status): void
  {
      if (!in_array($health_status, self::VALID_HEALTH_STATUSES)) {
          throw new InvalidArgumentException("Statut de santé invalide");
      }
      $this->health_status = $health_status;
  }

  /** Defini le passage du medecin
   * 
   * @param Datetime
   * @throws InvalidArgumentException
   */
  public function setPassage(DateTime $passage): void
  {
      $this->passage = $passage;
  }

  /** Defini la prescription du medecin
   * 
   * @param ?string
   * @throws InvalidArgumentException
   */
  public function setPrescription(?string $prescription): void
  {
      $this->prescription = $prescription !== null ? trim($prescription) : null;
  }

  /** Defini la quantity de nourriture de l'animal
   * 
   * @param ?string
   * @throws InvalidArgumentException
   */
  public function setQuantity(?string $quantity): void
  {
      if ($quantity !== null && strlen($quantity) > 50) {
          throw new InvalidArgumentException("La quantité ne peut pas dépasser 50 caractères");
      }
      $this->quantity = $quantity !== null ? trim($quantity) : null;
  }

  /** Defni les condition d'habitat
   * 
   * @param ?string
   * @throws InvalidArgumentException
   */
  public function setHabitatCondition(?string $habitat_condition): void
  {
      $this->habitat_condition = $habitat_condition !== null ? trim($habitat_condition) : null;
  }

  /** Defini l'id de l'animal
   * 
   * @param int 
   * @throws InvalidArgumentException
   */
  public function setIdAnimal(int $id_animal): void
  {
      if ($id_animal <= 0) {
          throw new InvalidArgumentException("L'id de l'animal doit être un entier positif");
      }
      $this->id_animal = $id_animal;
  }

  /** Defini l'id de utilisateur
   * 
   * @param int
   * @throws InvalidArgumentException
   */
  public function setIdUser(int $id_user): void
  {
      if ($id_user <= 0) {
          throw new InvalidArgumentException("L'id de l'utilisateur doit être un entier positif");
      }
      $this->id_user = $id_user;
  }

  /** Defini l'animal
   * 
   * @param Animal
   */
  public function setAnimalReport(Animal $animal): void {
    $this->animal = $animal;
    $this->id_animal = $animal->getIdAnimal(); 
  }

  /** Defini l'user
   * 
   * @param User
   */
  public function setUserReport(User $user): void {
    $this->user = $user;
    $this->id_user = $user->getIdUser(); 
  }

}