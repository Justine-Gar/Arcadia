<?php

namespace App\Models;
use InvalidArgumentException;

/**
 * Class qui représente les habitat dans le systeme
 */
class Habitat {

  private ?int $id_habitat;
  private string $name_habitat;
  private string $description_habitat;

  /** Constructeur de la class Habitat
   * 
   * @param ?int l'identifiant du l'habitat
   * @param string le nom de l'habitat
   * @param string la description de l'habitat
   */
  public function __construct(?int $id_habitat, string $name_habitat, string $description_habitat)
  {
    $this->setIdHabitat($id_habitat);
    $this->setNameHabitat($name_habitat);
    $this->setDescriptionHabitat($description_habitat);
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
  public function getNameHabitat(): string { return $this->name_habitat;}

  /** Obtien la description de l'habitat 
   * 
   * @return string la decription de l'habitat
   */
  public function getDescriptionHabitat(): string {return $this->description_habitat;}


  //SETTERS

  /** Definit l'id de l'habitat
   * 
   * @param ?int le nouveau identifiant
   * @throws InvalidArgumentException
   */
  public function setIdHabitat(?int $id_habitat): void
  {
    if ($id_habitat !== null && $id_habitat <= 0)
    {
      throw new InvalidArgumentException("L'id doit un etre un entier positif");
    }
    $this->id_habitat = $id_habitat;
  }

  /** Defini le nom de l'habitat
   * 
   * @param string le nouveau nom de l'habitat
   * @throws InvalidArgumentException
   */
  public function setNameHabitat(string $name_habitat): void
  {
    $name_habitat = trim($name_habitat);
    if (empty($name_habitat))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($name_habitat) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->name_habitat = $name_habitat;
  }

  /** Defini la description de l'habitat
   * 
   * @param string la description de l'habitat
   * @throws InvalidArgumentException
   */
  public function setDescriptionHabitat(string $description_habitat): void
  {
    $description_habitat = trim($description_habitat);
    if (empty($description_habitat))
    {
      throw new InvalidArgumentException("La description ne peut etre vide");
    }
    $this->description_habitat = $description_habitat;
  }
}