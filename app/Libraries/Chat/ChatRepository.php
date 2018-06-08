<?php

namespace App\Libraries\Chat;

class ChatRepository {
    protected $viewData = [];

    public function index()
    {
        $this->viewData['allowedFonts'] = $this->getAllowedFonts();

        return view('home.index', $this->viewData);
    }

    private function getAllowedFonts()
    {
        return [
            'Arial',
            'Comic Sans MS',
        ];
    }
}