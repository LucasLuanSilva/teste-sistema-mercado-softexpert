<?php

use \App\Http\Response;
use \App\Controller;

$obRouter->get('/tipo/produto', [
    function() {
        return new Response(200, Controller\TipoProdutoController::all());
    }
]);

$obRouter->post('/tipo/produto', [
    function() {
        return new Response(200, Controller\TipoProdutoController::all());
    }
]);