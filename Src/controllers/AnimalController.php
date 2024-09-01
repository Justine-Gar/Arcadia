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

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $byPage = 4;
    $offset = ($page - 1) * $byPage;

    $totalAnimaux = $this->animalRepository->countAllAnimal();
    $totalPages = ceil($totalAnimaux / $byPage);

    $animals = $this->animalRepository->getAnimalPagination($offset, $byPage);

    //var_dump($animals);

    $data = [
      'title' => 'Gestion des Animaux',
      'animals' => $animals,
      'pageActuelle' => $page,
      'totalPages' => $totalPages
    ];
        /*echo "<pre>";
        var_dump($animals);
        echo "</pre>";*/

    return $this->renderAdmin('gestionAnimaux', $data);
  }
}