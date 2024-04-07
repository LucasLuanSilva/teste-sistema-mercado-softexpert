<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Http\Router;

define('URL', 'http://localhost/api');

$obRouter = new Router(URL);

// Inclui as rotas da API
include __DIR__ . '/app/routes.php';

// Imprime a resposta
$obRouter->run()->sendResponse();