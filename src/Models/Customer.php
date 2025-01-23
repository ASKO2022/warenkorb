<?php

namespace Cart\Models;

use PDO;

use Cart\Database\Connection;
use PDOException;

class Customer
{
    private $pdo;

    public function __construct()
    {
        $db = new Connection();
        $this->pdo = $db->getPdo();
    }

    public function register($username, $email, $password)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE username = ? OR email = ?");
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return "Benutzername oder E-Mail existiert bereits.";
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("INSERT INTO customers (username, email, password) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $hashedPassword);
            $stmt->execute();


            $stmt->execute();
            return "Registrierung erfolgreich!";
        } catch (PDOException $e) {
            return "Fehler bei der Registrierung: " . $e->getMessage();
        }
    }

    public function login($username, $password)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return "Benutzername oder Passwort ist falsch.";
            }

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                return $user; // Rückgabe des Benutzerobjekts für Sitzungsmanagement
            } else {
                return "Benutzername oder Passwort ist falsch.";
            }
        } catch (PDOException $e) {
            return "Fehler beim Login: " . $e->getMessage();
        }
    }
}
