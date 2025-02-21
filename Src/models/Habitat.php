<?php

namespace App\Models;
use InvalidArgumentException;

/**
 * Class qui représente les habitat dans le systeme
 */
class Habitat {

  private ?int $id_habitat;
  private string $name;
  private string $description;

  /** Constructeur de la class Habitat
   * 
   * @param ?int l'identifiant du l'habitat
   * @param string le nom de l'habitat
   * @param string la description de l'habitat
   */
  public function __construct(?int $id_habitat, string $name, string $description)
  {
    $this->setIdHabitat($id_habitat);
    $this->setNameHabitat($name);
    $this->setDescriptionHabitat($description);
  }

  //GETTERS

  /** Obtient l'id de l'habitat
   * 
   * @return ?int l'id de l'habitat
   */
  public function getIdHabitat(): ?int { return $this->id_habitat;}

  /** Obtient le nom de l'habitat
   * 
   * @return string le name de l'habitat
   */
  public function getNameHabitat(): string { return $this->name;}

  /** Obtien la description de l'habitat 
   * 
   * @return string la decription de l'habitat
   */
  public function getDescriptionHabitat(): string {return $this->description;}


  //SETTERS

  /** Definit l'id de l'habitat
   * 
   * @param ?int le nouveau identifiant
   * @throws InvalidArgumentException
   */
  public function setIdHabitat(?int $id_habitat): void
  {
    if ($id_habitat !== null && $id_habitat <= 0) {
      throw new InvalidArgumentException("L'ID de l'habitat doit être un entier positif");
    }
    $this->id_habitat = $id_habitat;
  }

  /** Defini le nom de l'habitat
   * 
   * @param string le nouveau nom de l'habitat
   * @throws InvalidArgumentException
   */
  public function setNameHabitat(string $name): void
  {
    $name = trim($name);
    if (empty($name))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($name) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->name = $name;
  }

  /** Defini la description de l'habitat
   * 
   * @param string la description de l'habitat
   * @throws InvalidArgumentException
   */
  public function setDescriptionHabitat(string $description): void
  {
    $description = trim($description);
    if (empty($description))
    {
      throw new InvalidArgumentException("La description ne peut etre vide");
    }
    if (strlen($description) > 10000) {
      throw new InvalidArgumentException("La description ne peut dépasser 10000 caractères");
    }
    $this->description = $description;
  }
}