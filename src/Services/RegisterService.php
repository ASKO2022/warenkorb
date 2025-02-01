<?php

namespace Cart\Services;


use Cart\Database\Connection;
use PDOException;

class RegisterService
{
    private $pdo;
    public function __construct()
    {
        $db = new Connection("db", "db", "db", "db", 3306);
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

            if ($stmt->execute()) {
                return "Registrierung erfolgreich!";
            } else {
                return "Fehler bei der Registrierung: Benutzer konnte nicht gespeichert werden.";
            }
        } catch (PDOException $e) {
            return "Fehler bei der Registrierung: " . $e->getMessage();
        }
    }
}