<?php

namespace dm;

class Produto 
{
    private $data;

    public function __get($propriedade)
    {
        return $this->data[$propriedade];
    }

    public function __set($propriedade, $value)
    {
        $this->data[$propriedade] = $value;
    }
}