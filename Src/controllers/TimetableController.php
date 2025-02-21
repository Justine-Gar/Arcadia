<?php
namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\TimetableRepository;
use lib\core\Response;
use lib\core\Logger;

class TimetableController extends Controllers
{
    private $timetableRepository;

    public function __construct()
    {
        $this->timetableRepository = new TimetableRepository();
    }

    // Méthodes pour la gestion des horaires dans l'administration
    public function gestionHoraires()
    {
        $timetables = $this->timetableRepository->getAllTimetables();
        $data = [
            'title' => 'Gestion des Horaires',
            'timetables' => $timetables
        ];
        return $this->renderAdmin('gestionHoraires', $data);
    }

    public function modifierHoraires()
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

                if (!isset($jsonData['id']) || 
                    !isset($jsonData['modify_days']) || 
                    !isset($jsonData['modify_open_hours']) || 
                    !isset($jsonData['modify_close_hours'])) {
                    throw new \InvalidArgumentException("Données manquantes pour la modification de l'horaire");
                }

                $id_timetable = (int)$jsonData['id'];
                $days = $jsonData['modify_days'];
                $open_hours = $jsonData['modify_open_hours'];
                $close_hours = $jsonData['modify_close_hours'];

                $updatedTimetable = $this->timetableRepository->updateTimetable($id_timetable, $days, $open_hours, $close_hours);

                if ($updatedTimetable) {
                    $response = new Response();
                    $response->json([
                        'success' => true,
                        'message' => 'L\'horaire a été modifié avec succès',
                        'timetable' => [
                            'id' => $updatedTimetable->getIdTimetable(),
                            'days' => $updatedTimetable->getDays(),
                            'open_hours' => $updatedTimetable->getOpenHours(),
                            'close_hours' => $updatedTimetable->getCloseHours()
                        ]
                    ]);
                    return $response;
                } else {
                    throw new \Exception("Aucun horaire trouvé avec cet ID");
                }

            } catch (\Exception $e) {
                Logger::error("Erreur lors de la modification de l'horaire: " . $e->getMessage());
                $response = new Response();
                $response->setStatusCode(500);
                $response->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification de l\'horaire: ' . $e->getMessage()
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