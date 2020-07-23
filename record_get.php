<?php

require_once "./vendor/autoload.php";

use \api\Transaction;
use \api\LoggerTXT;
use \model\Produto;

try {

    Transaction::open('estoque');
    Transaction::setLogger(new LoggerTXT('./tmp/log_find.txt'));
    Transaction::log('Buscando um produto');

    $p1 = Produto::find(7);
    print $p1->descricao;

    Transaction::close();

} catch (\Exception $Exception) {
    Transaction::rollback();
    print $Exception->getMessage();
}