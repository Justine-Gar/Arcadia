<?php

namespace src\models;
use InvalidArgumentException;

/**
 * Classe représentant un role dans le systeme
 */
class Role {
  
  private ?int $id_role;
  private string $name_role;

  /** Constructeur de la classe Role
   * 
   * @param ?int $id_role identifiant du role
   * @param string name_role le non du role
   */
  public function __construct(?int $id_role, string $name_role)
  {
    $this->id_role = $id_role;
    $this->name_role = $name_role;
  }

  //GETTERS

  /** Obtient l'id_role du role
   * 
   * @return ?int l'identifiant du role
   */
  public function getIdRole(): ?int { return $this->id_role;}

  /** Obtient le nom du role
   * 
   * @return string le nom du role
   */
  public function getNameRole(): string {return $this->name_role;}


  //SETTERS
  
  /** Definit l'id_role du role
   *  
   * @param ?int $id_role le nouveau identifiant
   * @return ?int $id_role iddentifiant défini
   * @throws InvalidArgumentException si id_role est inférieur ou égale à 0;
   */
  public function setIdRole(?int $id_role): ?int
  {
      if ($id_role !== null && $id_role <= 0) {
          throw new InvalidArgumentException("L'id_role doit être un entier positif");
      }
      return $this->id_role = $id_role;
  }

  /** Definit le noma du role
   * 
   * @param string $name_role le nouveau nom
   * @return string $name_role le nom defini
   * @throws InvalidArgumentException si le nom est vide;
   */
  public function setNameRole(string $name_role): string
  {
    $name_role = trim($name_role);
        if (empty($name_role)) {
            throw new InvalidArgumentException("Le nom ne peut pas être vide");
        }
        return $this->name_role = $name_role;
  }
}