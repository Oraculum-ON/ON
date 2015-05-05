<?php ON\Register::set('titulo', 'Suporte'); ?>
<div id="content">
    <h1>Banco de Dados: Active Record</h1>
<p><a href="http://en.wikipedia.org/wiki/Active_record_pattern">Active Record</a> &eacute; um padr&atilde;o de desenvolvimento que realiza o gerenciamento de registros de entidades de bancos de dados atrav&eacute;s de objetos. Veja o exemplo abaixo...</p>
<p>
<?php highlight_string('<?php
/* Carrega a configuracao do banco */
$db=new Oraculum_Models(\'mysql\');
/*
A classe procura por um arquivo mysql.php dentro da pasta models semelhante a este arquivo

A partir de entao, LoadModelClass ja\' mapeia a entidade/tabela e cria o
objeto relacionado com todos os atributos
*/
/* Mapeia a tabela/entidade noticias do banco */

$db->LoadModelClass(\'noticias\');

/* Cadastro */

$noticia=new Noticias();
$noticia->titulo=\'teste\';
$noticia->texto=\'texto da noticia\';
$noticia->save();

/* Busca todas */
$noticias=new Noticias();
$noticias=$noticias->getAll();
foreach ($noticias as $noticia) {
echo $noticia->titulo.\'<br />\'; // Exibindo o titulo
}

/* Busca mais de uma com algum filtro */
$noticias=new Noticias();
$noticias=$noticias->getAllByPublicada(true);<br />foreach ($noticias as $noticia) {
echo $noticia->titulo.\'<br />\'; // Exibindo o titulo
}

/* Busca uma apenas */
$noticias=new Noticias();
$noticia=$noticias->getByCodigo(1); // Poderia ser getByTitulo ou outro campo
echo $noticia->titulo; // Exibindo o titulo

/* Removendo */
$noticia->delete();'); ?></p>
<p>D&uacute;vidas podem ser sanadas atrav&eacute;s do menu suporte ;)</p>
<p>Boa sorte</p>

</p>
</div>