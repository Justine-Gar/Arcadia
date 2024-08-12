<?php

namespace App\Controllers;

class HabitatController extends Controllers
{
    public function index()
    {
        $this->render('habitats', [
            'title' => 'Habitats',
        ]);
    }
}
