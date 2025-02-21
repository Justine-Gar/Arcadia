<?php
namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\AnimalRepository;
use App\Repositories\HabitatRepository;
use App\Repositories\FileARepository;
use lib\core\Logger;
use lib\core\Response;

class AnimalController extends Controllers
{
  private $animalRepository;
  private $habitatRepository;
  private $fileARepository;

  public function __construct()
  {
      parent::__construct();
      $this->animalRepository = new AnimalRepository();
      $this->habitatRepository = new HabitatRepository();
      $this->fileARepository = new FileARepository();
  }

  
  public function gestionAnimals() 
  {
      try {
          $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
          $byPage = 4;
          
          // Récupérer d'abord le total
          $totalAnimaux = $this->animalRepository->countAllAnimal();

          // Si aucun animal
          if ($totalAnimaux === 0) {
              $data = [
                  'title' => 'Gestion des Animaux',
                  'animals' => [],
                  'habitats' => [],
                  'pageActuelle' => 1,
                  'totalPages' => 1,
                  'totalAnimaux' => 0
              ];
              return $this->renderAdmin('gestionAnimaux', $data);
          }
  
          // Calculer le nombre total de pages
          $totalPages = ceil($totalAnimaux / $byPage);
          
          // Vérifier si la page demandée est valide
          if ($page > $totalPages) {
              $page = $totalPages;
          }
          if ($page < 1) {
              $page = 1;
          }
  
          $offset = ($page - 1) * $byPage;
  
          // Récupérer les animaux pour la page
          $animals = $this->animalRepository->getAnimalPagination($offset, $byPage);
          //Recupere tous les animaux
          $allAnimals = $this->animalRepository->getAllAnimal();
          // Récupérer les habitats
          $habitats = $this->habitatRepository->getAllHabitats();
  
          $data = [
              'title' => 'Gestion des Animaux',
              'animals' => $animals,
              'allAnimals' => $allAnimals,
              'habitats' => $habitats,
              'pageActuelle' => $page,
              'totalPages' => $totalPages,
              'totalAnimaux' => $totalAnimaux,
              'fileARepository' => $this->fileARepository
          ];
  
          return $this->renderAdmin('gestionAnimaux', $data);
  
      } catch (\Exception $e) {
          Logger::error("Erreur dans gestionAnimals: " . $e->getMessage());
          
          // Rediriger vers la première page en cas d'erreur
          header('Location: /admin/animaux?page=1');
          exit;
      }
  }

  public function ajouterAnimal() 
  {
    $message = '';
    $messageType = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      //Verification si User Connecter
      if (!isset($_SESSION['id_user'])) {
        $response = new Response();
        $response->setStatusCode(401);
        $response->json(['success' => false, 'message' => 'Utilisateur non connecté']);
        return $response;
      }

      $jsonData = json_decode(file_get_contents('php://input'), true);

      try {

        //Vérification des données requises
            if (!isset($jsonData['add_firstname']) || 
                !isset($jsonData['add_gender']) || 
                !isset($jsonData['add_species']) || 
                !isset($jsonData['add_diet']) || 
                !isset($jsonData['add_reproduction']) || 
                !isset($jsonData['add_id_habitat'])) {
                throw new \InvalidArgumentException("Tous les champs sont requis");
            }

            $newAnimal = $this->animalRepository->createAnimal(
                $jsonData['add_firstname'],
                $jsonData['add_gender'],
                $jsonData['add_species'],
                $jsonData['add_diet'],
                $jsonData['add_reproduction'],
                intval($jsonData['add_id_habitat'])
            );

        error_log("Animal créé avec succès. Id: " . $newAnimal->getIdAnimal()); 

        $response = new Response();
        $response->setHeader('Content-type', 'application/json');
        $response->json([
          'success' => true,
          'message' => 'Animal créé avec succès',
          'animal' => [
            'id' => $newAnimal->getIdAnimal(),
            'firstname' => $newAnimal->getFirstname()
          ]
        ]);

        return $response;

      } catch (\Exception $e) {
        error_log("Erreur lors de la création de l'animal : " . $e->getMessage());
    
        $response = new Response();
        $response->setStatusCode(500);
        $response->json([
          'success' => false,
          'message' => 'Erreur lors de la création de l\'animal: ' . $e->getMessage()
        ]);
        return $response;
      }
    }

    $animals = $this->animalRepository->getAllAnimal();
    $habitatRepository = new HabitatRepository();
    $habitats = $habitatRepository->getAllHabitats();

