<?php

require_once "./vendor/autoload.php";

use \connection\connectdb;
use \tdg\Produto;

try {

    $ConnectionDB = new connectdb('./config/config.json', 'phpoo');
    $connection = $ConnectionDB->getConnection();

    Produto::setConnection($connection);
    $produtos = Produto::all();
    foreach ($produtos as $produto) {
        $produto->delete();
        // var_dump($produto);
    }

    $p1 = new Produto;
    $p1->descricao = 'Vinho Brasileiro Tinto Merlot';
    $p1->estoque = 10;
    $p1->preco_custo = 12;
    $p1->preco_venda = 18;
    $p1->codigo_barras = '13523453234234';
    $p1->data_cadastro = date('Y-m-d');
    $p1->origem = 'N';
    $p1->save();

    $p2 = new Produto;
    $p2->descricao = 'Vinho Brasileiro Tinto Merlot';
    $p2->estoque = 10;
    $p2->preco_custo = 12;
    $p2->preco_venda = 18;
    $p2->codigo_barras = '13523453234234';
    $p2->data_cadastro = date('Y-m-d');
    $p2->origem = 'N';
    $p2->save();
 
    $p3 = Produto::find(1);
    print "Descrição: " . $p3->descricao . "<br>\n";
    print "Margem de lucro: " . $p3->getMargemLucro() . "% <br>\n";
    $p3->registraCompra(14, 5);
    $p3->save();

} catch (\Exception $Exception) {
    echo $Exception->getMessage() . "\n<br>";
    var_dump($Exception->getTrace());
}