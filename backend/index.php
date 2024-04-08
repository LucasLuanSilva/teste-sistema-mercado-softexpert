<?php

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: *");

require __DIR__ . '/vendor/autoload.php';

use \App\Http\Router;

define('URL', 'http://localhost/api');

$obRouter = new Router(URL);

// Inclui as rotas da API
include __DIR__ . '/app/routes.php';

// Imprime a resposta
$obRouter->run()->sendResponse();