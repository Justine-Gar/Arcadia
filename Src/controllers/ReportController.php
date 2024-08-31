<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\AnimalRepository;
use App\Repositories\ReportRepository;
use App\Models\Health;
use DateTime;

class ReportController extends Controllers
{
  private $reportRepository;
  private $animalRepository;

  public function __construct()
  {
    parent::__construct();
    $this->reportRepository = new ReportRepository;
    $this->animalRepository = new AnimalRepository;
  }
  /** lire tout les rpport journalier
   * 
   */
  public function gestionJournal()
  {
    $reports = $this->reportRepository->getAllReports();
    $animals = $this->animalRepository->getAllAnimal();

    $data = [
      'title' => 'Gestion des Rapports',
      'reports' => $reports,
      'animals' => $animals,
      'healthStatus' => Health::cases()
    ];

    return $this->renderAdmin('gestionJournal', $data);
  }


  /** method pour filtrer un rapport
   * 
   */
  public function filtrer() 
  {
    $animalId = isset($_GET['animal_id']) ? intval($_GET['animal_id']) : null;
    $startDate = !empty($_GET['start_date']) ? new DateTime($_GET['start_date']) : null;

    $reports = $this->reportRepository->getFiltrer($animalId, $startDate);
    $animals = $this->animalRepository->getAllAnimal();


    $data = [
      'title' => 'Rapport Filtrés',
      'reports' => $reports,
      'animals' => $animals,
      'healthStatus' => Health::cases(),
      'selectedAnimalId' => $animalId,
      'startDate' => $startDate ? $startDate->format('Y-m-d') : '',
    ];

    return $this->renderAdmin('gestionJournal', $data);
  }
}

