<?php

namespace dm;

class Venda 
{
    private $id;
    private $itens;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function addItem($quantidade, \dm\Produto $produto)
    {
        $this->itens[] = [$quantidade, $produto];
    }

    public function getItens()
    {
        return $this->itens;
    }
}