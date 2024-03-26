<?php

require_once __DIR__ . '/vendor/autoload.php';


use App\Application\App;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new App();

$app->run();
