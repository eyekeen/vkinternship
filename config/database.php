<?php

return [
    'driver' => $_ENV['DRIVER'] ?? 'mysql',
    'host' => $_ENV['HOST'] ?? 'localhost',
    'dbname' => $_ENV['DBNAME'] ?? 'db',
    'port' => $_ENV['PORT'] ?? 3306,
    'user' => $_ENV['USER'] ?? 'root',
    'password' => $_ENV['PASSWORD'] ?? '',
];
