<?php

require_once "./vendor/autoload.php";

use \api\Transaction;
use \api\LoggerTXT;
use \model\Produto;

try {
    
    Transaction::open('estoque');
    Transaction::setLogger(new LoggerTXT('./tmp/log_protect.txt'));
    Transaction::log('Clonando um produto');

    $p1 = Produto::find(7);
    $p1->estoque = 'dois';
    $p1->store();

    Transaction::close();

} catch (\Exception $Exception) {
    Transaction::rollback();
    print $Exception->getMessage();
}