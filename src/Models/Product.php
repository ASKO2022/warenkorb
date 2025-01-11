<?php

namespace Cart\Models;

use PDO;
use Cart\Database\Connection;

class Product
{
    private $pdo;

    public function __construct()
    {
        // Verwende die Connection-Klasse, um das PDO-Objekt zu erhalten
        $db = new Connection();
        $this->pdo = $db->getPdo();
    }

    // Methode zum Hinzufügen eines Produkts
    public function addProduct($name, $description, $price)
    {
        try {
            // Das Bild-Feld wird aus der SQL-Query entfernt
            $stmt = $this->pdo->prepare("INSERT INTO products (name, description, price) VALUES (:name, :description, :price)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);

            $stmt->execute();

            return true; // Produkt erfolgreich hinzugefügt
        } catch (\PDOException $e) {
            return "Fehler beim Hinzufügen des Produkts: " . $e->getMessage();
        }
    }


    // Methode zum Abrufen aller Produkte
    public function getAllProducts()
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Alle Produkte als assoziatives Array zurückgeben
    }
}
