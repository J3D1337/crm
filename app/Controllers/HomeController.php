<?php 

namespace App\Controllers;

class HomeController extends Controller{

    public function index() {
        $model = $this->model('Model');
        $message = $model->getData();
        $this->view('home', ['message' => $message]);
    }
}