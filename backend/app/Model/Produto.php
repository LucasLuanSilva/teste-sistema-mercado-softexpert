<?php

namespace App\Model;

class TipoProduto {
    private string $descricao;
    private int $codigoTipo;
    private float $valor;
    
    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setTipo($codigoTipo){
        $this->codigoTipo = $codigoTipo;
    }

    public function getTipo(){
        return $this->codigoTipo;
    }

    public function setValor($valor){
        $this->valor = $valor;
    }

    public function getValor(){
        return $this->valor;
    }
}