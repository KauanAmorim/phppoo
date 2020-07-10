<?php

require_once "./vendor/autoload.php";

use \tdg\ProdutoGateway;

$data1 = new \stdClass;
$data1->descricao       = 'Vinho Brasileiro Tinto Merlot';
$data1->estoque         = 10;
$data1->preco_custo     = 12;
$data1->preco_venda     = 18;
$data1->codigo_barras   = '13523453234234';
$data1->data_cadastro   = date('Y-m-d');
$data1->origem          = 'N'; // nacional - é uma merda essa forma de registro.

$data2 = new \stdClass;
$data2->descricao       = 'Vinho Importado Tinto Carmenere';
$data2->estoque         = 10;
$data2->preco_custo     = 18;
$data2->preco_venda     = 29;
$data2->codigo_barras   = '73450345423423';
$data2->data_cadastro   = date('Y-m-d');
$data2->origem          = 'I'; // importado - mesma forma merda.

try {
    
    $ConnectionDB = new \connection\connectdb('./config/config.json', 'phpoo');
    $connection = $ConnectionDB->getConnection();
    
    ProdutoGateway::setConnection($connection);
    $ProdutoGateWay = new ProdutoGateway;

    $ProdutoGateWay->save($data1);
    $ProdutoGateWay->save($data2);

    $produto = $ProdutoGateWay->find(2);
    $produto->estoque += 2;
    $ProdutoGateWay->save($produto);

    foreach ($ProdutoGateWay->all("estoque<=10") as $produto) {
        print $produto->descricao . "<br>\n";
    }

} catch (\Exception $exception) {
    print $exception->getMessage();
}

