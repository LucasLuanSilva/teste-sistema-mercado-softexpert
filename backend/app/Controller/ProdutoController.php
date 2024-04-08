<?php

declare(strict_types=1);

namespace App\Controller;

use \App\Http\Request;
use \App\Model\Produto;

class ProdutoController {

    public static function add() {
        $pdo = \App\Persistence\ConnectionCreator::createConnection();

        $request = new Request();

        $body = $request->getBody();

        try {
            self::validaParametros($body, $pdo);
        } catch(\Exception $e) {
            throw new Exception('Informe parâmetros válidos', 400);
        }


        $sth = $pdo->prepare("INSERT INTO produto (descricao, codigo_tipo, valor) VALUES (:descricao, :codigo_tipo, :valor)");
        try {
            $sth->bindValue('descricao', $body['descricao']);
            $sth->bindValue('codigo_tipo', $body['codigo_tipo']);
            $sth->bindValue('valor', $body['valor']);
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

            self::validaParametros($body, $pdo);
        } catch(\Exception $e) {
            throw new Exception('Informe parâmetros válidos', 400);
        }


        $sth = $pdo->prepare("UPDATE produto SET descricao = :descricao, codigo_tipo = :codigo_tipo, valor = :valor WHERE codigo = :codigo");
        try {
            $sth->bindValue('codigo', $body['codigo']);
            $sth->bindValue('descricao', $body['descricao']);
            $sth->bindValue('codigo_tipo', $body['codigo_tipo']);
            $sth->bindValue('valor', $body['valor']);
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


        $sth = $pdo->prepare("DELETE FROM produto WHERE codigo = :codigo");
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

        $sth = $pdo->prepare("SELECT 
            p.*,
            p.valor as valor_unitario,
            tp.imposto as imposto_unitario
        FROM produto p 
        LEFT JOIN tipo_produto tp ON p.codigo_tipo = tp.codigo
        ORDER BY p.codigo DESC");
        try {
            $sth->execute();
        } catch(\PDOException $e) {
            return $e->getMessage();
        }

        $result = $sth->fetchAll();

        return $result;
    }

    private static function validaParametros($body, $pdo) {
        try {
            $sth = $pdo->prepare("SELECT * FROM tipo_produto WHERE codigo = :codigo_tipo");
            try {
                $sth->bindValue('codigo_tipo', $body['codigo_tipo']);
                $sth->execute();
            } catch(\PDOException $e) {
                return $e->getMessage();
            }

            if (!($sth->rowCount() > 0)) {
                return "Informe um tipo válido";
            }

            if (!isset($body['descricao'])) {
                return "Informe a descrição do produto";
            }

            if (!isset($body['valor'])) {
                return "Informe o valor do produto";
            }
        } catch(\Exception $e) {
            throw new Exception('Informe parâmetros válidos', 400);
        }
    }
}