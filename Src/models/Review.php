<?php

namespace App\Models;

use InvalidArgumentException;

/**
 * Class qui représente les avis des utilisateurs
 */
class Review  {

  private const VALID_STATUS = [
    "En attente",
    "Approuvé",
    "Rejeté",
    "Supprimer"
  ];

  private ?int $id_review;
  private string $name_review;
  private string $description_review;
  private int $score;
  private string $status;

  /** Constructeur de la class Review
   * 
   * @param ?int $id_review identificant de l'avis
   * @param string $name_review le nom de la personne qui a poster l'avis
   * @param string $description_review la description de l'avis client
   * @param int $score la note donner sur 5
   * @param string $status etat de l'avis client 
   */
  public function __construct(?int $id_review, string $name_review, string $description_review, int $score, string $status)
  {
    $this->setIdReview($id_review);
    $this->setNameReview($name_review);
    $this->setDescriptionReview($description_review);
    $this->setScore($score);
    $this->setStatus($status);
  }

  //GETTERS

  /** Obtient l'id de review
   * 
   * @return ?int
   */
  public function getIdReview(): ?int { return $this->id_review;}

  /** Obtient le nom de l'utilisateur qui a poster l'avis
   * 
   * @return string
   */
  public function getNameReview(): string { return $this->name_review;}

  /** Obtient la description de l'avis
   * 
   * @return string
   */
  public function getDescriptionReview(): string { return $this->description_review;}

  /** Obtient le score de l'avis
   * 
   * @return int
   */
  public function getScore(): int { return $this->score;}

  /** Obtient le status de l'avis
   * 
   * @return string
   */
  public function getStatus(): string { return $this->status;}
  


  //SETTERS

  /** Defini l'id de l'avis
   * 
   * @param ?int 
   * @throws InvalidArgumentException
   */
  public function setIdReview(?int $id_review): void
  {
    if ($id_review !== null && $id_review <= 0)
    {
      throw new InvalidArgumentException("L'id doit etre un entier positif");
    }
    $this->id_review = $id_review;
  }

  /** Defini le nom de dépositaire de l'avis
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setNameReview(string $name_review): void
  {
    $name_review = trim($name_review);
    if (empty($name_review))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($name_review) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->name_review = $name_review;
  }

  /** Defini la description de l'avis
   * 
   * @param string
   * @throws
   */
  public function setDescriptionReview(string $description_review): void
  {
    $description_review = trim($description_review);
    if (empty($description_review))
    {
      throw new InvalidArgumentException("la description ne peut etre vide");
    }
    $this->description_review = $description_review;
  }

  /** Defini le score de l'avis 
   * 
   * @param int scoreentre 1et 5
   * @throws InvalidArgumentException
   */
  public function setScore(int $score): void
  {
    if ($score < 1 || $score > 5) 
    {
      throw new InvalidArgumentException("Le score doit etre entre 1 et 5");
    }
    $this->score = $score;
  }

  /** Defini le status de l'avis
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setStatus(string $status): void
  {
    if (!in_array($status, self::VALID_STATUS))
    {
      throw new InvalidArgumentException("Le status de l'avis est invalide");
    }
    $this->status = $status;
  }
}