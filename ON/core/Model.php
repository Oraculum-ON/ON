<?php

namespace Oraculum;

class Model
{
    private $dsn = null;
    private $dsntype = 1;
    private $user = null;
    private $pass = null;
    private $host = null;
    private $driver = null;
    private $database = null;
    private $driveroptions = [];
    private $model = null;
    public static $connection = null;

    public function __construct($model = null)
    {
        if (!defined('MODEL_DIR')) :
                define('MODEL_DIR', 'models');
        endif;

        return (!is_null($model)) ? $this->loadModel($model) : $this;
    }

    public function loadModel($model = null)
    {
        if ((!is_null($model))) :
            $model = strtolower($model);
            $modelfile = MODEL_DIR.'/'.$model.'.php';
            if (file_exists($modelfile)) :
                include $modelfile;
            else :
                throw new Exception('[Error '.__METHOD__.'] Modelo nao encontrado ('.$modelfile.') ');
            endif;
            if ($this->dsntype == 1) :
                $dsn = preg_split('[://|:|@|/]', $this->dsn);
                $this->driver = strtolower($dsn[0]);
                if ($this->driver == 'sqlite') :
                    $this->user = '';
                    $this->pass = '';
                    $this->host = '';
                    $this->database = $dsn[2];
                    $this->driveroptions = null;
                else :
                    $this->user = $dsn[1];
                    $this->pass = $dsn[2];
                    $this->host = $dsn[3];
                    $this->database = $dsn[4];
                    $this->driveroptions = isset($dsn[5]) ? $dsn[5] : null;
                    $this->dsn = $this->driver.
                    ':host='.$this->host.';dbname='.$this->database;
                endif;
            endif;
            $this->model = $model;
        endif;
        if ((!isset(self::$connection)) || (!is_null(self::$connection))) :
            $this->PDO();
        endif;

        return $this;
    }

    public static function closeModel()
    {
        self::$connection = null;
    }

    public function loadTable($model = null, $key = 'id')
    {
        if (!is_null($model)) :
            $model = strtolower($model);
            $class = ucwords($model);
            $modelfile = MODEL_DIR.'/tables/'.$model.'.php';
            if (file_exists($modelfile)) :
                include $modelfile;
            else :
                $class = ucwords($model);
                if (!class_exists($class)) :
                    $eval = 'namespace Oraculum\Tables;';
                    $eval .= 'use Oraculum\ActiveRecord;';
                    $eval .= ' class '.$class.' extends ActiveRecord {';
                    $eval .= ' public function __construct(){';
                    $eval .= '     parent::__construct(get_class($this))';
                    $eval .= '     ->setKey(array(\''.$key.'\'));';
                    $eval .= ' }';
                    $eval .= '}';
                    eval($eval);
                endif;
            endif;
            return true;
        else :
            throw new Exception('[Error '.__METHOD__.'] Modelo nao informado ('.$model.') ');
        endif;
        return $this;
    }

    public function PDO()
    {
        $this->driveroptions = [];
        if (extension_loaded('pdo')) :
            if (in_array($this->driver, \PDO::getAvailableDrivers())) :
                try {
                    self::$connection = new \PDO($this->dsn, $this->user, (!$this->pass ? '' : $this->pass), $this->driveroptions);
                    self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                } catch (\PDOException $e) {
                    throw new Exception('[Error '.__METHOD__.'] PDO Connection Error: '.$e->getMessage());
                }

                return self::$connection;
            else :
                throw new Exception('[Error '.__METHOD__.'] Nao ha driver disponivel para \''.$this->driver.'\'');
            endif;
        else :
            throw new Exception('[Error '.__METHOD__.'] Extensao PDO nao carregada');
        endif;
    }

    public function generateAR($table = 'all', $create = true)
    {
        if ($table == 'all') :
            if ($this->driver == 'sqlite') :
                $tables = self::$connection->query('SELECT name FROM sqlite_master WHERE type=\'table\';')->fetchAll();
            else :
                $tables = self::$connection->query('SHOW TABLES')->fetchAll();
            endif;
            foreach ($tables as $table) :
                $this->generateAR($table[0], $create);
            endforeach;
        else :
            $table = strtolower($table);
            $classear = ucwords($table);
            $class = 'class '.$classear." extends Oraculum\ActiveRecord{\n";
            $class .= "\tpublic function __construct(){\n";
            $class .= "\t\tparent::__construct(get_class(\$this));\n";
            $class .= "\t}\n";
            $class .= "}\n";
            if ($create) :
                eval($class);
            else :
                return "<?php \n".$class;
            endif;
        endif;
    }

    public function getTable($table = null)
    {
        if (is_null($table)) :
            throw new Exception('[Error '.__METHOD__.'] Tabela nao informada');
        else :
            return $this->loadTable($table);
        endif;
    }

    public function setDsn($dsn = null)
    {
        if (is_null($dsn)) :
            throw new Exception('[Error '.__METHOD__.'] DSN nao informado');
        else :
            $this->dsn = $dsn;
            $dsn = preg_split('[://|:|@|/]', $this->dsn);
            $this->driver = strtolower($dsn[0]);
            if ($this->driver == 'sqlite') :
                $this->user = '';
                $this->pass = '';
                $this->host = '';
                $this->database = $dsn[1];
                $this->driveroptions = null;
            else :
                $this->user = $dsn[1];
                $this->pass = $dsn[2];
                $this->host = $dsn[3];
                $this->database = $dsn[4];
                $this->driveroptions = isset($dsn[5]) ? $dsn[5] : null;
            endif;
        endif;
    }

    public function getModelName()
    {
        return $this->model;
    }
    public static function execSQL($sql, $debug = false)
    {
        if ($debug) :
            echo '<br />SQL: <pre>' . $sql . '</pre>';
        endif;

        try {
            return self::$connection->query($sql);
        } catch (\PDOException $e) {
            throw new Exception('PDO Connection Error: '.$e->getMessage());
        }
    }

    public function getInsertId()
    {
        try {
            return self::$connection->lastInsertId();
        } catch (\PDOException $e) {
            throw new Exception('PDO Connection Error: '.$e->getMessage());
        }
    }

    public function start()
    {
        $this->execSQL('begin');
    }

    public function commit()
    {
        $this->execSQL('commit');
    }

    public function rollback()
    {
        $this->execSQL('rollback');
    }

    public function getResult($query, $debug = false)
    {
        $rows=$this->execSQL($query, $debug);
        return $this->fetch($rows);
    }

    public function count($query)
    {
        return $query->rowCount();
    }
}
