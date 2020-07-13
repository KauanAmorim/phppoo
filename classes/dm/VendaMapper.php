<?php

namespace dm;

class VendaMapper 
{
    private static $Connection;

    public static function setConnection(\PDO $Connection)
    {
        self::$Connection = $Connection;
    }

    public static function save(\dm\Venda $Venda)
    {
        $data = date("Y-m-d");
        $id = self::getLastId() + 1;
        $sql = "INSERT INTO venda (id, data_venda) VALUES ('$id', '$data')";
        print $sql . "<br>\n";
        self::$Connection->query($sql);
        $Venda->setId($id);

        foreach ($Venda->getItens() as $item) {
            $quantidade = $item[0];
            $produto = $item[1];
            $preco = $produto->preco;

            $sql = "INSERT INTO item_venda (
                id_venda, id_produto, quantidade, preco
            ) VALUES (
                '{$id}',
                '{$produto->id}',
                '{$quantidade}',
                '{$preco}'
            )";

            print $sql . " <br>\n";
            self::$Connection->query($sql);
        }
    }

    private function getLastId()
    {
        $sql = "SELECT MAX(id) as max FROM venda";
        $result = self::$Connection->query($sql);
        $data = $result->fetchObject();
        return $data->max;
    }
}
