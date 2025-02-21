<?php
namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\AnimalRepository;
use App\Repositories\FileHRepository;
use App\Repositories\FileARepository;
use App\Repositories\HabitatRepository;
use App\Repositories\ReportRepository;
use lib\core\Logger;
use lib\core\Response;

class HabitatController extends Controllers
{
    private $habitatRepository;
    private $fileHRepository;
    private $fileARepository;
    private $animalRepository;
    private $reportRepository;

    public function __construct()
    {
        parent::__construct();
        $this->habitatRepository = new HabitatRepository();
        $this->fileHRepository = new FileHRepository();
        $this->fileARepository = new FileARepository();
        $this->animalRepository = new AnimalRepository();
        $this->reportRepository = new ReportRepository();
    }

    public function index()
    {
        $habitats =$this->habitatRepository->getAllHabitats();
        $allFileH = $this->fileHRepository->getAllFileHId();
        $animals = $this->animalRepository->getAllAnimal();
        $allFileA = $this->fileARepository->getAllFileAId();
        $reports = $this->reportRepository->getAllReports();

        $data = [
            'title' => 'Habitats',
            'habitats' => $habitats,
            'allFileH' => $allFileH,
            'animals' => $animals,
            'allFileA' => $allFileA,
            'reports' => $reports
        ];

        return $this->render('habitats', $data);
    }

    public function gestionHabitats()
    {
        $habitats = $this->habitatRepository->getAllHabitats();
        $fileHRepository = new FileHRepository();
        $data = [
            'title' => 'Gestion des Habitats',
            'habitats' => $habitats,
            'fileHrepository' => $fileHRepository
        ];
        return $this->renderAdmin('gestionHabitats', $data);
        
    }

    public function ajouterHabitat()
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

            //verification de la method HTTP
            $jsonData = json_decode(file_get_contents('php://input'), true);
            //Logger::info("Données reçues pour la création du rapport : " . json_encode($jsonData));

            if (empty($jsonData['add_name']) || empty($jsonData['add_description'])) {
                throw new \InvalidArgumentException("Données manquantes pour la création de l\'habitat");
            }

            try {
                //récupere donner en json
                $name = $jsonData['add_name'] ?? '';
                $description = $jsonData['add_description'] ?? '';

                //creation du nouveau habitat
                $newHabitat = $this->habitatRepository->createHabitat($name, $description);

                Logger::info("Rapport créé avec succès. ID: " . $newHabitat->getIdHabitat());
                $message = 'L\'habitat est crée avec succès';
                $messageType = 'success';

                $response = new Response();
                $response->json([
                    'success' => true,
                    'message' => 'L\'habitat est créé avec succès',
                    'habitat' => $newHabitat
                ]);
                return $response;

            } catch (\Exception $e) {

                Logger::error("Erreur lors de la création de l'habitat:" . $e->getMessage());
                $message = 'Erreur lors de la création de l\'habitat';
                $messageType = 'error';

                $response = new Response();
                $response->setStatusCode(500);
                $response->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'habitat: ' . $e->getMessage()
                ]);

                return $response;
            }
        }

        $habitats = $this->habitatRepository->getAllHabitats();

        $data = [
            'title' => 'Gestion des Habitats',
            'habitats' => $habitats,
            'message' => $message,
            'messageType' => $messageType
        ];

        return $this->renderAdmin('gestionHabitats', $data);
        
    }
    
    public function modifierHabitat()
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

        // verification méthode http
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                // reupération les données JSON
                $rawData = file_get_contents('php://input');
                $jsonData = json_decode($rawData, true);

                // Log des données reçues pour debug
                //Logger::info("Données reçues pour la modification : " . print_r($jsonData, true));

                // vérif les données acquise
                if (!isset($jsonData['id']) || !isset($jsonData['modify_name']) || !isset($jsonData['modify_description'])) {
                    throw new \InvalidArgumentException("Données manquantes pour la modification de l'habitat");
                }

                // récupération des données
                $id_habitat = (int)$jsonData['id'];
                $name = $jsonData['modify_name'];
                $description = $jsonData['modify_description'];

                // modification dans la base de données
                $updatedHabitat = $this->habitatRepository->updateHabitat($id_habitat, $name, $description);

                // vérification du résultat
                if ($updatedHabitat) {
                    //Ok
                    //Logger::info("Habitat modifié avec succès. ID: " . $updatedHabitat->getIdHabitat());
                    $response = new Response();
                    $response->json([
                        'success' => true,
                        'message' => 'L\'habitat a été modifié avec succès',
                        'habitat' => [
                            'id' => $updatedHabitat->getIdHabitat(),
                            'name' => $updatedHabitat->getNameHabitat(),
                            'description' => $updatedHabitat->getDescriptionHabitat()
                        ]
                    ]);
                    return $response;

                } else {
                    // Échec
                    throw new \Exception("Aucun habitat trouvé avec cet ID");
                }

            } catch (\Exception $e) {
                // Erreur serveur
                Logger::error("Erreur lors de la modification de l'habitat: " . $e->getMessage());
                $response = new Response();
                $response->setStatusCode(500);
                $response->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification de l\'habitat: ' . $e->getMessage()
                ]);
                return $response;
            }
            
           // Si la méthode n'est pas POST
            $response = new Response();
            $response->setStatusCode(405);
            $response->json([
                'success' => false,
                'message' => 'Méthode non autorisée'
            ]);
            return $response; 
        }

        
    }

    public function supprimerHabitat()
    {
        if (!isset($_SESSION['id_user'])) {
            $response = new Response();
            $response->setStatusCode(401);
            $response->json(['success' => false, 'message' => 'Utilisateur non connecté']);
            return $response;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $jsonData = json_decode(file_get_contents('php://input'), true);
                
                if (!isset($jsonData['habitatIds']) || !isset($jsonData['habitatInfos'])) {
                    throw new \InvalidArgumentException("Données manquantes pour la suppression");
                }

                $habitatIds = $jsonData['habitatIds'];
                
                foreach ($habitatIds as $id) {
                    $result = $this->habitatRepository->deleteHabitat((int)$id);
                    if (!$result) {
                        throw new \Exception("Erreur lors de la suppression de l'habitat avec l'ID: $id");
                    }
                }

                $response = new Response();
                $response->json([
                    'success' => true,
                    'message' => 'Les habitats ont été supprimés avec succès'
                ]);
                return $response;

            } catch (\Exception $e) {
                Logger::error("Erreur lors de la suppression des habitats: " . $e->getMessage());
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
