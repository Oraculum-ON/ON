<?php

namespace Oraculum;

class ActiveRecord extends Model
{
    private $fields = [];
    protected $className = null;
    protected $tableName = null;
    protected $key = ['id'];
    protected $keyvalue = [];
    protected $filterQuery = [];
    protected $validateMsg = null;
    protected $intFields = ['int',
                            'smallint',
                            'mediumint',
                            'bigint',
                            'bit',
                            'timestamp'];
    protected $floatFields = ['float',
                              'decimal',
                              'double'];
    protected $types = ['bigint', 'binary', 'bit', 'blob', 'boolean',
                                'char', 'date', 'datetime', 'decimal',
                                'double', 'enum', 'float', 'geometry',
                                'geometrycollection', 'int', 'linestring',
                                'longblob', 'longtext', 'mediumblob',
                                'mediumint', 'mediumtext', 'multilinestring',
                                'multipoint', 'multipolygon', 'point',
                                'polygon', 'real', 'serial', 'set', 'smallint',
                                'text', 'time', 'timestamp', 'tinyblob',
                                'tinyint', 'tinytext', 'varbinary', 'varchar',
                                'year', ];

    public function __construct($class = null)
    {
        if (!is_null($class)) :
            $this->className = $class;
            if (strpos($class, '\\')) :
                $table = explode('\\', $class);
                $this->tableName = strtolower($table[count($table) - 1]);
            else :
                $this->tableName = $table;
            endif;
        endif;

        return $this;
    }

    public function getLines($limit = null)
    {
        $limit = (int) $limit;
        $query = 'SELECT count(*) as \'count\' FROM '.$this->tableName;
        if ($limit > 0) :
            $query .= ' LIMIT '.$limit;
        endif;
        $rows = $this->getResult($query);

        return (int) $rows[0]->count;
    }

    public function getAll($limit = null, $offset = null, $orderby = null, $order = 'ASC')
    {
        $limit = (int) $limit;
        $offset = (int) $offset;
        $query = 'SELECT * FROM '.$this->tableName;
        if (count($this->filterQuery) > 0) :
            $query .= ' WHERE '.implode(' AND ', $this->filterQuery);
        endif;
        if (!is_null($orderby)) :
            $query .= ' ORDER BY '.$orderby.' '.$order;
        endif;
        if ($limit > 0) :
            $query .= ' LIMIT '.$limit;
            if ($offset > 0) :
                $query .= ' OFFSET '.$offset;
            endif;
        endif;

        return $this->getResult($query);
    }

    public function getFirst()
    {
        $this->updateKeyValue();
        $rows = $this->getAll(1, 0, $this->key[0]);

        return ((count($rows) > 0) ? $rows[0] : null);
    }

    public function getLast()
    {
        $this->updateKeyValue();
        $rows = $this->getAll(1, 0, $this->key[0], 'DESC');

        return ((count($rows) > 0)? $rows[0] : null);
    }

    private function getByTableField($field, $value, $type = '%u')
    {
        if (count($this->filterQuery) > 0) :
            $filter = ' AND '.implode(' AND ', $this->filterQuery);
        else :
            $filter = '';
        endif;
        $query = sprintf(
            'SELECT * '.
                         '  FROM '.$this->tableName.
                         ' WHERE '.$field.'='.($type == '%u' ? $type : '"'.$type.'"').
                         ' '.$filter.
                         ' LIMIT 1',
            $this->secsql($value)
        );
        $rows = $this->getResult($query);
        
        return ((count($rows) > 0)? $rows[0] : null);
    }

    private function getAllByTableField($field, $value, $type = '%u', $limit = null, $offset = null)
    {
        $filter = '';
        $limit = (int) $limit;
        $offset = (int) $offset;
        $sqllimit = null;
        if ($limit > 0) :
            $sqllimit = 'LIMIT '.$limit;
            if ($offset > 0) :
                $sqllimit .= ' OFFSET '.$offset;
            endif;
        endif;
        if (count($this->filterQuery) > 0) :
            $filter .= ' AND '.implode(' AND ', $this->filterQuery);
        endif;
        if (is_array($value)) :
            $value = 'IN ('.implode(',', $value).')';
            $query = sprintf(
                'SELECT * '.
                             '  FROM '.$this->tableName.
                             ' WHERE '.$field.' '.$type.' '.$sqllimit,
                $this->secsql($value)
            );
        else :
            if (stripos($value, '%') !== false) :
                $query = sprintf(
                    'SELECT * '.
                                 '  FROM '.$this->tableName.
                                 ' WHERE '.$field.' LIKE "'.$type.'"'.
                                 ' '.$filter.
                                 ' '.$sqllimit,
                    $this->secsql($value)
                );
            else :
                $query = sprintf(
                    'SELECT * '.
                                 '  FROM '.$this->tableName.
                                 ' WHERE '.$field.'='.$type == '%u' ? $type : '"'.$type.'"'.
                                 ' '.$filter.
                                 ' '.$sqllimit,
                    $this->secsql($value)
                );
            endif;
        endif;

        return $this->getResult($query);
    }

