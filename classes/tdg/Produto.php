<?php

namespace tdg;

use \tdg\ProdutoGateway;

class Produto 
{
    private static $connection;
    private $data;

    public function __get($propriedade)
    {
        return $this->data[$propriedade];
    }

    public function __set($propriedade, $value)
    {
        $this->data[$propriedade] = $value;
    }

    public static function setConnection($connection)
    {
        self::$connection = $connection;
        ProdutoGateway::setConnection($connection);
    }

    public static function find($id)
    {
        $ProdutoGateway = new ProdutoGateway;
        return $ProdutoGateway->find($id, '\tdg\Produto');
    }

    public static function all($filter = '')
    {
        $ProdutoGateway = new ProdutoGateway;
        return $ProdutoGateway->all($filter, '\tdg\Produto');
    }

    public function delete()
    {
        $ProdutoGateway = new ProdutoGateway;
        return $ProdutoGateway->delete($this->id);
    }

    public function save()
    {
        $ProdutoGateway = new ProdutoGateway;
        return $ProdutoGateway->save((object) $this->data);
    }

    public function getMargemLucro()
    {
        return (($this->preco_venda - $this->preco_custo) / $this->preco_custo) * 100;
    }

    public function registraCompra($custo, $quantidade)
    {
        $this->custo = $custo;
        $this->estoque += $quantidade;
    }
}