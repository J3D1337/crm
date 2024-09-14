<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Welcome to the Home Page',
            'message' => 'This is the home page of your MVC application!'
        ];

        // Render the home view and pass data to it
        $this->render('home', $data);
    }
}
