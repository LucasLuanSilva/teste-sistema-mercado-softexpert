<?php

namespace App\Controller;

use \App\Http\Request;

class TipoProdutoController {
    public static function all() {
        $request = new Request();
        error_log(json_encode($request->getQueryParams()));
        error_log(json_encode($request->getBody()));
        return ['teste' => 'ok'];
    }
}