<?php
    use ON\Register;
    use ON\App;

    Register::set('titulo', 'Primeiros Passos');
    App::loadView()
 	    ->addTemplate('geral')
 	    ->loadPage('primeiros-passos');
