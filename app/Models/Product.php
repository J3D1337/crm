<?php  

namespace App\Models;

use PDO;

class Product
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addProduct($name, $description, $category, $quantity)
    {
        $sql = 'INSERT INTO products (name, description, category, quantity) VALUES (:name, :description, :category, :quantity)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':quantity', $quantity);

        return $stmt->execute();
    }

    public function getAllProducts()
    {
        $sql = 'SELECT * FROM products';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getProductById($id)
    {
        $sql = 'SELECT * FROM products WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProduct($id, $name, $description, $category, $quantity)
    {
        $sql = 'UPDATE products SET name = :name, description = :description, category = :category, quantity = :quantity WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':quantity', $quantity);

        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        $sql = 'DELETE FROM products WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }   
}