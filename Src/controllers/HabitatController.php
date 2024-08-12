<?php

namespace App\Controllers;

use App\Controllers\Controllers;

class HabitatController extends Controllers
{
    public function index()
    {
        $data = [
            'title' => 'Habitats',
        ];

        return $this->render('habitats', $data);
    }
}
