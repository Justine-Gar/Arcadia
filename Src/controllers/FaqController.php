<?php

namespace App\Controllers;

use App\Controllers\Controllers;

class FaqController extends Controllers
{
    public function index()
    {
        $data = [
            'title' => 'Faq',
        ];

        return $this->render('faq', $data);
    }
}
