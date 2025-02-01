<?php

namespace Cart\Services;


use Cart\Database\Connection;
use PDO;
use PDOException;

class LoginService
{
    private $pdo;

    public function __construct()
    {
        $db = new Connection("db", "db", "db", "db", 3306);
        $this->pdo = $db->getPdo();
    }

    public function login($username, $password)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE username = ?");
            $stmt->bindParam(1, $username);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return "Benutzername oder Passwort ist falsch.";
            }

            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if (password_verify($password, $user->password)) {
                return $user;
            }

            return "Benutzername oder Passwort ist falsch.";

        } catch (PDOException $e) {
            return "Fehler beim Login: " . $e->getMessage();
        }
    }
}