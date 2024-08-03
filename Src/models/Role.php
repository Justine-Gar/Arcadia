<?php

namespace src\models;
use InvalidArgumentException;

/**
 * Classe représentant un role dans le systeme
 */
class Role {
  
  private ?int $id;
  private string $name;

  /** Constructeur de la classe Role
   * 
   * @param ?int $id identifiant du role
   * @param string name le non du role
   */
  public function __construct(?int $id, string $name)
  {
    $this->id = $id;
    $this->name = $name;
  }

  //GETTERS

  /** Obtient l'id du role
   * 
   * @return ?int l'identifiant du role
   */
  public function getId(): ?int { return $this->id;}

  /** Obtient le nom du role
   * 
   * @return string le nom du role
   */
  public function getName(): string {return $this->name;}


  //SETTERS
  
  /** Definit l'id du role
   *  
   * @param ?int $id le nouveau identifiant
   * @return ?int $id iddentifiant défini
   * @throws InvalidArgumentException si id est inférieur ou égale à 0;
   */
  public function setId(?int $id): ?int
  {
      if ($id !== null && $id <= 0) {
          throw new InvalidArgumentException("L'ID doit être un entier positif");
      }
      return $this->id = $id;
  }

  /** Definit le noma du role
   * 
   * @param string $name le nouveau nom
   * @return string $name le nom defini
   * @throws InvalidArgumentException si le nom est vide;
   */
  public function setName(string $name): string
  {
    $name = trim($name);
        if (empty($name)) {
            throw new InvalidArgumentException("Le nom ne peut pas être vide");
        }
        return $this->name = $name;
  }
}