<?php

namespace tdg;

class ProdutoGateway 
{
    private static $connection;

    public static function setConnection(\PDO $connection)
    {
        self::$connection = $connection;
    }

    public function find($id, $class = 'stdClass')
    {
        $sql = "SELECT * FROM produto WHERE id = '$id' ";
        print "$sql <br>\n";
        $result = self::$connection->query($sql);
        return $result->fetchObject($class);
    }

    public function all($filter, $class = 'stdClass')
    {
        $sql = "SELECT * FROM produto ";
        $sql .= ($filter) ? "WHERE $filter" : "";
        print "$sql <br>\n";
        $result = self::$connection->query($sql);
        return $result->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM produto WHERE id = '$id' ";
        print "$sql <br>\n";
        return self::$connection->query($sql);
    }

    public function save($data)
    {
        $sql = (empty($data->id)) ? $this->sqlInsert($data) : $this->sqlUpdate($data);
        print "$sql <br>\n";
        return self::$connection->exec($sql); // executa instrução SQL.
    }

    private function sqlInsert($data)
    {
        $id = $this->getLastId();
        $id++;
        return "INSERT INTO produto (
            id, descricao, estoque,
            preco_custo, preco_venda,
            codigo_barras, data_cadastro,
            origem
        ) VALUES (
            '{$id}',
            '{$data->descricao}',
            '{$data->estoque}',
            '{$data->preco_custo}',
            '{$data->preco_venda}',
            '{$data->codigo_barras}',
            '{$data->data_cadastro}',
            '{$data->origem}'
        )";
    }

    private function sqlUpdate($data)
    {
        return "UPDATE produto SET
            descricao       = '{$data->descricao}',
            estoque         = '{$data->estoque}',
            preco_custo     = '{$data->preco_custo}',
            preco_venda     = '{$data->preco_venda}',
            codigo_barras   = '{$data->codigo_barras}',
            data_cadastro   = '{$data->data_cadastro}',
            origem          = '{$data->origem}'
            WHERE id        = '{$data->id}'
        ";
    }

    private function getLastId()
    {
        $sql = "SELECT MAX(id) as max FROM produto";
        $result = self::$connection->query($sql);
        $data = $result->fetch(\PDO::FETCH_OBJ);
        return $data->max;
    }
}