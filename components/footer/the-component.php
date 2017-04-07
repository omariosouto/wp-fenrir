<?php function the_component_footer() { ?>

<h1>FOOTER</h1>
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex veritatis, consectetur suscipit libero neque qui blanditiis commodi doloribus nisi facere voluptatum autem velit eius accusantium nobis hic repudiandae recusandae esse!
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex veritatis, consectetur suscipit libero neque qui blanditiis commodi doloribus nisi facere voluptatum autem velit eius accusantium nobis hic repudiandae recusandae esse!
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex veritatis, consectetur suscipit libero neque qui blanditiis commodi doloribus nisi facere voluptatum autem velit eius accusantium nobis hic repudiandae recusandae esse!
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex veritatis, consectetur suscipit libero neque qui blanditiis commodi doloribus nisi facere voluptatum autem velit eius accusantium nobis hic repudiandae recusandae esse!
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex veritatis, consectetur suscipit libero neque qui blanditiis commodi doloribus nisi facere voluptatum autem velit eius accusantium nobis hic repudiandae recusandae esse!
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex veritatis, consectetur suscipit libero neque qui blanditiis commodi doloribus nisi facere voluptatum autem velit eius accusantium nobis hic repudiandae recusandae esse!

<?php
/* Arquivos do Componente */
$dependencies = array(
	//array('libs', 'slick/slick.min.js', 'js'),
	//array('libs', 'slick/slick.min.js', 'js'),
	array('external', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', 'js'),
	array('dist', 'index.min.js', 'js'),
	array('dist', 'index.min.css', 'css')
);
// inclui os arquivos da lista de dependencias
component_include_front_dependencies(dirname(__FILE__), $dependencies);
?>

<?php } ?>