<?php

namespace App\Application\Database;

use App\Application\Config\Config;

abstract class Connection implements ConnectionInterface
{

    private string $driver;
    private string $host;
    private int $port;
    private string $dbname;
    private string $user;
    private string $password;

    public function __construct()
    {
        $this->driver = Config::get('database.driver');
        $this->host = Config::get('database.host');
        $this->port = Config::get('database.port');
        $this->dbname = Config::get('database.dbname');
        $this->user = Config::get('database.user');
        $this->password = Config::get('database.password');

        $this->usersExists();
    }

    public function connect(): \PDO
    {
        return new \PDO(
            "$this->driver:host=$this->host;port=$this->port;dbname=$this->dbname",
            $this->user,
            $this->password
        );
    }

    public function usersExists(): void
    {
        $this->connect()->exec('CREATE TABLE IF NOT EXISTS `users` (
            `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `email` varchar(250) DEFAULT NULL,
            `password` varchar(250) DEFAULT NULL,
            `token` varchar(255) DEFAULT NULL,
            `expired_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT current_timestamp(),
            UNIQUE (`email`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }
}
