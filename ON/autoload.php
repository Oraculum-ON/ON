<?php

    /**  
     * @param string $class The fully-qualified class name.
     * @return void
     */
    function onLoader($class)
    {
        if (strpos($class, 'ON')===TRUE):
            return;
        endif;
        $class=str_replace('ON\\', '', $class);
        if ($class != 'ON'):
            $classfile = PATH.'core\\'.$class.'.php';
        else:
            $classfile = PATH.$class.'.php';
        endif;
        if (file_exists($classfile)):
            include_once($classfile);
        else:
            //throw new Exception('[Error autoload] File not found ('.$classfile.')');
            //echo '[Error autoload] File not found ('.$classfile.')';
        endif;
    }
    spl_autoload_register('onLoader');




//exit;

	/*function __autoload($nomeClasse){
		echo $nomeClasse;
	}*/
/*	function __autoload($lib=NULL) {
		if (is_null($lib)):
			throw new Exception('[Erro RO19] Biblioteca nao informada');
		else:
			//$libfile='core/general/'.$lib.'.php';
			$libfile=$lib.'.php';
			echo PATH.$libfile;
			if (file_exists(PATH.$libfile)):
				include_once(PATH.$libfile);
			else:
				throw new Exception('[Erro RO25] Biblioteca nao encontrada ('.$libfile.') ');
			endif;
		endif;
	}*/
/*
public static function LoadContainer ($lib=NULL) {
	if(is_null($lib)):
	throw new Exception('[Erro RO6] Tipo de Container nao informado');
	else:
	$libfile='core/apps/'.$lib.'.php';
	if(file_exists(PATH.$libfile)):
	include_once($libfile);
	else:
	throw new Exception('[Erro RO12] Tipo de Container nao existente ('.$libfile.') ');
	endif;
	endif;
}
*/
