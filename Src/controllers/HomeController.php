<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use App\Models\Review;
use App\Repositories\ReviewRepository;
use App\Repositories\FileARepository;
use App\Repositories\FileHRepository;
use lib\core\Response;
use lib\core\Logger;

class HomeController extends Controllers
{
    private $reviewRepository;
    private $fileARepository;
    private $fileHRepository;

    public function __construct()
    {
        parent::__construct();
        $this->reviewRepository = new ReviewRepository();
        $this->fileARepository = new FileARepository();
        $this->fileHRepository = new FileHRepository();
    }

    /** Affiche les avis sur la page
     * 
     */
    public function index()
    {
        

        //Affiche les avis approuvée limiter à 6
        $reviews = $this->reviewRepository->getApprovedReviews(6);

        //affiche les image animals
        $allFileA = $this->fileARepository->getAllFileAId();

        //aqffiche les image habitats
        $allFileH = $this->fileHRepository->getAllFileHId();

        $data = [
            'title' => 'Accueil',
            'reviews' => $reviews,
            'allFileA' => $allFileA,
            'allFileH' => $allFileH

        ];

        return $this->render('home', $data);
    }

    /** Ajouter une avis (depuis form puplic)
     * 
     */
    public function ajouterAvis()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = new Response();
            $response->setStatusCode(405);
            $response->json(['success' => false, 'message' => 'Méthode non autorisée']);
            return $response;
        }

        try {
            // Récupération des données JSON
            $jsonData = json_decode(file_get_contents('php://input'), true);
            
            if (!$jsonData) {
                throw new \InvalidArgumentException("Données JSON invalides");
            }

            // Création du nouvel avis
            $review = new Review(
                null,                           // id_review est null car auto-incrémenté
                $jsonData['name'],             // name de la BD
                $jsonData['description'],      // description de la BD
                (int)$jsonData['score'],      // score
                "En attente"                   // status par défaut
            );

            // Sauvegarde dans la base de données
            if ($this->reviewRepository->addReview($review)) {
                $response = new Response();
                $response->json([
                    'success' => true,
                    'message' => 'Merci ! Votre avis a été soumis et sera publié après modération.'
                ]);
                return $response;
            }

            throw new \Exception("Erreur lors de l'enregistrement de l'avis");

        } catch (\InvalidArgumentException $e) {
            Logger::error("Erreur de validation d'avis: " . $e->getMessage());
            $response = new Response();
            $response->setStatusCode(400);
            $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            return $response;
        } catch (\Exception $e) {
            Logger::error("Erreur lors de l'ajout d'un avis: " . $e->getMessage());
            $response = new Response();
            $response->setStatusCode(500);
            $response->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi de votre avis'
            ]);
            return $response;
        }
    }
}
