<?php

namespace api;

use \api\Connection;

final class Transaction 
{
    private static $Connection;
    private static $Logger;

    private function __construct(){}

    public static function open($database)
    {
        if(empty(self::$Connection)){
            self::$Connection = Connection::getConnection($database);
            self::$Connection->beginTransaction();
            self::$Logger = null;
            return true;
        }
        return false;
    }

    public static function get()
    {
        return (self::$Connection) ? self::$Connection : false;
    }

    public static function rollback()
    {
        if(self::$Connection){
            self::$Connection->rollback();
            self::$Connection = null;
            return true;
        }
        return false;
    }

    public static function close()
    {
        if(self::$Connection){
            self::$Connection->commit();
            self::$Connection = null;
            return true;
        }
        return false;
    }

    public static function setLogger(\api\Logger $Logger)
    {
        self::$Logger = $Logger;
    }

    public static function log($message)
    {
        if(self::$Logger){
            self::$Logger->write($message);
        }
    }
}
