<?php

namespace App\Core;
use PDO;
use PDOException;
use PDOStatement;

class Database
{

    private static ?PDO $instance = null;
    private static string $host;
    private static string $database;
    private static string $username;
    private static string $password;
    private static string $driver;

    private function __construct() {}

    public static function init(string $host, string $database, string $username, string $password, string $driver = 'pgsql'): void
    {
        self::$host = $host;
        self::$database = $database;
        self::$username = $username;
        self::$password = $password;
        self::$driver = $driver;
        self::$instance = null;
    }

    public static function getInstance(): ?PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = match (self::$driver) {
                    'pgsql' => "pgsql:host=" . self::$host.";dbname=" .self::$database,
                    'mysql' => "mysql:host=" . self::$host.";database=" .self::$database . ";charset=utf8mb4",
                    default => throw new PDOException("Driver non supporté : " . self::$driver),
                };

                self::$instance = new PDO($dsn, self::$username, self::$password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                error_log("Connexion à la base de données échouée : " . $e->getMessage());
                return null;
            }
        }

        return self::$instance;
    }
 

    public static function query(string $sql, bool $single = true): mixed
    {
        $query = self::getInstance()->query($sql);
        return $single ? $query->fetch(PDO::FETCH_OBJ) : $query->fetchAll(PDO::FETCH_OBJ);
    }

    private static function prepare(string $sql, array $datas): PDOStatement
    {
        $prepare = Database::getInstance()->prepare($sql);
        $prepare->execute($datas);
        return $prepare;
    }

    public static function executeQuery(string $sql, array $datas, bool $single = true): mixed
    {
        $statement = self::prepare($sql, $datas);
        return $single ? $statement->fetch(PDO::FETCH_OBJ) : $statement->fetchAll(PDO::FETCH_OBJ);
    }

   

    public static function executeUpdate(string $sql, array $datas): int|string
    {
        $statement = self::prepare($sql, $datas);
        return (str_starts_with(strtoupper(trim($sql)), 'INSERT')) ? self::getInstance()->lastInsertId() : $statement->rowCount();
    }

   public static function beginTransaction(): void
    {
        self::getInstance()->beginTransaction();
    }

    public static function commit(): void
    {
        self::getInstance()->commit();
    }

    public static function rollback(): void
    {
        self::getInstance()->rollBack();
    }

    public static function getAllData(string $tableName): array
    {
        $sql = "SELECT * FROM $tableName";
        $result = self::query($sql, false);
        return $result;
    }

    
}