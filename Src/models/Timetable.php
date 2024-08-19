<?php

namespace App\Models;

use InvalidArgumentException;

/**
 * Class qui représente les horaires dans le systeme
 */
class Timetable {

  private ?int $id_timetable;
  private string $days;
  private string $open_hours;
  private string $close_hours;

  /** Constructeur de la class timetable
   * 
   * @param ?int $id_timetable l'identifiant des horaires
   * @param string $days le jours de la semaine
   * @param string $open_hours horaire d'ouverture
   * @param string $close_hours horaire de fermeture
   */
  public function __construct(?int $id_timetable, string $days, string $open_hours, string $close_hours)
  {
    $this->setIdTimetable($id_timetable);
    $this->setDays($days);
    $this->setOpenHours($open_hours);
    $this->setCloseHours($close_hours);
  }

  //GETTERS

  /** Obtient l'id des horaires
   * 
   * @return ?int 
   */
  public function getIdTimetable(): ?int { return $this->id_timetable;}

  /** Obtient le jour de la semaine
   * 
   * @return string
   */
  public function getDays(): string { return $this->days;}

  /** Obtient l'heure d'ouverture
   * 
   * @return string
   */
  public function getOpenHours(): string { return $this->open_hours;}

  /** Obtient l'heure fermeture 
   * 
   * @return string
   */
  public function getCloseHours(): string { return $this->close_hours;}


  //SETTERS

  /** Defini l'id des horaires
   * 
   * @param ?int
   * @throws InvalidArgumentException
   */
  public function setIdTimetable(?int $id_timetable): void
  {
    if ($id_timetable !== null && $id_timetable <= 0)
    {
      throw new InvalidArgumentException("L'id doit etre un entier positif");
    }
    $this->id_timetable = $id_timetable;
  }

  /** Defini le jour de la samaine 
   *
   * @param string
   * @throws InvalidArgumentException 
   */
  public function setDays(string $days): void
  {
    $days = trim($days);
    if (empty($days))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($days) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->days = $days;
  }

  /** Defini l'heure d'ouverture
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setOpenHours(string $open_hours): void
  {
    $open_hours = trim($open_hours);
    if (empty($open_hours))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($open_hours) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->open_hours = $open_hours;
  }

  /** Defini l'heure de fermeture
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setCloseHours(string $close_hours): void
  {
    $close_hours = trim($close_hours);
    if (empty($close_hours))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($close_hours) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->close_hours = $close_hours;
  }
}