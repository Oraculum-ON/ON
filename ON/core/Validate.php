<?php

namespace Oraculum;

class Validate
{
    public $valid = true;
    public $error = [];
    public $fields = [];
    public $values = [];

    const STRING = 'string';
    const NUMERIC = 'numeric';
    const INT = 'int';
    const NOT_EMAIL = 'not e-mail';
    const EMAIL = 'e-mail';
    const EMAIL_MX = 'e-mail server';
    const DATE = 'date';
    const REQUIRED = 'required';

    public function __construct()
    {
    }

    public function field($field)
    {
        $this->fields[$field] = new ValidateField();

        return $this->fields[$field];
    }

    public function valid($stop = false)
    {
        foreach ($this->fields as $field => $attr) :
            $this->$field = $attr->value;
        if (!$attr->valid()) :
                $this->valid = false;
        $attr->type;
        $this->error[] = $attr->errormsg;
        if ($stop) :
                    return $this->valid;
        endif;
        endif;
        endforeach;

        return $this->valid;
    }
}
