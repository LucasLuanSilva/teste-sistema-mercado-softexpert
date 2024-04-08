<?php

namespace App\Model;

class TipoProduto {
    private string $descricao;
    private float $imposto;

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function getDescricao(){
        return $this->descricao;
    }
    
    public function setImposto($imposto){
        $this->imposto = $imposto;
    }

    public function getImposto(){
        return $this->imposto;
    }
}