    $data = [
      'title' => 'Gestion des Animaux',
      'animals' => $animals,
      'habitats' => $habitats
    ];

    return $this->renderAdmin('gestionAnimaux', $data);
  }

  public function modifierAnimal() 
  {
    // verification connexion utilisateur
    if (!isset($_SESSION['id_user'])) {
      $response = new Response();
      $response->setStatusCode(401);
      $response->json([
          'success' => false, 
          'message' => 'Utilisateur non connecté'
      ]);
      return $response;
    }

    //verification method post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      try {
        //récupérer les donnée json
        $jsonData = json_decode(file_get_contents('php://input'), true);
        Logger::info("Données reçues pour les modification: " . print_r($jsonData, true));

        //vérifier les donnée acquise
        if(!isset($jsonData['id']) ||
            !isset($jsonData['modify_firstname']) ||
            !isset($jsonData['modify_gender']) ||
            !isset($jsonData['modify_species']) ||
            !isset($jsonData['modify_diet']) ||
            !isset($jsonData['modify_reproduction']) ||
            !isset($jsonData['modify_id_habitat']))
        {
          throw new \InvalidArgumentException("Données manquantes pour la modification de l'animal");
        }

        //récupere les donnée et mettre sous variable pour création de lobj
        $id_animal = (int)$jsonData['id'];
        $firstname = $jsonData['modify_firstname'];
        $gender = $jsonData['modify_gender'];
        $species = $jsonData['modify_species'];
        $diet = $jsonData['modify_diet'];
        $reproduction = $jsonData['modify_reproduction'];
        $id_habitat = (int)$jsonData['modify_id_habitat'];

        $updateAnimal = $this->animalRepository->updateAnimal($id_animal, $firstname, $gender, $species, $diet, $reproduction, $id_habitat);

        //vérification du résulat
        if($updateAnimal) {
          Logger::info("Animal modifié avec succes.");
          $response = new Response();
          $response->json([
            'success' => true,
            'message' => 'L\'animal à été modifié avec succès',
            'animal' => [
              'id' => $updateAnimal->getIdAnimal(),
              'firstname' => $updateAnimal->getFirstname(),
              'gender' => $updateAnimal->getGender(),
              'species' => $updateAnimal->getSpecies(),
              'diet' => $updateAnimal->getDiet(),
              'reproduction' => $updateAnimal->getReproduction(),
              'id_habitat' => $updateAnimal->getIdHabitat()
            ]
          ]);
          
          return $response;

        } else {
          Logger::error("Animal non trouvé");
          $response = new Response();
          $response->setStatusCode(404);
          $response->json([
              'success' => false,
              'message' => 'Animal non trouvé'
          ]);
        }


      } catch(\Exception $e) {
        // Erreur serveur
        Logger::error("Erreur lors de la modification de l'animal: " . $e->getMessage());
        $response = new Response();
        $response->setStatusCode(500);
        $response->json([
            'success' => false,
            'message' => 'Erreur lors de la modification de l\'animal: ' . $e->getMessage()
        ]);
        return $response;
      }

      $response = new Response();
      $response->setStatusCode(405);
      $response->json([
        'success' => false, 
        'message' => 'Méthode non autorisée'
      ]);
      return $response;
    }
  }

  public function supprimerAnimal()
  {
    // Vérification si l'utilisateur est connecté
    if (!isset($_SESSION['id_user'])) {
      $response = new Response();
      $response->setStatusCode(401);
      $response->json(['success' => false, 'message' => 'Utilisateur non connecté']);
      return $response;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $jsonData = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($jsonData['animalIds']) || !isset($jsonData['animalInfos'])) {
                throw new \InvalidArgumentException("Données manquantes pour la suppression");
            }

            $animalIds = $jsonData['animalIds'];
            
            // Suppression de chaque animal
            foreach ($animalIds as $id) {
                $result = $this->animalRepository->deleteAnimal((int)$id);
                if (!$result) {
                    throw new \Exception("Erreur lors de la suppression de l'animal avec l'ID: $id");
                }
            }

            $response = new Response();
            $response->json([
                'success' => true,
                'message' => 'Les animaux ont été supprimés avec succès'
            ]);
            return $response;

        } catch (\Exception $e) {
            Logger::error("Erreur lors de la suppression des animaux: " . $e->getMessage());
            $response = new Response();
            $response->setStatusCode(500);
            $response->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
            return $response;
        }
    }

    $response = new Response();
    $response->setStatusCode(405);
    $response->json([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
    return $response;
  }


}