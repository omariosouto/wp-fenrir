<?php function the_component_cardpost() { ?>

<div class="cardpost"> 
	<h2 class="cardpost__title">the_component_cardpost</h2>
</div>


<?php
/* Arquivos do Componente */
$dependencies = array(
	array('libs', 'slick/slick.min.js', 'js'),
	array('external', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', 'js'),
	array('dist', 'index.min.js', 'js'),
	array('dist', 'index.min.css', 'css')
);
// inclui os arquivos da lista de dependencias
component_include_front_dependencies(dirname(__FILE__), $dependencies);

} ?>

