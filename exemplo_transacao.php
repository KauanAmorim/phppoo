<?php

require_once "./vendor/autoload.php";

use \api\Transaction;
use \ar\Produto;

try {

    Transaction::open('estoque');

    // obtém a conexão ativa.
    $Connection = Transaction::get();
    Produto::setConnection($Connection);

    $p1 = new Produto;
    $p1->descricao = 'Vinho Brasileiro Tinto Merlot';
    $p1->estoque = 10;
    $p1->preco_custo = 12;
    $p1->preco_venda = 18;
    $p1->codigo_barras = '13523453234234';
    $p1->data_cadastro = date('Y-m-d');
    $p1->origem = 'N';
    $p1->save();

    throw new Exception('Exceção proposital');

    $p1 = new Produto;
    $p1->descricao = 'Vinho Importado Tinto Carmenere';
    $p1->estoque = 10;
    $p1->preco_custo = 18;
    $p1->preco_venda = 29;
    $p1->codigo_barras = '73450345423423';
    $p1->data_cadastro = date('Y-m-d');
    $p1->origem = 'I';
    $p1->save();   

    Transaction::close();

} catch (\Exception $Exception) {
    Transaction::rollback();
    echo $Exception->getMessage() . " <br>\n";
    var_dump($Exception->getTrace());
}