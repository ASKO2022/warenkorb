<?php

namespace Cart\Database;

use PDO;
use PDOException;

class Connection
{
    private $pdo;
    private string $dbHost;
    private string $dbName;
    private string $dbUser;
    private string $dbPassword;
    private int $dbPort;

    public function __construct($dbHost, $dbName, $dbUser, $dbPassword, $dbPort)
    {
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbPort = $dbPort;


        $dsn = "mysql:host=$this->dbHost;port=$this->dbPort;dbname=$this->dbName";

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
}

$connection = new Connection("db", "db", "db", "db", 3306);
//$connection->executeSqlFromFile("database.sql");
