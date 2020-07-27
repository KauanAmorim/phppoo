<?php

namespace api;

use \api\Transaction;

abstract class Record 
{
    protected $data; //array contendo os dados do objeto.

    public function __construct($id = null)
    {
        if($id){ // se o ID for informado.
            // carrega o objeto correspondente.
            $object = $this->load($id);
            if($object){
                $this->fromArray($object->toArray());
            }
        }
    }

    public function __clone()
    {
        unset($this->data['id']);
    }

    public function __set($propriedade, $value)
    {
        if(\method_exists($this, 'set_'.$propriedade)){
            // executa o método set_<propriedade>
            call_user_func(array($this, 'set_'.$propriedade), $value);
        } else {
            if($value === null){
                unset($this->data[$propriedade]);
            } else {
                $this->data[$propriedade] = $value; // atribui o valor da propriedade.
            }
        }
    }

    public function __get($propriedade)
    {
        if(\method_exists($this, 'get_'.$propriedade)){
            // executa o método get_<propriedade>
            return call_user_func(array($this, 'get_'.$propriedade));
        } else {
            return $this->data[$propriedade];
        }
    }

    public function __isset($propriedade)
    {
        return isset($this->data[$propriedade]);
    }

    private function getEntity()
    {
        $class = \get_class($this); // obtém o nome da classe.
        return constant("{$class}::TABLENAME"); // retorna a constante de classe TABLENAME.
    }

    public function fromArray($data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function store()
    {
        $prepared = $this->prepare($this->data);

        // verifica se tem ID ou se existe na base de dados
        if(empty($this->data['id']) or (!$this->load($this->id))) {
            $sql = $this->sqlInsert($prepared);
            $insert = true;
        } else {
            $sql = $this->sqlUpdate($prepared);
            $insert = false;
        }

        if ($Connection = Transaction::get()) {
            // cria mensagem de log e executa a consulta
            Transaction::log($sql);
            $stmt = $Connection->prepare($sql);

            // se retornou algum dado
            if ($stmt->execute()) {
                //retorna os dados em forma de objeto
                $id = ($insert) ? $Connection->lastInsertId() : $this->id;
                return $this->load($id);
            }
        } else {
            throw new Exception("Não há transação ativa!!");   
        }
    }

    public function load($id)
    {
        // monta instrução de SELECT
        $sql = "SELECT * FROM {$this->getEntity()}";
        $sql .= " WHERE id = " . (int) $id;

        // obtém transação ativa
        if($Connection = Transaction::get()){
            // cria mensagem de log e executa a consulta
            Transaction::log($sql);
            $result = $Connection->query($sql);

            // se retornou algum dado
            if($result){
                // retorna os dados em forma de objeto
                $object = $result->fetchObject(\get_class($this));
            }
            return $object;
        } else {
            throw new \Exception("Não há transação ativa!!");;
        }
    }

    public function delete($id = null)
    {
        // o ID é o parâmetro ou a propriedade ID
        $id = $id ? $id : $this->id; // livro
        // $id = $id ? $id : $this->data['id'];

        // mostra a string de UPDATE
        $sql = "DELETE FROM {$this->getEntity()}";
        $sql .= ' WHERE id = ' . (int) $this->data['id']; // livro
        // $sql = ' WHERE id =' . (int) $id;

        if ($Connection = Transaction::get()) {
            // faz o log e executa o SQL
            Transaction::log($sql);
            $result = $Connection->exec($sql);
            return $result; // retorna o resultado
        } else {
            throw new \Exception("Não há transação ativa!!");
        }
    }

    public static function find($id) 
    {
        $classname = \get_called_class();
        $ar = new $classname;
        return $ar->load($id);
    }

    public function prepare ($data) 
    {
        $prepared = [];
        foreach ($data as $key => $value) {
            if(\is_scalar($value)) {
                $prepared[$key] = $this->escape($value);
            }
        }
        return $prepared;
    }

    public function escape($value)
    {
        if (is_string($value) and (!empty($value))) {
            // adiciona \ em aspas
            $value = \addslashes($value);
            return "'$value'";
        } else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        } else if ($value !== '') {
            return $value;
        } else {
            return 'NULL';
        }
    }

    private function sqlInsert($prepared)
    {
        return "INSERT INTO {$this->getEntity()} ".
        '( '. implode(', ', array_keys($prepared)) .' )'.
        ' VALUES '.
        '( '. implode(', ', array_values($prepared)) .' )';
    }

    private function sqlUpdate($prepared)
    {
        // monta a string UPDATE
        $sql = "UPDATE {$this->getEntity()} ";
        // monta os pares: coluna=valor,...
        if($prepared){
            foreach ($prepared as $column => $value) {
                if($column !== 'id'){
                    $set[] = "{$column} = {$value}";
                }
            }
        }

        $sql .= ' SET ' . implode(', ', $set);
        $sql .= ' WHERE id=' . (int) $this->data['id'];

        return $sql;
    }


}
