<?php

declare(strict_types=1);

namespace App\Controller;

use \App\Http\Request;
use \App\Model\TipoProduto;

class TipoProdutoController {

    public static function add() {
        $pdo = \App\Persistence\ConnectionCreator::createConnection();

        $request = new Request();

        $body = $request->getBody();

        try {
            self::validaParametros($body);
        } catch(\Exception $e) {
            throw new Exception('Informe parâmetros válidos', 400);
        }


        $sth = $pdo->prepare("INSERT INTO tipo_produto (descricao, imposto) VALUES (:descricao, :imposto)");
        try {
            $sth->bindValue('descricao', $body['descricao']);
            $sth->bindValue('imposto', $body['imposto']);
            $sth->execute();
        } catch(\PDOException $e) {
            return $e->getMessage();
        }

        $result = $pdo->lastInsertId();

        return $result;
    }

    public static function update() {
        $pdo = \App\Persistence\ConnectionCreator::createConnection();

        $request = new Request();

        $body = $request->getBody();

        try {
            if (!isset($body['codigo'])) {
                return "Informe um código";
            }

            self::validaParametros($body);
        } catch(\Exception $e) {
            throw new Exception('Informe parâmetros válidos', 400);
        }


        $sth = $pdo->prepare("UPDATE tipo_produto SET descricao = :descricao, imposto = :imposto WHERE codigo = :codigo");
        try {
            $sth->bindValue('codigo', $body['codigo']);
            $sth->bindValue('descricao', $body['descricao']);
            $sth->bindValue('imposto', $body['imposto']);
            $sth->execute();
        } catch(\PDOException $e) {
            return $e->getMessage();
        }

        if ($sth->rowCount() > 0) {
            return "Operação realizada com sucesso";
        } else {
            return "Registro não encontrado";
        }
    }

    public static function delete() {
        $pdo = \App\Persistence\ConnectionCreator::createConnection();

        $request = new Request();

        $query = $request->getQueryParams();

        try {
            if (!isset($query['codigo'])) {
                return "Informe um código";
            }
        } catch(\Exception $e) {
            throw new Exception('Erro ao validar parâmetros', 400);
        }


        $sth = $pdo->prepare("DELETE FROM tipo_produto WHERE codigo = :codigo");
        try {
            $sth->bindValue('codigo', $query['codigo']);
            $sth->execute();
        } catch(\PDOException $e) {
            return $e->getMessage();
        }

        if ($sth->rowCount() > 0) {
            return "Operação realizada com sucesso";
        } else {
            return "Registro não encontrado";
        }
    }

    public static function all() {
        $pdo = \App\Persistence\ConnectionCreator::createConnection();

        $sth = $pdo->prepare("SELECT * FROM tipo_produto ORDER BY codigo DESC");
        
        try {
            $sth->execute();
        } catch(\PDOException $e) {
            return $e->getMessage();
        }

        $result = $sth->fetchAll();

        return $result;
    }

    private static function validaParametros($body) {
        try {
            if (!isset($body['descricao'])) {
                return "Informe a descrição do tipo";
            }

            if (!isset($body['imposto'])) {
                return "Informe o valor do imposto";
            }
        } catch(\Exception $e) {
            throw new Exception('Informe parâmetros válidos', 400);
        }
    }
}