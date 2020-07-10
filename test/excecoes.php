<?php

class testError extends \exception {}

function verifyError($flg_error){
    if($flg_error){
        // throw new \exception('olá, um erro foi gerado!');
        throw new \testError('olá, um erro foi gerado!');
    }
    return true;
}

try {
    $flg_error = true;
    verifyError($flg_error);
} catch (\Exception $exception) {
    print $exception->getFile() . PHP_EOL;
    print $exception->getCode() . PHP_EOL;
    print $exception->getMessage() . PHP_EOL;
    print_r($exception->getTrace()) . PHP_EOL;
    print $exception->getTraceAsString(). PHP_EOL;
}