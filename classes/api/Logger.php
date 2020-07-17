<?php

namespace api;

abstract class Logger 
{
    protected $filename; // local do arquivo de LOG.

    public function __construct($filename)
    {
        $this->filename = $filename;
        \file_put_contents($filename, ''); // limpa o conteúdo do arquivo
    }

    // define o método write como obrigatório.
    abstract public function write($message);
}