<?php
    use ON\Register;
    use ON\App;

    Register::set('titulo', 'Home');
	App::loadView()
 	    ->addTemplate('geral')
 	    ->loadPage('home');
