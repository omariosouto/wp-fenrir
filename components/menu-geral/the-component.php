<?php function the_component_menu_geral() { ?>





<?php
/* Arquivos do Componente */
$dependencies = array(
	array('dist', 'index.min.js', 'js'),
	array('dist', 'index.min.css', 'css')
);
// inclui os arquivos da lista de dependencias
component_include_front_dependencies(dirname(__FILE__), $dependencies);
?>

<?php } ?>