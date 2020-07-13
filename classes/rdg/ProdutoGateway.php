<?php

namespace rdg;

class ProdutoGateway
{
    private static $Connection;
    private $data;

    public function __get($propriedade)
    {
        return $this->data[$propriedade];
    }

    public function __set($propriedade, $value)
    {
        $this->data[$propriedade] = $value;
    }

    public static function setConnection(\PDO $Connection)
    {
        self::$Connection = $Connection;
    }

    public static function find($id)
    {
        $sql = "SELECT * FROM produto WHERE id = '$id'";
        print "$sql <br>\n";
        $result = self::$Connection->query($sql);
        return $result->fetchObject(__CLASS__);
    }

    public static function all($filter = '')
    {
        $sql = "SELECT * FROM produto ";
        $sql .= ($filter) ? "WHERE $filter" : '';
        print "$sql <br>\n";
        $result = self::$Connection->query($sql);
        return $result->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function delete()
    {
        $sql = "DELETE FROM produto WHERE id = '{$this->id}'";
        print "$sql <br>\n";
        return self::$Connection->query($sql);
    }

    public function save()
    {
        $sql = (empty($this->data['id'])) ? $this->sqlInsert() : $this->sqlUpdate();
        print "$sql <br>\n";
        return self::$Connection->exec($sql); // executa instrução SQL.
    }

    private function sqlInsert()
    {
        $id = $this->getLastId() + 1;
        return "INSERT INTO produto (
            id, descricao, estoque, preco_custo,
            preco_venda, codigo_barras, data_cadastro,
            origem
        ) VALUES (
            '{$id}',
            '{$this->descricao}',
            '{$this->estoque}',
            '{$this->preco_custo}',
            '{$this->preco_venda}',
            '{$this->codigo_barras}',
            '{$this->data_cadastro}',
            '{$this->origem}'
        )";
    }

    private function sqlUpdate()
    {
        return "UPDATE produto SET 
            descricao = '{$this->descricao}',
            estoque = '{$this->estoque}',
            preco_custo = '{$this->preco_custo}',
            preco_venda = '{$this->preco_venda}',
            codigo_barras = '{$this->codigo_barras}',
            data_cadastro = '{$this->data_cadastro}',
            origem = '{$this->origem}'
        WHERE id = '{$this->id}'";
    }

    private function getLastId()
    {
        $sql = "SELECT MAX(id) as max FROM produto";
        $result = self::$Connection->query($sql);
        $data = $result->fetch(\PDO::FETCH_OBJ);
        return $data->max;
    }
}