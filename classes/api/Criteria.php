<?php

namespace api;

use \api\Expression;

class Criteria extends Expression 
{
    private $expressions; // armazena a lista de expressões.
    private $operators; // armazena a lista de operadores.
    private $properties; // propriedades do critério.

    public function __construct()
    {
        $this->expressions = [];
        $this->operators = [];
    }

    public function add(
        Expression $expression, $operator = self::AND_OPERATOR
    ){
        // na primeira vez, não precisamos concatenar
        if(empty($this->expressions)){
            $operator = null;
        }
        // agrega o resultado da expressão á lista de expressões
        $this->expressions[] = $expression;
        $this->operators[] = $operator;
    }

    public function dump()
    {
        // concatena a lista de expressões
        if(is_array($this->expressions)){
            if(count($this->expressions) > 0 ){
                $result = '';
                foreach($this->expressions as $i => $expression){
                    $operator = $this->operators[$i];
                    // concatena o operator com a respectiva expressão
                    $result .= $operator . $expression->dump() . ' ';
                }
                $result = trim($result);
                // var_dump($result);
                return "({$result})";
            }
        }
    }

    public function setProperty($property, $value)
    {
        $this->properties[$property] = (isset($value)) ? $value : null;
    }

    public function getProperty($property)
    {
        if(isset($this->properties[$property])){
            return $this->properties[$property];
        }
    }
}