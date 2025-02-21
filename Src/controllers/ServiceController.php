<?php
namespace App\Controllers;

use App\Controllers\Controllers;
use App\Models\Role;
use App\Repositories\FileSRepository;
use App\Repositories\ServiceRepository;
use lib\core\Logger;
use lib\core\Response;

class ServiceController extends Controllers
{   
    private $serviceRepository;
    private $fileSRepository;

    public function __construct()
    {
        parent::__construct();
        $this->serviceRepository = new ServiceRepository();
        $this->fileSRepository = new FileSRepository();
    }
    
    public function index()
    {
        $services = $this->serviceRepository->getAllService();
        $serviceImages = [];

        foreach ($services as $service) {
            $files = $this->fileSRepository->getByServiceId($service->getIdService());
            if (!empty($files)) {
                $serviceImages[$service->getIdService()] = $files[0]->getFilePath(); 
            }
        }
        
        $data = [
            'title' => 'Services',
            'services' => $services,
            'serviceImages' => $serviceImages
        ];
        
        return $this->render('services', $data);
    }

    public function gestionServices()
    {
        
        try {
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['id_user']) || !isset($_SESSION['user_role'])) {
                header('Location: /login');
                exit;
            }
    
            $services = $this->serviceRepository->getAllService();
            $fileSRepository = new FileSRepository();
            
            $data = [
                'title' => 'Gestion des Services',
                'services' => $services,
                'fileSRepository' => $fileSRepository
            ];
    
            // Redirection simple selon le rôle
            if ($_SESSION['user_role']->value === 'Admin') {
                return $this->renderAdmin('gestionServices', $data);
            } else if ($_SESSION['user_role']->value === 'Staff') {
                return $this->renderStaff('gestionServiceStaff', $data);
            }
    
            header('Location: /unauthorized');
            exit;
    
        } catch (\Exception $e) {
            Logger::error("Erreur dans gestionServices: " . $e->getMessage());
            header('Location: /error');
            exit;
        }
    
    }

    public function ajouterService()
    {
        $message = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Verification si User Connecter
            if ($_SESSION['user_role']->value !== 'Admin' && $_SESSION['user_role']->value !== 'Staff') {
                $response = new Response();
                $response->setStatusCode(403);
                $response->json(['success' => false, 'message' => 'Accès non autorisé']);
                return $response;
            }

            //verification de la method HTTP
            $jsonData = json_decode(file_get_contents('php://input'), true);
            //Logger::info("Données reçues pour la création du rapport : " . json_encode($jsonData));

            if (empty($jsonData['add_name']) || empty($jsonData['add_description'])) {
                throw new \InvalidArgumentException("Données manquantes pour la création du rapport");
            }

            try {
                //récupere donner en json
                $name = $jsonData['add_name'] ?? '';
                $description = $jsonData['add_description'] ?? ''; //date actuelle

                //creation du nouveau rapport
                $newService = $this->serviceRepository->createService(
                    $name,
                    $description
                );

                //Logger::info("Rapport créé avec succès. ID: " . $newReport->getIdReport());
                $message = 'Rapport crée avec succès';
                $messageType = 'success';

                $response = new Response();
                $response->json([
                    'success' => true,
                    'message' => 'Service créé avec succès',
                    'service' => $newService
                ]);
                return $response;

            } catch (\Exception $e) {

                //Logger::error("Erreur lors de la création du service:" . $e->getMessage());
                $message = 'Erreur lors de la création du service';
                $messageType = 'error';

                $response = new Response();
                $response->setStatusCode(500);
                $response->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du service: ' . $e->getMessage()
                ]);
                return $response;
            }
        }

        $services = $this->serviceRepository->getAllService();

        $data = [
            'title' => 'Gestion des Services',
            'services' => $services,
            'message' => $message,
            'messageType' => $messageType
        ];


        // Redirection simple selon le rôle
        if ($_SESSION['user_role']->value === 'Admin') {
            return $this->renderAdmin('gestionServices', $data);
        } else if ($_SESSION['user_role']->value === 'Staff') {
            return $this->renderStaff('gestionServiceStaff', $data);
        }

        header('Location: /unauthorized');
        exit;
    }

    public function modifierService() 
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
            // Vérification du rôle
            if ($_SESSION['user_role']->value !== 'Admin' && $_SESSION['user_role']->value !== 'Staff') {
                $response = new Response();
                $response->setStatusCode(403);
                $response->json(['success' => false, 'message' => 'Accès non autorisé']);
                return $response;
            }

            try {
                // récupération les données JSON
                $rawData = file_get_contents('php://input');
                $jsonData = json_decode($rawData, true);

                // vérif les données acquise
                if (!isset($jsonData['id']) || !isset($jsonData['modify_name']) || !isset($jsonData['modify_description'])) {
                    throw new \InvalidArgumentException("Données manquantes pour la modification du service");
                }

                // récupération des données
                $id_service = (int)$jsonData['id'];
                $name = $jsonData['modify_name'];
                $description = $jsonData['modify_description'];

                // modification dans la base de données
                $updatedService = $this->serviceRepository->updateService($id_service, $name, $description);

                // vérification du résultat
                if ($updatedService) {
                    $response = new Response();
                    $response->json([
                        'success' => true,
                        'message' => 'Le service a été modifié avec succès',
                        'service' => [
                            'id' => $updatedService->getIdService(),
                            'name' => $updatedService->getNameService(),
                            'description' => $updatedService->getDescriptionService()
                        ]
                    ]);
                    return $response;
                } else {
                    throw new \Exception("Aucun service trouvé avec cet ID");
                }
            } catch (\Exception $e) {
                Logger::error("Erreur lors de la modification du service: " . $e->getMessage());
                $response = new Response();
                $response->setStatusCode(500);
                $response->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification du service: ' . $e->getMessage()
                ]);
                return $response;
            }
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

    public function supprimerService()
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
                
                if (!isset($jsonData['serviceIds']) || !isset($jsonData['serviceInfos'])) {
                    throw new \InvalidArgumentException("Données manquantes pour la suppression");
                }

                $serviceIds = $jsonData['serviceIds'];
                
                foreach ($serviceIds as $id) {
                    $result = $this->serviceRepository->deleteService((int)$id);
                    if (!$result) {
                        throw new \Exception("Erreur lors de la suppression du service avec l'ID: $id");
                    }
                }

                $response = new Response();
                $response->json([
                    'success' => true,
                    'message' => 'Les services ont été supprimés avec succès'
                ]);
                return $response;

            } catch (\Exception $e) {
                Logger::error("Erreur lors de la suppression des services: " . $e->getMessage());
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
