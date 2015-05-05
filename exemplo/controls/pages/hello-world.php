<?php
    use ON\Register;
    use ON\App;

    Register::set('titulo', 'Hello World');
	App::loadView()
        ->addTemplate('geral')
 	    ->loadPage('hello-world');