    private function filterByTableField($field, $value, $type = '%u')
    {
        if ($type == '%u') :
            $this->filterQuery[] = sprintf(''.$field.'='.$type.'', $this->secsql($value));
        else :
            $this->filterQuery[] = sprintf(''.$field.'="'.$type.'"', $this->secsql($value));
        endif;

        return $this;
    }

    public function __set($name, $value)
    {
        if (!is_null($value)) :
            $this->fields[$name] = $value;
        else :
            $this->fields[$name] = null;
        endif;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->fields)) :
            return $this->fields[$name];
        else :
            throw new Exception('[Erro CGAR168] Campo \''.$name.'\' inexistente');
        endif;
    }

    public function __call($name, $values)
    {
        $value = (isset($values[0])) ? $values[0] : null;
        if (isset($values[1])) :
            $type = $values[1];
        else :
            $type = ((gettype($value) == 'integer') ? '%u' : '%s');
        endif;
        
        if (stripos($name, 'getBy') !== false) :
            $field = strtolower(str_replace('getBy', '', $name));

            return $this->getByTableField($field, $value, $type);
        
        elseif (stripos($name, 'getAllBy') !== false) :
                $field = strtolower(str_replace('getAllBy', '', $name));
                $limit = (isset($values[2])) ? $values[2] : null;
                $offset = (isset($values[3])) ? $values[3] : null;

                return $this->getAllByTableField($field, $value, $type, $limit, $offset);
        
        elseif (stripos($name, 'filterBy') !== false) :
            $field = strtolower(str_replace('filterBy', '', $name));
            if (!is_null($value)) :
        
                return $this->filterByTableField($field, $value, $type);
        
            else :
                return $this;
            endif;
        endif;
    }

    public function fetch($rows)
    {
        $return = [];
        foreach ($rows as $row) :
            $obj = new self($this->className);
            if (!empty($this->key)) :
                $obj->setKey($this->key);
            endif;
            foreach ($row as $field => $value) :
                if (!is_int($field)) :
                    $obj->$field = $value;
                endif;
            endforeach;
            $return[] = clone $obj;
        endforeach;

        return $return;
    }

    public function insert($debug = false)
    {
        $values = null;
        $fields = null;
        $eval = '$q=sprintf(\'INSERT INTO '.$this->tableName.' ';
        if (count($this->fields) > 0) :
            foreach ($this->fields as $field => $value) :
                if (is_null($values)) :
                    $eval .= '(';
                else :
                    $eval .= ',';
                    $values .= ',';
                    $fields .= ',';
                endif;
                    $eval .= $field;
                    $values .= '"%s"';
                    $fields .= '$this->secsql($this->'.$field.')';
            endforeach;
        endif;
        $eval .= ')';
        $eval .= ' VALUES ('.$values.')\','.$fields.');';
        if ($debug) :
            echo $eval;
        endif;
        eval($eval);
        $this->execSQL($q, $debug);
        $this->keyvalue = [$this->getInsertId()];

        return $this;
    }

    public function update($debug = false)
    {
        $fields = null;
        $eval = '$q=sprintf(\'UPDATE '.$this->tableName.' SET ';
        if (count($this->fields) > 0) :
            foreach ($this->fields as $field => $value) :
                if (!is_null($fields)) :
                    $eval .= ',';
                endif;
                if (is_null($value)) :
                    $eval .= $field.'=NULL ';
                    $fields .= '';
                else :
                    $eval .= $field.'="%s" ';
                    $fields .= '$this->secsql($this->'.$field.'),';
                endif;
            endforeach;
        endif;
        $eval .= 'WHERE '.$this->key[0].'="%u"';
        $fields .= '$this->secsql('.$this->keyvalue[0].')';
        $eval .= '\','.$fields.');';
        if ($debug) :
            echo $eval;
        endif;
        eval($eval);
        $this->execSQL($q, $debug);
        $this->keyvalue = [$this->getInsertId()];

        return $this;
    }

    public function delete($debug = false)
    {
        $this->updateKeyValue();
        $query = sprintf(
            'DELETE FROM '.$this->tableName.
                         ' WHERE '.$this->key[0].'="%u"',
            $this->secsql($this->keyvalue[0])
        );
        if ($debug) :
            echo $query;
        endif;
        $this->execSQL($query);

        return $this;
    }

    public function save($validate = true, $exception = true, $debug = false)
    {
        $valid = true;
        if ($validate) :
            $valid = $this->validate($exception);
        endif;
        if ($valid) :
            $this->updateKeyValue();
            if (!empty($this->keyvalue)) :
                return $this->update($debug);
            else :
                return $this->insert($debug);
            endif;
        else :
            return false;
        endif;
    }

    public function setKey($key = ['id'])
    {
        $this->key = $key;
        $this->updateKeyValue();
    }

    private function updateKeyValue()
    {
        $this->keyvalue = [];
        foreach ($this->key as $key) :
            if (isset($this->fields[$key])) :
                $this->keyvalue[] = $this->fields[$key];
            endif;
        endforeach;
    }

    private function getKeyValue($index = null)
    {
        if (!is_null($index)) :
            return $this->keyvalue[$index];
        else :
            return $this->keyvalue;
        endif;
    }

    public function getFieldList()
    {
        return $this->fields;
    }

    public function secsql($string)
    {
        //$string=mysql_real_escape_string($string, self::$connection);
        return addslashes($string);
    }

    public function validate($exception = true)
    {
        $realfieldlist = [];
        $fields = self::$connection->query('DESC '.$this->tableName)->fetchAll();
        foreach ($fields as $dbField) :
            $realfieldlist[] = $dbField['Field'];
            if (!$this->validateField($dbField)) :
        
                if ($exception) :
                    throw new Exception($this->validateMsg);
                    return false;
                endif;
            endif;
        endforeach;

        foreach ($this->fields as $field => $value) :
            if (!in_array($field, $realfieldlist)) :
                $this->validateMsg = 'Campo \''.$field.'\' n&atilde;o encontrado na entidade \''.$this->tableName.'\'';
        
                if ($exception) :
                    throw new Exception($this->validateMsg);
                endif;
                return false;
            endif;
        endforeach;

        return true;
    }
    
    private function validateField($dbField)
    {
        return $this->validateNotNulls($dbField);
        
        /* if ($field['Key']=='MUL'):
        Por motivo de desempenho, a validacao de chave estrangeira deve ser realizada
        pelo proprio banco e apenas tratada a excessao, e nao pela linguagem para evitar
        verificacoes desnecessarias que ja sao tratadas pelos gerenciadores de banco de dados */
    }
    
    private function validateNotNulls($dbField)
    {
        $empty = ((!isset($this->fields[$dbField['Field']])) ||
                  (is_null($this->fields[$dbField['Field']])) ||
                  ($this->fields[$dbField['Field']] == ''));
        if ($empty) :
            if (($dbField['Extra'] != 'auto_increment') &&
                ($dbField['Default'] == '') &&
                (($dbField['Null'] == 'NO'))) :
                $this->validateMsg = 'Campo \''.$dbField['Field'].'\' n&atilde;o foi preenchido';
                return false;
            endif;
        else :
            $this->convertType($dbField);
        endif;

        return true;
    }

    private function convertType($dbField)
    {
        // Validando Tipos
        $types = '['.implode('|', $this->types).']';
        preg_match($types, $dbField['Type'], $type);
        if ($type[0] != 'enum') :
            preg_match('/[0-9]+/', $dbField['Type'], $size);
        else :
            $size[0] = 0;
        endif;
        switch ($type) :
            case (in_array($type, $this->intFields)):
                $this->fields[$dbField['Field']] = (int) $this->fields[$dbField['Field']];
                break;
            case (in_array($type, $this->floatFields)):
                $this->fields[$dbField['Field']] = (float) $this->fields[$dbField['Field']];
                break;
        endswitch;
        $this->convertSize($dbField, $size);
    }

    private function convertSize($dbField, $size)
    {
        // Validando Tamanho. Caso exceda o limite o valor é truncado
        if ((isset($size)) && ($size > 0)) :
            if ((strlen($this->fields[$dbField['Field']])) > $size) :
                $this->fields[$dbField['Field']] = substr($this->fields[$dbField['Field']], 0, $size);
            endif;
        endif;
    }
}
