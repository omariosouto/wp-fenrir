<?php

/**
*	@COMPONENT '%COMPONENT_NAME%'
*	Description: 
**/

/* Arquivos PHP do Componente */
$dependencies = array(
	'the-component.php',
);

// inclui os arquivos da lista de dependencias
component_include_dependencies( dirname(__FILE__), $dependencies );

?>