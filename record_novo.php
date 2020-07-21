<?php

require_once "./vendor/autoload.php";

use \api\Transaction;
use \api\LoggerTXT;
use \model\Produto;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    
    Transaction::open('estoque');
    Transaction::setLogger(new LoggerTXT('./tmp/log_novo.txt'));
    Transaction::log('Inserindo produto novo');

    $p1 = new Produto;
    $p1->descricao = 'Cerveja artesanal IPA';
    $p1->estoque = 50;
    $p1->preco_custo = 8;
    $p1->preco_venda = 12;
    $p1->codigo_barras = '75363453234234';
    $p1->data_cadastro = date('Y-m-d');
    $p1->origem = 'N';
    var_dump($p1->store());

    Transaction::close();

} catch (\Exception $Exception) {
    Transaction::rollback();
    print $Exception->getMessage();
}