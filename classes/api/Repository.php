<?php

namespace api;

use \api\Transaction;

final class Repository 
{
    private $activeRecord; // classe manipulada pelo repositório

    public function __construct($class)
    {
        $this->activeRecord = $class;
    }

    public function load(\api\Criteria $Criteria)
    {
        // instancia a instrução de SELECT
        $sql = "SELECT * FROM ". constant($this->activeRecord. '::TABLENAME');

        // obtém a cláusula WHERE do objeto criteria.
        if($Criteria){
            $expression = $Criteria->dump();
            if($expression){
                $sql .= ' WHERE ' . $expression;
            }
        }

        $order = $Criteria->getProperty('order');
        $limit = $Criteria->getProperty('limit');
        $offset = $Criteria->getProperty('offset');

        $sql .= ($order) ? ' ORDER BY ' . $order : '';
        $sql .= ($limit) ? ' LIMIT ' . $limit : '';
        $sql .= ($offset) ? ' OFFSET ' . $offset : '';

        // obtém transação ativa
        if($Connection = Transaction::get()){
            Transaction::log($sql); // registra mensagem de log

            // executa a consulta no banco de dados
            $result = $Connection->Query($sql);
            $results = [];

            if($result) {
                // percorre os resultados da consulta, retornando um objeto.
                while($row = $result->fetchObject($this->activeRecord)){
                    $results[] = $row;
                }

                //$results = $result->fetchAll(PDO::FETCH_CLASS, $this->activeRecord);
            }
            return $results;
        } else {
            throw new \Exception("Não há transação ativa!!");
        }
    }

    public function delete(\api\Criteria $Criteria) 
    {
        $sql = "DELETE FROM " . constant($this->activeRecord.'::TABLENAME');

        $expression = $Criteria->dump();
        if($expression){
            $sql .= ' WHERE ' . $expression;
        }

        // obtém transação ativa
        if($Connection = Transaction::get()){
            Transaction::log($sql); // registra mensagem de log
            $result = $Connection->exec($sql); // executa instrução de DELETE
            return $result;
        } else {
            throw new \Exception("Não há transação ativa!!");
        }
    }

    public function count(\api\Criteria $Criteria)
    {
        $sql = "SELECT count(*) FROM " . constant($this->activeRecord.'::TABLENAME');
        $expression = $Criteria->dump();
        if($expression){
            $sql .= ' WHERE ' . $expression;
        }

        // obtém transação ativa
        if($Connection = Transaction::get()){
           Transaction::log($sql); // registra mensagem de log
           $result = $Connection->Query($sql); // executa instrução de SELECT
           if($result){
               $row = $result->fetch();
            }
            return $row[0]; // retorna o resultado
        } else {
            throw new \Exception('Não há transação ativa!!');
        }
    }
}