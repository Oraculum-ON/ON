<?php
    use ON\Register;
    use ON\App;

    Register::set('titulo', 'Active Record');
	App::loadView()
 	    ->addTemplate('geral')
 	    ->loadPage('active-record');
