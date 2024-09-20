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
            // Render the registration view for GET requests
            $this->render('register');
            return;
        }

        // Handle POST request (form submission)
        if ($this->isPost()) {
            $name = $this->getInput('name');
            $email = $this->getInput('email');
            $password = $this->getInput('password');

            // Check if the email is already registered
            if ($this->userModel->findByEmail($email)) {
                // Re-render the register view with an error message
                $this->render('register', [
                    'error' => 'Email already registered'
                ]);
                return;
            } elseif (empty($name) || empty($email) || empty($password)) {
                // Re-render the register view with an error message
                $this->render('register', [
                    'error' => 'All fields are required'
                ]);
                return;
            }

            // Register the user and redirect on success
            $this->userModel->register($name, $email, $password);
            $this->redirect('/login');  // Redirect to login page after successful registration
        }
    }

    // Handle user login (GET and POST)
    public function login()
{
    if ($this->isGet()) {
        // Render the login view for GET requests
        $this->render('login');
        return;
    }

    // Handle POST request (form submission)
    if ($this->isPost()) {
        $email = $this->getInput('email');
        $password = $this->getInput('password');

        $user = $this->userModel->findByEmail($email);

        // Check credentials
        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            // Store user information in session, including role
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'], // Include role here
            ];

            // Redirect to homepage or dashboard
            $this->redirect('/');
        } else {
            // Re-render the login view with an error message
            $this->render('login', [
                'error' => 'Invalid credentials'
            ]);
        }
    }
}

    public function logout()
    {
        // Destroy the session and redirect to the login page
        session_destroy();
        $this->redirect('/login');
    }
}
