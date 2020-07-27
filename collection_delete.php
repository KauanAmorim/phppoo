<?php

require_once "./vendor/autoload.php";

use \api\Filter;
use \api\Criteria;
use \api\LoggerTXT;
use \api\Expression;
use \api\Repository;
use \api\Transaction;

try {

    // inicia a transação com a base de dados
    Transaction::open('estoque');

    // define o arquivo para LOG
    Transaction::setLogger(new LoggerTXT('./tmp/log_collection_delete.txt'));

    // define o critério de seleção
    $Criteria = new Criteria;
    $Criteria->add(new Filter('descricao', 'like', '%GB%'), Expression::OR_OPERATOR);
    $Criteria->add(new Filter('descricao', 'like', '%FILMAD%'), Expression::OR_OPERATOR);

    // cria repositório
    $repository = new Repository('\model\Produto');
    // exclui os objetos, conforme o critério
    $repository->delete($Criteria);
    Transaction::close(); // fecha a transação

} catch (\Exception $Exception) {
    Transaction::rollback();
    print $Exception->getMessage() . "<br>\n";
    var_dump($Exception->getTrace());
}