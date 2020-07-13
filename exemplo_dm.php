<?php

require_once "./vendor/autoload.php";

use \DatabaseConnect\Connect;
use \dm\Venda;
use \dm\Produto;
use \dm\VendaMapper;

try {

    $ConnectionDb = new Connect('./config/config.json', 'phpoo');
    $Connection = $ConnectionDb->getConnection();
    VendaMapper::setConnection($Connection);

    $p1 = new Produto;
    $p1->id = 1;
    $p1->preco = 12;

    $p2 = new Produto;
    $p2->id = 2;
    $p2->preco = 14;

    $Venda = new Venda;
    
    // adiciona alguns produtos
    $Venda->addItem(10, $p1);
    $Venda->addItem(20, $p2);

    VendaMapper::save($Venda);
    // deu para entender, mas o exemplo foi um fail.
    // procurar mais exemplos e explicações sobre da data mapper.

} catch (\Exception $Exception) {
    echo $Exception->getMessage() . ' <br>\n';
    var_dump($Exception->GetTrace());
}