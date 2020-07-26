<?php

namespace api;

use \api\Expression;

class Filter extends Expression 
{
    private $variable; // variavel
    private $operator; // operador
    private $value; // valor

    public function __construct($variable, $operator, $value)
    {
        // armazena as propriedade
        $this->variable = $variable;
        $this->operator = $operator;

        // transforma o valor de acordo com certas regras de tipo
        $this->value = $this->transform($value);
    }

    private function transform($value)
    {
        // caso seja um array
        if(\is_array($value)){

            foreach ($value as $x) {
                if(\is_integer($x)){
                    $foo[] = $x;
                } elseif(\is_string($x)) {
                    $foo[] = "'$x'";
                }
            }

            // converte um array em string separada por ","
            $result = '('. implode(',', $foo) . ')';

        } elseif (\is_string($value)) {
            $result = "'$value'";
        } elseif (\is_null($value)) {
            $result = 'NULL';
        } elseif (\is_bool($value)) {
            $result = ($value) ? true : false;
        } else {
            $result = $value;
        }

        return $result;
    }

    public function dump()
    {
        return "{$this->variable} {$this->operator} {$this->value}";
    }
}
