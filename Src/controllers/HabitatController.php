<?php
namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\HabitatRepository;

class HabitatController extends Controllers
{
    private $habitatRepository;

    public function __construct()
    {
        parent::__construct();
        $this->habitatRepository = new HabitatRepository();
    }

    public function index()
    {
        $data = [
            'title' => 'Habitats',
        ];

        return $this->render('habitats', $data);
    }

    public function gestionHabitats()
    {
        $habitats = $this->habitatRepository->getAllHabitat();
        $data = [
            'title' => 'Gestion des Habitats',
            'habitats' => $habitats
        ];
        return $this->renderAdmin('gestionHabitats', $data);
        
    }
    
}
