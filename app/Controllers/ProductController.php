<?php  

namespace App\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    private $productModel;

    public function __construct($db)
    {
        $this->productModel = new Product($db);
    }

    public function index()
    {
        $products = $this->productModel->getAllProducts();
        $this->render('products/index', ['products' => $products]);
    }

    public function create()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $this->render('products/create');
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $product = $this->productModel->getProductById($id);
        $this->render('products/edit', ['product' => $product]);
    }

    public function update($id)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/login');
            return;
        }

        $this->productModel->deleteProduct($id);
        $this->redirect('/products');
    }
}