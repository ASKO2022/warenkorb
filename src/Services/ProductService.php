<?php

namespace Cart\Services;

use Cart\Models\Product;
use Cart\Database\Connection;
use PDO;

class ProductService
{
    private $pdo;

    public function __construct()
    {
        $db = new Connection("db", "db", "db", "db", 3306);
        $this->pdo = $db->getPdo();
    }

    public function addProduct($name, $description, $price)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $name, \PDO::PARAM_STR);
            $stmt->bindParam(2, $description, \PDO::PARAM_STR);
            $stmt->bindParam(3, $price);
            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            return "Fehler beim Hinzufügen des Produkts: " . $e->getMessage();
        }
    }

    public function updateProduct($id, $name, $description, $price)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
            $stmt->bindParam(1, $name, \PDO::PARAM_STR);
            $stmt->bindParam(2, $description, \PDO::PARAM_STR);
            $stmt->bindParam(3, $price);
            $stmt->bindParam(4, $id, \PDO::PARAM_INT);
            $stmt->execute();

            return true;
        }
        catch (\PDOException $e) {
            return "Fehler beim Hinzufügen des Produkts: " . $e->getMessage();
        }
    }

    public function deleteProduct($id)
    {
        try {
            if (!is_numeric($id)) {
                throw new \InvalidArgumentException("Ungültige ID");
            }

            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            error_log("Fehler beim Löschen des Produkts: " . $e->getMessage());
            return false;
        } catch (\InvalidArgumentException $e) {
            error_log("Ungültige ID übergeben: " . $e->getMessage());
            return false;
        }
    }


    public function getAllProducts()
    {
        $stmt = $this->pdo->query("SELECT * FROM products");

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $products[] = new Product(
                $row->id,
                $row->name,
                $row->description,
                $row->price
            );
        }

        return $products;
    }
}
