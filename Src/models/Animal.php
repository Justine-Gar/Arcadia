<?php

namespace src\models;

use InvalidArgumentException;

/**
 * Class qui représente les habitat dans le systeme
 */
class Animal {

  private ?int $id_animal;
  private string $firstname;
  private string $gender;
  private string $species;
  private string $diet;
  private string $reproduction;
  private ?Habitat $habitat = null;

  /** Constructeur de la class Animal
   * 
   * @param ?int $id_animal l'identifiant de l'animal;
   * @param string $firstanme le nom de l'animal
   * @param string $gender le genre de l'animal
   * @param string $species la famille de la l'animal
   * @param string $diet le regima alimentaire de l'animal
   * @param string $reproduction la reproduction de l'animal
   * @param ?int $id_habitat l'identifiant de l'habitat
   */
  public function __construct(
    ?int $id_animal, string $firstname, 
    string $gender, string $species, 
    string $diet, string $reproduction,
    ?Habitat $habitat = null)
  {
    $this->setIdAnimal($id_animal);
    $this->setFirstname($firstname);
    $this->setGender($gender);
    $this->setSpecies($species);
    $this->setDiet($diet);
    $this->setReproduction($reproduction);
    $this->setIdHabitat($habitat);
  }

  //GETTERS

  /** Obetenir l'Id de l'animal
   * 
   * @return ?int 
   */
  public function getIdAnimal(): int { return $this->id_animal;}

  /** Obtenir le nom de l'habitat
   * 
   * @return string
   */
  public function getFirstname(): string { return $this->firstname;}

  /** Obtenir le genre de l'animal
   * 
   * @return string
   */
  public function getGender(): string { return $this->gender;}

  /** Obtenir la famille de l'animal
   * 
   * @return string
   */
  public function getSpecies(): string { return $this->species;}

  /** Obtenir le regime alimentaire de l'animal
   * 
   * @return string
   */
  public function getDiet(): string { return $this->diet;}

  /** Obtenir la reproduction de l'animal
   * 
   * @return string
   */
  public function getReproduction(): string { return $this->reproduction;}

  /** Obtenir l'habitat de l'animal
   * 
   * @return int
   */
  public function getIdHabitat(): ?Habitat { return $this->habitat;}


  //SETTERS

  /** Defini l'id de l'animal
   * 
   * @param ?int
   * @throws InvalidArgumentException
   */
  public function setIdAnimal(?int $id_animal): void
  {
    if ($id_animal !== null && $id_animal <= 0)
    {
      throw new InvalidArgumentException("L'id doit etre un entier positif");
    }
    $this->id_animal = $id_animal;
  }


  /** Defini le nom de l'animal
   * 
   * @param string le nom de l'animal
   * @throws InvalidArgumentException
   */
  public function setFirstname(string $firstname): void 
  {
    $firstname = trim($firstname);
    if (empty($firstname))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($firstname) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->firstname = $firstname;
  }


  /** Defini le genre de l'animal
   * 
   * @param string le genre de l'animal
   * @throws InvalidArgumentException
   */
  public function setGender(string $gender): void
  {
    $gender = trim($gender);
    if (empty($gender))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($gender) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->gender = $gender;
  }

  /** Defini la famille de l'animal
   * 
   * @param string la famille de l'animal
   * @throws InvalidArgumentException
   */
  public function setSpecies(string $species): void
  {
    $species = trim($species);
    if (empty($species))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    if (strlen($species) > 50)
    {
      throw new InvalidArgumentException("le nom ne peut dépasser 50 caractère");
    }
    $this->species = $species;
  }

  /** Defini le regime alimentaire de l'animal
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setDiet(string $diet): void
  {
    $diet = trim($diet);
    if (empty($diet))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }

    $this->diet = $diet;
  }

  /** Defini la reproduction de l'animal
   * 
   * @param string
   * @throws InvalidArgumentException
   */
  public function setReproduction(string $reproduction): void
  {
    $reproduction = trim($reproduction);
    if (empty($reproduction))
    {
      throw new InvalidArgumentException("Le nom ne peut etre vide");
    }
    $this->reproduction = $reproduction;
  }

  /** Defini l'id_habitat de l'animal
   * 
   * @param int id_habitat de table habitat
   * @throws InvalidArgumentException
   */
  public function setIdHabitat(Habitat $habitat): void
  {
    $this->habitat = $habitat;
  }
}