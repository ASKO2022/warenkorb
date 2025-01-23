<?php

namespace Cart\Models;

use PDO;
use Cart\Database\Connection;

class Product
{
    private $pdo;

    public function __construct()
    {
        $db = new Connection();
        $this->pdo = $db->getPdo();
    }

    public function addProduct($name, $description, $price)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $description);
            $stmt->bindParam(3, $price);

            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            return "Fehler beim Hinzufügen des Produkts: " . $e->getMessage();
        }
    }


    // Methode zum Abrufen aller Produkte
    public function getAllProducts()
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
