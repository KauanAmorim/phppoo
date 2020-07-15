<?php

require_once "./vendor/autoload.php";

use \api\Connection;
use \ar\Produto;

try {
    
    $Connection = Connection::getConnection('estoque');
    Produto::setConnection($Connection);
           
    $Produto = new Produto;
    $Produto->descricao = 'Café torrado e moído brasileiro';
    $Produto->estoque = 100;
    $Produto->preco_custo = 4;
    $Produto->preco_venda = 7;
    $Produto->codigo_barras = '34963045930455';
    $Produto->data_cadastro = date('Y-m-d');
    $Produto->origem = 'N';
    $Produto->save();

    var_dump($Produto::all());

} catch (\Exception $Exception) {
    echo $Exception->getMessage() . " <br>\n";
    var_dump($Exception->getTrace());
}