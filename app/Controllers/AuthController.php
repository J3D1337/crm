<?php
namespace App\Controllers;

use App\Models\User;
use Core\JWTHandler;

class AuthController extends Controller
{
    private $jwt;
    private $userModel;

    public function __construct($db)
    {
        $this->jwt = new JWTHandler();
        $this->userModel = new User($db);
    }

    // Handle user registration (GET and POST)
    public function register()
    {
        if ($this->isGet()) {
            $this->render('register');
            return;
        }

        if ($this->isPost()) {
            $name = $this->getInput('name');
            $email = $this->getInput('email');
            $password = $this->getInput('password');

            if ($this->userModel->findByEmail($email)) {
                $this->render('register', ['error' => 'Email already registered']);
                return;
            } elseif (empty($name) || empty($email) || empty($password)) {
                $this->render('register', ['error' => 'All fields are required']);
                return;
            }

            // Register the user
            $this->userModel->register($name, $email, $password);

            $this->redirect('/login');
        }
    }

    // Handle user login (GET and POST)
    public function login()
{
    if ($this->isGet()) {
        $this->render('login');
        return;
    }

    if ($this->isPost()) {
        $email = $this->getInput('email');
        $password = $this->getInput('password');

        $user = $this->userModel->findByEmail($email);

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            // Create JWT token
            $token = $this->jwt->createToken([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);

            // Set the token in a cookie (optional, depends on how you want to store the token)
            setcookie('token', $token, time() + 3600, '/');

            // Redirect to homepage
            $this->redirect('/');
        } else {
            $this->render('login', ['error' => 'Invalid credentials']);
        }
    }
}


    // Handle logout
    public function logout()
{
    // Delete the JWT token by setting the cookie to expire in the past
    setcookie('token', '', time() - 3600, '/');

    // Redirect to the login page after logout
    $this->redirect('/login');
}
}
