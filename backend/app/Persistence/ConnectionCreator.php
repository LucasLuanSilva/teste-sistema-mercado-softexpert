<?php

namespace App\Persistence;

use PDO;

class ConnectionCreator {
    
    public static function createConnection(): PDO {
        $host = 'localhost';
        $dbname = 'mercado_softexpert';
        $user = 'postgres';
        $password = 'admin';

        $connection = new PDO("pgsql:host=$host;port=5432;dbname=$dbname", $user, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }

}