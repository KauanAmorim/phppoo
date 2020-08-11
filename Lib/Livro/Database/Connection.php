<?php

namespace Livro\Database;

final class Connection 
{

    private function __construct(){}

    public static function getConnection($name)
    {
        // verifica se existe arquivo de configuração para este banco de dados.
        $config_path = "config/{$name}.ini";
        if(\file_exists($config_path)){
            $database_config = \parse_ini_file($config_path);
        } else {
            throw new \Exception("Arquivo {$name}.ini não encontrado");
        }

        // lê as informações contigas no arquivo;
        $user = isset($database_config['user']) ? $database_config['user'] : null;
        $pass = isset($database_config['pass']) ? $database_config['pass'] : null;
        $name = isset($database_config['name']) ? $database_config['name'] : null;
        $host = isset($database_config['host']) ? $database_config['host'] : null;
        $type = isset($database_config['type']) ? $database_config['type'] : null;
        $port = isset($database_config['port']) ? $database_config['port'] : null;

        return self::setConnection(
            $user, $pass, $name,
            $host, $type, $port
        );
    }

    private static function setConnection($user, $pass, $name, $host, $type, $port)
    {
        // descobre qual o tipo (driver) de banco de dados a ser utilizado.
        switch ($type) {
            case 'pgsql':
                $port = $port ? $port : '5432';
                $Connection = new \PDO("pgsql:dbname={$name}; user={$user}; password=${$pass}; host=$host;port={$port}");
                break;
            case 'mysql':
                $port = $port ? $port : '3306';
                $Connection = new \PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
                break;
            case 'sqlite':
                $Connection = new \PDO("sqlite:{$name}");
                break;
            case 'ibase':
                $Connection = new \PDO("firebird:dbname={$name}", $user, $pass);
                break;
            case 'oci8':
                $Connection = new \PDO("oci:dbname={$name}", $user, $pass);
                break;
            case 'mssql':
                $Connection = new \PDO("mssql:host={$host},1433;dbname={$name}", $user, $pass);
                break;
            default:
                throw new \Exception("Não foi encontrado o drive espeficidado em type no .ini");
                break;
        }
        // define para que o PDO lance exceções na ocirrência de erros
        $Connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $Connection;
    }
}