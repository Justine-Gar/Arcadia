<?php
namespace App\Controllers;

use App\Controllers\Controllers;

class HabitatController extends Controllers
{
    public function index()
    {
        $this->render('habitats', [
            'title' => 'Habitats',
        ]);
    }
}
