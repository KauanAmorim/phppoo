<?php

require_once "./vendor/autoload.php";

use \api\Transaction;
use \ar\ProdutoComTransacao;

try {
    
    Transaction::open('estoque');

    $p1 = new ProdutoComTransacao;
    $p1->descricao = 'Chocolate Amargo';
    $p1->estoque = 80;
    $p1->preco_custo = 4;
    $p1->preco_venda = 7;
    $p1->codigo_barras = '68323453234234';
    $p1->data_cadastro = date('Y-m-d');
    $p1->origem = 'N';
    $p1->save();

    Transaction::close();

} catch (\Exception $Exception) {
    Transaction::rollback();
    echo $Exception->getMessage() . " <br>\n";
    var_dump($Exception->getTrace());
}