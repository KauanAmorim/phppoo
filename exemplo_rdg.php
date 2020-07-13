<?php

require_once "./vendor/autoload.php";

use \DatabaseConnect\Connect;
use \rdg\ProdutoGateway;

try {
    
    $ConnectionDb = new Connect('./config/config.json', 'phpoo');
    $Connection = $ConnectionDb->getConnection();
    ProdutoGateway::setConnection($Connection);

    $produtos = ProdutoGateway::all();
    foreach ($produtos as $produto) {
        $produto->delete();
    }

    $p1 = new ProdutoGateway;
    $p1->descricao = 'Vinho Brasileiro Tinto Merlot';
    $p1->estoque = 10;
    $p1->preco_custo = 12;
    $p1->preco_venda = 18;
    $p1->codigo_barras = '13523453234234';
    $p1->data_cadastro = date('Y-m-d');
    $p1->origem = 'N';
    $p1->save();

    $p2 = new ProdutoGateway;
    $p2->descricao = 'Vinho Importado Tinto Carmenere';
    $p2->estoque = 10;
    $p2->preco_custo = 18;
    $p2->preco_venda = 29;
    $p2->codigo_barras = '73450345423423';
    $p2->data_cadastro = date('Y-m-d');
    $p2->origem = 'I';
    $p2->save();

    $produto = ProdutoGateway::find(1);
    $produto->estoque += 2;
    $produto->save();

} catch (\Exception $Exception) {
    echo $Exception->getMessage() . "<br>\n";
    var_dump($Exception->getTrace());
}