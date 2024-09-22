<?php 

namespace App\Controllers;

use App\Models\Product;
use Core\JWTHandler;

class ProductController extends Controller
{
    private $productModel;
    private $jwtUser;  // Store the decoded user from JWT

    public function __construct($db)
    {
        $this->productModel = new Product($db);
        
        // Extract JWT and decode it to get the user's role
        $this->jwtUser = $this->getJwtUser();
    }

    private function getJwtUser()
    {
        // Get the JWT token from the cookie
        $token = $_COOKIE['token'] ?? null;
        if (!$token) {
            return null;
        }

        // Decode the token using the JWTHandler
        $jwtHandler = new JWTHandler();
        $decodedToken = $jwtHandler->validateToken($token);

        if ($decodedToken) {
            return (array) $decodedToken->data;  // Return decoded user data
        }

        return null;  // Return null if token is invalid or expired
    }

    protected function isAdmin()
    {
        // Check if the user is an admin
        return $this->jwtUser && $this->jwtUser['role'] === 'admin';
    }

    public function index()
    {
        $products = $this->productModel->getAllProducts();
        $this->render('products/index', [
            'products' => $products,
            'jwtUser' => $this->jwtUser  // Pass the decoded JWT user
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $this->redirect('/login');  // Redirect to login if not admin
            return;
        }

        $this->render('products/create');
    }

    public function store()
    {
        if (!$this->isAdmin()) {
            $this->redirect('/login');
            return;
        }

        $name = $this->getInput('name');
        $description = $this->getInput('description');
        $category = $this->getInput('category');
        $quantity = $this->getInput('quantity');

        $this->productModel->addProduct($name, $description, $category, $quantity);
        $this->redirect('/products');
    }

    public function edit($id)
    {
        if (!$this->isAdmin()) {
            $this->redirect('/login');
            return;
        }

        $product = $this->productModel->getProductById($id);
        $this->render('products/edit', ['product' => $product]);
    }

    public function update($id)
    {
        if (!$this->isAdmin()) {
            $this->redirect('/login');
            return;
        }

        $name = $this->getInput('name');
        $description = $this->getInput('description');
        $category = $this->getInput('category');
        $quantity = $this->getInput('quantity');

        $this->productModel->updateProduct($id, $name, $description, $category, $quantity);
        $this->redirect('/products');
    }

    public function delete($id)
    {
        if (!$this->isAdmin()) {
            $this->redirect('/login');
            return;
        }

        $this->productModel->deleteProduct($id);
        $this->redirect('/products');
    }
}
