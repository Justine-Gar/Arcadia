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
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $byPage = 10;
    $offset = ($page - 1) * $byPage;

    $animalId = !empty($_GET['animal_id']) ? intval($_GET['animal_id']) : null;
    $startDate = !empty($_GET['start']) ? new DateTime($_GET['start']) : null;

    $totalReport = $this->reportRepository->countFilteredReports($animalId, $startDate);
    $totalPages = ceil($totalReport / $byPage);

    // Utilisons toujours getFilteredReportsPaginated, même sans filtres
    $reports = $this->reportRepository->getFilteredReportsPaginated($animalId, $startDate, $offset, $byPage);
    
    $animals = $this->animalRepository->getAllAnimal();

    $data = [
        'title' => 'Rapports Filtrés',
        'reports' => $reports,
        'animals' => $animals,
        'healthStatus' => Health::cases(),
        'selectedAnimalId' => $animalId,
        'startDate' => $startDate ? $startDate->format('Y-m-d') : '',
        'pageActuelle' => $page,
        'totalPages' => $totalPages,
        'animalId' => $animalId,
        'start' => $startDate ? $startDate->format('Y-m-d') : ''
    ];

    return $this->renderAdmin('gestionJournal', $data);
  }
}

