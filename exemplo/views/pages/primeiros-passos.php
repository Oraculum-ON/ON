<?php ON\Register::set('titulo', 'Primeiros Passos'); ?>
<div id="content">
    <h1>Primeiros Passos</h1>
<p>
Para obter o Oraculum Framework basta realizar o download da &uacute;ltima
vers&atilde;o est&aacute;vel dispon&iacute;vel no site do projeto.<br />
Atualmente o mesmo est&aacute; dispon&iacute;vel no endere&ccedil;o
<a href="http://oraculumframework.org/">http://oraculumframework.org/</a><br />
<br />
Os requisitos para
instalar e utilizar o Oraculum Framework s&atilde;o os seguintes:<br />
</p>
<ul style="text-align:left; width:70%; margin:auto;">
    <li>
        Servidor Web Apache 2 ou superior.
    </li>
    <li>
        PHP 5.2 ou superior.
    </li>
    <li>
        Recomenda-se habilitar a op&ccedil;&atilde;o de reescrita de URL no Apache,
por&eacute;m, os testes com a mesma desabilitada foram bem sucedidos.
    </li>
    <li>
        Sistema Operacional Windows ou Linux. Ainda n&atilde;o foram realizados testes
em outras plataformas como Mac OS.
    </li>
</ul>
<br /> <br /> <br />
<p>Ap&oacute;s obter o arquivo,
descompacte-o com um programa descompactador na pasta de publica&ccedil;&otilde;es
(document_root) do servidor Web. Acesse o endere&ccedil;o de acesso do servidor
web no seu navegador, seguido do nome da pasta, ou subpastas onde o framework foi
inclu&iacute;do dentro da pasta de publica&ccedil;&otilde;es.<br /> <br />
Se aparecer uma tela informando que o Oraculum Framework foi inicializado com
sucesso ocorreu tudo certo, caso contr&aacute;rio, procure verificar se voc&ecirc;
descompactou o framework na pasta correta, e que est&aacute; acessando o
endere&ccedil;o correto.<br /> <br />
</p>
<h2>Configura&ccedil;&otilde;es</h2>
<br /> <br /><p> As configura&ccedil;&otilde;es
b&aacute;sicas que devem ser realizadas s&atilde;o as seguintes<br />
Alterar o par&acirc;metro <strong>RewriteBase</strong> do arquivo .htaccess para a URL que
&eacute; chamada<br />
<br />
<pre style="margin:auto; text-align:left; width:80%;">&lt;IfModule mod_rewrite.c&gt;
RewriteEngine On
# Determina a base de reescrita
RewriteBase /oraculum
# Verifica se a url nao corresponde a um arquivo existente
RewriteCond %{REQUEST_FILENAME} !-f
# Verifica se a url nao corresponde a um diretorio existente
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule .([a-zA-Z0-9]+)?$ index.php
&lt;/IfModule&gt;</pre><br /> <br />
<p>
Exemplo: Se para acessar o seu site voc&ecirc; digita
http://localhost/oraculum/ na barra de endere&ccedil;os, <strong>RewriteBase</strong>
ser&aacute; /oraculum/<br /><br />
Este mesmo valor informado em <strong>RewriteBase</strong> deve ser informado
em <strong>setBaseUrl</strong> no arquivo <strong>index.php</strong>
ao configurar o <strong>FrontController</strong><br /><br />
<?php highlight_string('<?php
    include(\'./bootstrap.php\');
    Oraculum::LoadContainer(\'WebApp\');
    $app=new Oraculum_WebApp();
    $app->FrontController()
        ->setBaseUrl(\'/oraculum/\')
        ->setDefaultPage(\'home\')
        ->setErrorPage(\'404\')
        ->start();'); ?>
<br /><br />
Com isso, o framework se comportar&aacute; da seguinte forma:
<ul style="width:75%; text-align:left; margin:auto;">
    <li>
        Quando for chamada a p&aacute;gina http://localhost/oraculum/ ser&aacute;
chamado o controlador "home"
    </li>
    <li>
        Quando for chamada a p&aacute;gina http://localhost/oraculum/teste  tentar&aacute; chamar o controlador "teste"
    </li>
    <li>
        Se n&atilde;o encontrar o controlador "teste" (ou qual for chamado) ser&aacute; carregado o controlador "404".
    </li>
</ul>

</p>
</div>