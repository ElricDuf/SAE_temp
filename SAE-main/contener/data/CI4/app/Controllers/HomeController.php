<?php

namespace App\Controllers;

use App\Models\HomeModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $homeModel = new HomeModel();

        $beats = $homeModel->getLatestBeats(6);

        $data = [
            'title'      => 'Accueil',
            'categories' => $homeModel->getTopCategories(8),
            'beats'      => $beats,
            'listings'   => $beats,
            'stats'      => $homeModel->getStats(),
        ];

        return view('home', $data);
    }
}
