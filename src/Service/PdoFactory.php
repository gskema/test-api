<?php

namespace App\Service;

use PDO;
use SensitiveParameter;

class PdoFactory
{
    public static function create(
        #[SensitiveParameter]
        string $dbHost,
        #[SensitiveParameter]
        string $dbName,
        #[SensitiveParameter]
        string $dbUser,
        #[SensitiveParameter]
        string $dbPass,
    ): PDO {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbName);
        $pdo = new PDO($dsn, $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // default is silent
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // default is both

        return $pdo;
    }
}
