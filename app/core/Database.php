<?php

class Database
{
    private array $config;
    private ?PDO $connection = null;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/database.php';
    }

    public function connect(): PDO
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->config['host'],
            $this->config['port'],
            $this->config['database'],
            $this->config['charset']
        );

        try {

            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            return $this->connection;

        } catch (PDOException $e) {

            die('Error de conexión con la base de datos.');

        }
    }
}