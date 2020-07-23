<?php

require_once "./vendor/autoload.php";

use \api\Transaction;
use \api\LoggerTXT;
use \model\Produto;

try {
    
    Transaction::open('estoque');
    Transaction::setLogger(new LoggerTXT('./tmp/log_delete.txt'));
    Transaction::log('Clonando um produto');

    $p1 = Produto::find(6);
    if($p1 instanceof Produto){
        $p1->delete();
    } else {
        throw new \Exception('Produto não localizado');
    }

    Transaction::close();

} catch (\Exception $Exception) {
    Transaction::rollback();
    print $Exception->getMessage();
}