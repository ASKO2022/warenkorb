<?php

namespace Cart\Database;

use PDO;
use PDOException;

class Connection
{
    private $pdo;

    public function __construct()
    {
        $dbHost = 'db';
        $dbName = 'db';
        $dbUser = 'db';
        $dbPassword = 'db';
        $dbPort = '3306';

        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";

        try {
            $this->pdo = new PDO($dsn, $dbUser, $dbPassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Verbindungsfehler: " . $e->getMessage();
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function executeSqlFromFile($filePath)
    {
        $sql = file_get_contents($filePath);

        if ($sql === false) {
            echo "Fehler beim Laden der SQL-Datei: $filePath\n";
            return;
        }

        try {
            $this->pdo->beginTransaction();
            $this->pdo->exec($sql);
            $this->pdo->commit();
            echo "SQL-Skript erfolgreich ausgeführt.\n";
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo "Fehler beim Ausführen des SQL-Skripts: " . $e->getMessage() . "\n";
        }
    }


    public function checkTableExists($tableName)
    {
        $stmt = $this->pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$tableName]);
        return $stmt->rowCount() > 0;
    }
}

$connection = new Connection();
