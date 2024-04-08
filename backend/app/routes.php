<?php

use \App\Http\Response;
use \App\Controller;

// Usa parse_url para extrair apenas o caminho
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/produto') {
    $obRouter->get('/produto', [
        function() {
            return new Response(200, Controller\ProdutoController::all());
        }
    ]);

    $obRouter->post('/produto', [
        function() {
            return new Response(200, Controller\ProdutoController::add());
        }
    ]);

    $obRouter->put('/produto', [
        function() {
            return new Response(200, Controller\ProdutoController::update());
        }
    ]);

    $obRouter->delete('/produto', [
        function() {
            return new Response(200, Controller\ProdutoController::delete());
        }
    ]);
} else if ($path === '/tipo/produto') {
    $obRouter->get('/tipo/produto', [
        function() {
            return new Response(200, Controller\TipoProdutoController::all());
        }
    ]);
    
    $obRouter->post('/tipo/produto', [
        function() {
            return new Response(200, Controller\TipoProdutoController::add());
        }
    ]);
    
    $obRouter->put('/tipo/produto', [
        function() {
            return new Response(200, Controller\TipoProdutoController::update());
        }
    ]);
    
    $obRouter->delete('/tipo/produto', [
        function() {
            return new Response(200, Controller\TipoProdutoController::delete());
        }
    ]);
} else if ($path === '/venda') {
    $obRouter->get('/venda', [
        function() {
            return new Response(200, Controller\VendaController::all());
        }
    ]);
    
    $obRouter->post('/venda', [
        function() {
            return new Response(200, Controller\VendaController::add());
        }
    ]);
    
    $obRouter->delete('/venda', [
        function() {
            return new Response(200, Controller\VendaController::delete());
        }
    ]);
}