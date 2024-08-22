<?php
namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\ServiceRepository;
use lib\core\Response;

class ServiceController extends Controllers
{   
    
    private $serviceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->serviceRepository = new ServiceRepository();
    }
    
    public function index()
    {
        $services = $this->serviceRepository->getAllService();

        /*Débogage : Afficher le contenu de $services
        echo "<pre>";
        var_dump($services);
        echo "</pre>";*/

        $data = [
            'title' => 'Services',
            'services' => $services,
        ];

        /*Débogage : Afficher le contenu de $data
        echo "<pre>";
        var_dump($data);
        echo "</pre>";*/
        
        return $this->render('services', $data);
    }
}
