<?php

declare(strict_types=1);

namespace App\Controller;

use \App\Http\Request;
use \App\Model\Venda;

class VendaController {

    public static function add() {
        $pdo = \App\Persistence\ConnectionCreator::createConnection();

        $request = new Request();

        $body = $request->getBody();

        $pdo->beginTransaction();

        $sth = $pdo->prepare('INSERT INTO venda (valor, imposto) VALUES (:valor, :imposto)');

        try {
            $sth->bindValue('valor', $body['valor']);
            $sth->bindValue('imposto', $body['imposto']);
            $sth->execute();
        } catch(\PDOException $e) {
            $pdo->rollBack();
            return $e->getMessage();
        }

        $result = $pdo->lastInsertId();

        foreach ($body['itens'] as $item) {
            $sth = $pdo->prepare('INSERT INTO itens_venda (quantidade, valor_unitario, imposto_unitario, codigo_venda, codigo_produto) VALUES (:quantidade, :valor_unitario, :imposto_unitario, :codigo_venda, :codigo_produto)');
            try {
                $sth->bindValue('quantidade', $item['quantidade']);
                $sth->bindValue('valor_unitario', $item['valor_unitario']);
                $sth->bindValue('imposto_unitario', $item['imposto_unitario']);
                $sth->bindValue('codigo_venda', $result);
                $sth->bindValue('codigo_produto', $item['codigo']);
                $sth->execute();
            } catch(\PDOException $e) {
                $pdo->rollBack();
                return $e->getMessage();
            }
        }

        $pdo->commit();

        return $result;
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


        $sth = $pdo->prepare("DELETE FROM venda WHERE codigo = :codigo");
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

        $sth = $pdo->prepare("SELECT * FROM venda ORDER BY codigo DESC");
        
        try {
            $sth->execute();
        } catch(\PDOException $e) {
            return $e->getMessage();
        }

        $result = $sth->fetchAll();

        return $result;
    }
}