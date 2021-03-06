<?php

require_once "./vendor/autoload.php";

use \api\Filter;
use \api\Criteria;
use \api\LoggerTXT;
use \api\Repository;
use \api\Transaction;

try {

    // inicia a transação com a base de dados
    Transaction::open('estoque');

    // define o arquivo para LOG
    Transaction::setLogger(new LoggerTXT('./tmp/log_collection_get.txt'));

    // define o critério de seleção
    $Criteria = new Criteria;
    $Criteria->add(new Filter('estoque', '>', '10'));
    $Criteria->add(new Filter('origem', '=', 'N'));

    // cria repositório
    $repository = new Repository('\model\Produto');
    // carrega os objetos, conforme o critério
    $produtos = $repository->load($Criteria);
    if($produtos) {
        echo "Produtos <br>\n";
        // percorre todos os objetos
        foreach ($produtos as $produto) {
            echo ' ID: ' . $produto->id;
            echo ' - Descricao: ' . $produto->descricao;
            echo ' - Estoque: ' . $produto->estoque;
            echo "<br>\n";
        }
    }

    print "Quantidade: " . $repository->count($Criteria);
    Transaction::close();

} catch (\Exception $Exception) {
    Transaction::rollback();
    print $Exception->getMessage() . "<br>\n";
    var_dump($Exception->getTrace());
}