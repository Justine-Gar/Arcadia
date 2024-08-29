<?php
namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\AnimalRepository;


class AnimalController extends Controllers
{
  private $animalRepository;

  public function __construct()
  {
      parent::__construct();
      $this->animalRepository = new AnimalRepository();
  }

  public function gestionAnimals() 
  {
    $animals = $this->animalRepository->getAllAnimal();

    //var_dump($animals);

    $data = [
      'title' => 'Gestion des Animaux',
      'animals' => $animals
    ];
        /*echo "<pre>";
        var_dump($animals);
        echo "</pre>";*/

    return $this->renderAdmin('gestionAnimaux', $data);
  }
}