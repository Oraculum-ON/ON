<?php

namespace Oraculum;

class ValidateField
{
    public $valid = true;
    public $errormsg = null;
    
    public $value = null;
    public $required = false;
    public $type = null;

    public function __construct()
    {
    }
    
    public function set($value)
    {
        $this->value = $value;
        return $this;
    }
    
    public function required($type = null)
    {
        $this->required = true;
        $this->type = $type;
        return $this;
    }
    public function msg($msg)
    {
        $this->errormsg =  $msg;
    }
    public function valid()
    {
        if (is_null($this->type)) :
            $this->error = Validate::REQUIRED;
            return !(($this->required) &&
                     (is_null($this->value)));
        else :
            switch ($this->type) :
                case Validate::STRING:
                    return $this->checkString($this->value);
                    $return = is_string($valor);
                break;
                case Validate::NUMERIC:
                    return $this->checkNumeric($this->value);
                    $return = is_numeric($valor);
                break;
                case Validate::INT:
                    return $this->checkInt($this->value);
                    $return = is_int($valor);
                break;
                case Validate::NOT_EMAIL:
                    return !($this->checkEmail($this->value));
                break;
                case Validate::EMAIL:
                    return $this->checkEmail($this->value);
                break;
                case Validate::EMAIL_MX:
                    return $this->checkEmailMX($this->value);
                break;
                case Validate::DATE:
                    return $this->checkDate($this->value);
                break;
                default:
                    return $this->checkString($this->value);
                break;
            endswitch;
        endif;
    }
    
    public function checkString($value)
    {
        return is_string($value);
    }
    
    public function checkNumeric($value)
    {
        return is_numeric($value);
    }
    
    public function checkInt($value)
    {
        return is_int($value);
    }
    
    public function checkEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    
    public function checkEmailMX($value)
    {
        if ($this->checkEmail($value)) :
            $email=explode('@', $value);
            if ((count($email) == 2) &&
                (strpos($email[1], '.'))) :
                return checkdnsrr($email[1], 'MX');
            else :
                return false;
            endif;
        else :
            return false;
        endif;
    }
    
    public function checkDate($value, $format = 'Y-m-d')
    {
        $date = DateTime::createFromFormat($format, $value);
        return (($date) &&
                ($date->format($format) == $date));
    }
}
