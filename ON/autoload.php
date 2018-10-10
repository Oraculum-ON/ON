<?php

spl_autoload_register(function ($class) {
    // project-specific namespace prefix
    $prefix = 'Oraculum\\';
    $separator = DIRECTORY_SEPARATOR;
    
    // base directory for the namespace prefix
    if (strpos($class, 'Adds') !== false) :
        $base_dir = __DIR__ . $separator;
        $class = str_replace('Adds', 'adds', $class);
    elseif ((strpos($class, 'Tables') !== false) && (defined('MODEL_DIR'))) :
        $base_dir = MODEL_DIR . $separator;
        $class = str_replace('Tables', 'tables', $class);
    else:
        $base_dir = __DIR__ . $separator . 'core' . $separator;
    endif;
    


    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    
    
    $relative_class = str_replace('\\', $separator, $relative_class);
    $relative_class = str_replace('/', $separator, $relative_class);
    
    
    $file = $base_dir . $relative_class . '.php';
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});